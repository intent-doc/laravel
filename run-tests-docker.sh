#!/bin/bash

# Script to run tests on multiple PHP/Laravel versions using Docker
# Generates a compatibility matrix with the results

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Directory for logs
LOG_DIR="test-results"
mkdir -p "$LOG_DIR"

# Version arrays
PHP_VERSIONS=("8.2" "8.3" "8.4")
LARAVEL_VERSIONS=("10.*" "11.*" "12.*")

# Results matrix
declare -A RESULTS

echo -e "${YELLOW}=====================================${NC}"
echo -e "${YELLOW}Running Docker Tests${NC}"
echo -e "${YELLOW}=====================================${NC}"
echo ""

# Function to run test on a specific combination
run_test() {
    local PHP_VERSION=$1
    local LARAVEL_VERSION=$2
    local KEY="${PHP_VERSION}-${LARAVEL_VERSION}"

    # Sanitize Laravel version for filename (replace * with x)
    local LARAVEL_SAFE="${LARAVEL_VERSION//\*/x}"

    # Skip excluded combinations (PHP 8.2 is minimum for Laravel 11+)
    # Currently all combinations are supported

    echo -e "${YELLOW}→ Testing PHP $PHP_VERSION - Laravel $LARAVEL_VERSION${NC}"

    LOG_FILE="$LOG_DIR/php-${PHP_VERSION}-laravel-${LARAVEL_SAFE}.log"

    # Run test in Docker
    if docker run --rm \
        -v "$(pwd):/app" \
        -w /app \
        php:${PHP_VERSION}-cli \
        bash -c "
            # Install system dependencies
            apt-get update -qq && apt-get install -y -qq \
                git \
                zip \
                unzip \
                libzip-dev \
                libpng-dev \
                libxml2-dev \
                libcurl4-openssl-dev > /dev/null 2>&1

            # Install PHP extensions
            docker-php-ext-install -j\$(nproc) \
                zip \
                gd \
                soap \
                pdo_mysql \
                bcmath > /dev/null 2>&1

            # Install Composer
            curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer > /dev/null 2>&1

            # Clean vendor and composer.lock for fresh installation
            rm -rf vendor composer.lock

            # Install dependencies
            echo 'Installing Laravel ${LARAVEL_VERSION}...'
            composer require \"illuminate/support:${LARAVEL_VERSION}\" --no-interaction --no-update || exit 1

            # Set testbench version based on Laravel version
            if [ \"${LARAVEL_VERSION}\" == \"10.*\" ]; then
                TESTBENCH_VERSION=\"^8.0\"
            elif [ \"${LARAVEL_VERSION}\" == \"11.*\" ]; then
                TESTBENCH_VERSION=\"^9.0\"
            elif [ \"${LARAVEL_VERSION}\" == \"12.*\" ]; then
                TESTBENCH_VERSION=\"^10.0\"
            fi

            composer require \"orchestra/testbench:\$TESTBENCH_VERSION\" --dev --no-interaction --no-update || exit 1
            composer update --prefer-dist --no-interaction --with-all-dependencies --no-audit || exit 1

            # Run tests
            vendor/bin/phpunit || exit 1
        " > "$LOG_FILE" 2>&1; then

        echo -e "${GREEN}✓ PHP $PHP_VERSION - Laravel $LARAVEL_VERSION [PASSED]${NC}"
        RESULTS[$KEY]="✓"
    else
        echo -e "${RED}✗ PHP $PHP_VERSION - Laravel $LARAVEL_VERSION [FAILED]${NC}"
        RESULTS[$KEY]="✗"
        echo -e "${RED}  Log: $LOG_FILE${NC}"
    fi

    echo ""
}

# Execute all tests
for PHP_VERSION in "${PHP_VERSIONS[@]}"; do
    for LARAVEL_VERSION in "${LARAVEL_VERSIONS[@]}"; do
        run_test "$PHP_VERSION" "$LARAVEL_VERSION"
    done
done

# Generate compatibility file
echo -e "${YELLOW}=====================================${NC}"
echo -e "${YELLOW}Generating Compatibility Matrix${NC}"
echo -e "${YELLOW}=====================================${NC}"

COMPAT_FILE="COMPATIBILITY.md"

cat > "$COMPAT_FILE" << 'EOF'
# Compatibility Matrix

This matrix shows the results of tests executed locally with Docker.

> **Legend:**
> - ✓ = Tests passed
> - ✗ = Tests failed
> - ⊘ = Unsupported combination

EOF

echo "| PHP Version | Laravel 10.* | Laravel 11.* | Laravel 12.* |" >> "$COMPAT_FILE"
echo "|-------------|--------------|--------------|--------------|" >> "$COMPAT_FILE"

for PHP_VERSION in "${PHP_VERSIONS[@]}"; do
    echo -n "| **$PHP_VERSION** " >> "$COMPAT_FILE"
    for LARAVEL_VERSION in "${LARAVEL_VERSIONS[@]}"; do
        KEY="${PHP_VERSION}-${LARAVEL_VERSION}"
        RESULT="${RESULTS[$KEY]}"
        echo -n "| $RESULT " >> "$COMPAT_FILE"
    done
    echo "|" >> "$COMPAT_FILE"
done

cat >> "$COMPAT_FILE" << EOF

---

**Last updated:** $(date '+%Y-%m-%d %H:%M:%S')

**Detailed logs:** Check the \`$LOG_DIR/\` directory for complete logs of each test.

## How to run the tests

\`\`\`bash
# Run all tests
./run-tests-docker.sh

# View logs from a specific test
cat $LOG_DIR/php-8.3-laravel-11.x.log
\`\`\`
EOF

echo -e "${GREEN}✓ Compatibility matrix generated: $COMPAT_FILE${NC}"
echo ""

# Summary
PASSED=0
FAILED=0
EXCLUDED=0

for result in "${RESULTS[@]}"; do
    case $result in
        "✓") ((PASSED++)) ;;
        "✗") ((FAILED++)) ;;
        "⊘") ((EXCLUDED++)) ;;
    esac
done

echo -e "${YELLOW}=====================================${NC}"
echo -e "${YELLOW}Summary${NC}"
echo -e "${YELLOW}=====================================${NC}"
echo -e "${GREEN}Passed: $PASSED${NC}"
echo -e "${RED}Failed: $FAILED${NC}"
echo -e "${YELLOW}Excluded: $EXCLUDED${NC}"
echo ""

if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}🎉 All tests passed!${NC}"
    exit 0
else
    echo -e "${RED}❌ Some tests failed. Check logs in $LOG_DIR/${NC}"
    exit 1
fi
