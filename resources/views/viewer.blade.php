<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - IntentDocs</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #fafafa;
            color: #3b4151;
            line-height: 1.6;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            padding: 1.5rem;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: #3b4151;
        }

        .social-icons{
            margin-top: 0.6rem;
            margin-bottom: 0.6rem;
            align-items: baseline;
            display: flex;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #292929;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .version-badge {
            font-size: 0.7rem;
            background: #e8e8e8;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-weight: 500;
        }

        .search-box {
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1px solid #d9d9d9;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: #61affe;
        }

        .nav-section {
            margin-bottom: 1rem;
        }

        .nav-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #8a8a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .nav-items {
            padding: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
            text-decoration: none;
            color: inherit;
        }

        .nav-item:hover {
            background: #e8e8e8;
        }

        /* Main Content */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
        }

        .api-info {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
        }

        .api-info h1 {
            margin-bottom: 0.5rem;
        }

        .api-info p {
            color: #666;
            margin-bottom: 1rem;
        }

        .endpoint-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .endpoint-header {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            border-bottom: 1px solid transparent;
        }

        .endpoint-header:hover {
            background: #f9f9f9;
        }

        .endpoint-card.expanded .endpoint-header {
            border-bottom: 1px solid #e0e0e0;
        }

        /* Method Badges */
        .method {
            padding: 0.3rem 0.6rem;
            border-radius: 3px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            min-width: 60px;
            text-align: center;
        }

        .method-get { background: #61affe; color: #fff; }
        .method-post { background: #49cc90; color: #fff; }
        .method-put { background: #fca130; color: #fff; }
        .method-delete { background: #f93e3e; color: #fff; }
        .method-patch { background: #50e3c2; color: #fff; }

        .method-badge-small {
            padding: 0.15rem 0.4rem;
            border-radius: 3px;
            font-weight: 700;
            font-size: 0.65rem;
            text-transform: uppercase;
        }

        .endpoint-path {
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            flex: 1;
        }

        .endpoint-summary {
            color: #666;
            font-size: 0.9rem;
        }

        .endpoint-body {
            display: none;
            padding: 1.5rem;
        }

        .endpoint-card.expanded .endpoint-body {
            display: block;
        }

        .section-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: #8a8a8a;
            text-transform: uppercase;
            margin: 1.5rem 0 0.75rem 0;
            letter-spacing: 0.5px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        /* Parameters Table */
        .params-table {
            width: 100%;
            border-collapse: collapse;
        }

        .params-table th,
        .params-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e8e8e8;
        }

        .params-table th {
            background: #f7f7f7;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .param-name {
            font-family: monospace;
            font-weight: 600;
        }

        .param-required {
            color: #f93e3e;
            font-size: 0.75rem;
            margin-left: 0.25rem;
        }

        .param-type {
            color: #8a8a8a;
            font-size: 0.85rem;
        }

        .icon-social{
            width: 26px;
            height: 26px;
            color: #292929;
            margin: 1px;
        }

        .icon-social:hover {
            color: #666666ff;
        }

        /* Code Block */
        .code-block {
            background: #292929;
            color: #f1f1f1;
            padding: 1rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            overflow-x: auto;
        }

        .code-block .key { color: #f8c555; }
        .code-block .string { color: #98c379; }
        .code-block .number { color: #d19a66; }
        .code-block .boolean { color: #56b6c2; }
        .code-block .null { color: #c678dd; }

        /* Rules List */
        .rules-list {
            list-style: none;
            padding: 0;
        }

        .rules-list li {
            padding: 0.6rem 1rem;
            background: #f8f9fa;
            margin-bottom: 0.5rem;
            border-radius: 4px;
            border-left: 3px solid #61affe;
        }

        .arrow {
            transition: transform 0.2s;
        }

        .endpoint-card.expanded .arrow {
            transform: rotate(180deg);
        }

        .section-header {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .loading {
            text-align: center;
            padding: 3rem;
            color: #61affe;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #999;
        }

        .generated-info {
            font-size: 0.85rem;
            color: #8a8a8a;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <div class="logo-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.7134 10.1281L17.4668 10.6938C17.2864 11.1079 16.7136 11.1079 16.5331 10.6938L16.2866 10.1281C15.8471 9.11947 15.0555 8.31641 14.0677 7.87708L13.308 7.53922C12.8973 7.35653 12.8973 6.75881 13.308 6.57612L14.0252 6.25714C15.0384 5.80651 15.8442 4.97373 16.2761 3.93083L16.5293 3.31953C16.7058 2.89349 17.2942 2.89349 17.4706 3.31953L17.7238 3.93083C18.1558 4.97373 18.9616 5.80651 19.9748 6.25714L20.6919 6.57612C21.1027 6.75881 21.1027 7.35653 20.6919 7.53922L19.9323 7.87708C18.9445 8.31641 18.1529 9.11947 17.7134 10.1281ZM2.82843 12.0001L7.07107 16.2428L5.65685 17.657L0 12.0001L5.65685 6.34326L7.07107 7.75748L2.82843 12.0001ZM18.3429 17.6572L23.9998 12.0003L21.1714 9.17188L19.7571 10.5861L21.1714 12.0003L16.9287 16.2429L18.3429 17.6572Z"></path></svg></div>
                <span>IntentDoc</span>
                <span class="version-badge" id="sidebar-version">v1.0</span>
            </div>

            <div class="social-icons">
                <a href="https://github.com/intent-doc/laravel" target="_blank">
                    <svg class="icon-social" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.001 2C6.47598 2 2.00098 6.475 2.00098 12C2.00098 16.425 4.86348 20.1625 8.83848 21.4875C9.33848 21.575 9.52598 21.275 9.52598 21.0125C9.52598 20.775 9.51348 19.9875 9.51348 19.15C7.00098 19.6125 6.35098 18.5375 6.15098 17.975C6.03848 17.6875 5.55098 16.8 5.12598 16.5625C4.77598 16.375 4.27598 15.9125 5.11348 15.9C5.90098 15.8875 6.46348 16.625 6.65098 16.925C7.55098 18.4375 8.98848 18.0125 9.56348 17.75C9.65098 17.1 9.91348 16.6625 10.201 16.4125C7.97598 16.1625 5.65098 15.3 5.65098 11.475C5.65098 10.3875 6.03848 9.4875 6.67598 8.7875C6.57598 8.5375 6.22598 7.5125 6.77598 6.1375C6.77598 6.1375 7.61348 5.875 9.52598 7.1625C10.326 6.9375 11.176 6.825 12.026 6.825C12.876 6.825 13.726 6.9375 14.526 7.1625C16.4385 5.8625 17.276 6.1375 17.276 6.1375C17.826 7.5125 17.476 8.5375 17.376 8.7875C18.0135 9.4875 18.401 10.375 18.401 11.475C18.401 15.3125 16.0635 16.1625 13.8385 16.4125C14.201 16.725 14.5135 17.325 14.5135 18.2625C14.5135 19.6 14.501 20.675 14.501 21.0125C14.501 21.275 14.6885 21.5875 15.1885 21.4875C19.259 20.1133 21.9999 16.2963 22.001 12C22.001 6.475 17.526 2 12.001 2Z"></path></svg>
                </a>
                <a href="https://packagist.org/packages/intent-doc/laravel" target="_blank">
                    <svg class="icon-social" stroke="currentColor" fill="currentColor" stroke-width="0" role="img" viewBox="0 0 24 24" height="200px" width="200px" xmlns="http://www.w3.org/2000/svg"><path d="M13.214 23.975c-.098-.053-.329-.062-1.615-.062h-1.26l-.017-.088-.096-.431a3.622 3.622 0 0 1-.07-.354c.005-.007-.012-.056-.038-.107l-.048-.094-.547.085c-.301.046-.598.1-.659.116-.1.03-.11.03-.103.004.038-.14.044-.201.028-.266-.018-.07-.017-.072.238-.645.276-.622.266-.594.237-.634-.018-.025-.042-.02-.307.07-.159.05-.294.093-.301.09a16.82 16.82 0 0 1 .414-.732c.451-.773.566-.976.566-1.003 0-.01-.12-.146-.271-.304-.15-.157-.311-.329-.357-.384a.584.584 0 0 0-.112-.11c-.05-.017-.264-.231-.738-.734a16.196 16.196 0 0 0-.525-.542c-.062-.05-.077-.074-.107-.175a6.511 6.511 0 0 0-.291-.714l-.096-.201v-.178c0-.271-.056-.542-.277-1.331l-.054-.191-.087-.01c-.193-.017-.283-.123-.431-.504a6.231 6.231 0 0 0-.231-.472c-.1-.191-.221-.428-.271-.532l-.086-.185-.08-.01a.72.72 0 0 0-.361.05c-.12.05-.345.063-.618.037l-.399-.038-.199-.02-.107-.191c-.161-.284-.161-.285-.238-.324-.09-.046-.161-.046-.572 0l-.325.035-.245-.035c-.645-.094-.797-.166-.797-.375 0-.042-.045-.261-.102-.489-.13-.528-.218-1.126-.195-1.332.034-.294.273-.996.636-1.87l.142-.341.023-.547c.142-3.274.178-3.76.284-3.951.035-.062.468-.431.644-.552.335-.224 1.503-.8 2.802-1.379l.384-.171.195-.012.193-.01.522.329.521.329.423-.2.658-.308c.235-.108.236-.11.367-.253.184-.201.492-.566.592-.703.118-.161.395-.388.816-.672.147-.098.185-.116.569-.264l.231-.09h.49c.451 0 .502.003.655.037.358.078.652.193.686.267.022.05.07.08.187.12.14.048.311.168 1.224.863.489.373.472.366 1.539.719l.298.1.176.211c.201.241.358.404.404.419.07.022.485-.08 1.009-.249.266-.085.303-.093.351-.077.03.01.175.02.321.02.271.005.514.034 1.117.137.153.027.281.048.283.048.002 0 .244.419.537.933.293.512.61 1.048.705 1.192.167.249.174.264.189.375.026.191.08 1.008.09 1.309l.023.896c.02.843.036 1.04.09 1.154.093.199.276.747.572 1.719l.12.401.008.930-.14.728-.158.813-.016.083-.439.264-.441.265-.321-.016c-.179-.01-.335-.016-.349-.016-.03 0-.066.113-.135.411-.04.176-.042.178-.092.187l-.666.106c-.92.145-1.037.181-1.341.424-.264.211-.264.208-.556 1.681a16.97 16.97 0 0 1-.185.856c-.105.317-.941 1.708-1.246 2.074l-.165.199.064.228c.035.126.073.265.083.309.016.07.314 1.206.421 1.609l.038.14-.201-.084-.328-.136a.549.549 0 0 0-.13-.046c-.003.004.033.15.08.327.08.288.187.763.177.773-.003 0-.15-.008-.331-.022a6.185 6.185 0 0 0-.381-.02l-.055.005-.181.662c-.1.363-.184.664-.187.667-.01.007-.536-.02-1.005-.05-.54-.035-.7-.032-.836.017-.125.045-.241.05-.306.014l.002-.001zm1.957-.348c0-.026.023-.181.05-.345.027-.165.05-.337.05-.383 0-.056.013-.116.04-.175.02-.05.036-.09.033-.093-.002-.002-.368-.03-.813-.062-.637-.046-.86-.067-1.053-.103l-.244-.046-.58.072c-.414.05-.588.078-.607.095-.02.02-.201.042-.712.094-.376.038-.717.075-.756.083l-.072.013.004.083c.003.065.02.115.083.231.06.113.088.191.12.331.02.1.042.184.046.188.003.003.973.014 2.157.023 2.034.016 2.154.018 2.191.045.046.035.064.02.064-.05l-.001-.001zm-4.782-1.179c.873-.123 1.226-.168 1.572-.194.387-.028.897-.087 1.259-.143.057-.01.248-.02.424-.026l.321-.01.377.097.379.098.682.04c.377.023.687.04.69.036a2.93 2.93 0 0 0-.07-.229c-.042-.122-.11-.333-.155-.468l-.077-.244-.231-.072-.231-.073-.156.066-.156.065-1.776.057-1.774.058-.118-.06-.118-.057H9.8l-.269.597c-.148.328-.271.602-.276.609-.008.016-.124.03 1.134-.147zm6.127-1.036c0-.017-.431-1.435-.444-1.457-.007-.012-.05.024-.115.096l-.105.115.154.568.153.568.169.06c.181.064.191.067.191.05zm-6.788-.713l.191-.355-.057-.083c-.032-.046-.063-.08-.068-.073-.007.006-.142.238-.303.515s-.297.511-.302.517c-.004.01.072-.026.171-.075l.176-.09.192-.356zm2.957-.072c.106-.306.193-.562.193-.57a.856.856 0 0 0-.198-.075 3.495 3.495 0 0 1-.201-.066.578.578 0 0 1 .063-.11l.068-.104.405-.02c.706-.033 1.114-.155 1.51-.451.15-.11.214-.128.296-.085.07.04.12.128.106.191-.01.042-.028.056-.241.197-.201.13-.876.428-1.114.492-.136.035-.289.12-.323.181-.04.066-.159.92-.132.947.006.006 1.148-.04 1.33-.056.11-.008.117-.01.161-.067.024-.032.13-.153.238-.267.106-.113.301-.333.431-.488.13-.154.328-.381.437-.502.171-.189.263-.318.619-.857.878-1.324.937-1.441 1.003-1.969.045-.375.067-.447.214-.697.146-.249.294-.965.213-1.033-.072-.06-.153-.032-.427.15-.512.341-1.039.841-1.114 1.053-.034.095-.223.373-.285.418l-.519.369c-.582.414-.52.349-.849.879l-.11.174-.327.173-.328.171-.616.015c-1.23.028-1.288.022-1.943-.207l-.431-.153-.344-.303c-.191-.168-.53-.47-.754-.672l-.407-.368-.142-.321a28.99 28.99 0 0 1-.497-1.15c-.084-.226-.09-.231-.275-.428-.341-.361-.529-.757-.757-1.612-.045-.165-.078-.259-.1-.281-.018-.016-.028-.022-.023-.012.006.01 0 .046-.012.082-.157.441-.209.768-.249 1.559-.02.359-.02.351.096.983.225 1.226.296 1.5.409 1.565.04.024.165.146.291.286.341.376.448.485.604.61.285.231.735.71.901.961.04.06.102.14.135.177.15.163.462.6.899 1.259l.314.475.13-.007c.09-.006.329.012.74.054.336.035.635.064.665.065h.056l.191-.555zm-2.276.455a1.103 1.103 0 0 0-.122-.201c-.075-.107-.14-.195-.15-.191-.01.003-.138.268-.181.374-.01.031.341.044.453.018zm2.766-2.758c.046-.033.127-.055.376-.105.311-.06.375-.083.628-.211a.309.309 0 0 0 .092-.11.74.74 0 0 1 .11-.138c.04-.036.055-.062.055-.098 0-.078.227-.275 1.091-.946a.9.9 0 0 0 .245-.268c.074-.11.158-.211.263-.309.085-.08.291-.284.459-.451.168-.171.391-.376.497-.462a3.44 3.44 0 0 0 .241-.204c.03-.032.157-.106.335-.196.159-.08.295-.156.301-.168.018-.03.09-.06.508-.217.889-.331 1.479-.492 1.79-.492.08 0 .096-.052.067-.219-.068-.395-.296-.552-.808-.552h-.181l-.063-.067c-.06-.065-.067-.087-.124-.325-.115-.485-.185-.532-.742-.516-.435.013-.552.06-1.754.718-.602.331-1.035.702-1.543 1.33a.964.964 0 0 1-.11.123c-.311-.002-.303-.472.017-.949l.106-.157-.084-.035c-.236-.107-.532-.123-.74-.04-.291.116-1.023.525-1.117.622-.098.103-.187.106-.187.007 0-.122.114-.285.289-.411.04-.032.11-.088.153-.127a1.63 1.63 0 0 1 .435-.261c.147-.06.142-.052.09-.15-.14-.255-.525-.546-.915-.689-.05-.02-.169-.07-.263-.112-.221-.102-.331-.124-.672-.136-.244-.01-.283-.014-.267-.033.074-.09.311-.133.79-.144l.384-.01.248.11c.697.301.963.462 1.074.645.076.128.084.133.194.112.266-.05.518.032.765.249.135.12.184.13.274.063a.773.773 0 0 0 .273-.502c.035-.271-.06-1.136-.153-1.385-.07-.188-.057-.196.087-.046.171.178.171.178.326-.11.04-.076.103-.176.138-.221.291-.367.281-1.047-.017-1.347-.15-.15-.404-.291-.815-.446-.403-.155-.47-.211-.77-.628-.361-.506-.425-.567-.724-.708-.617-.293-2.101-.562-2.57-.467-.441.09-1.04.447-1.32.789-.175.213-.63.377-1.274.462-.84.108-1.254.828-1.041 1.806.04.191.038.189.165.034.405-.505 1.209-.976 1.93-1.13.575-.12 1.475-.126 2.01-.01l.096.022h-.144c-.75.01-1.904.257-2.722.584l-.176.07-.016.087c-.015.083-.022.093-.281.351-.536.539-.69.796-.775 1.286-.04.239.036.589.15.678.027.022.047.05.042.06a2.825 2.825 0 0 0-.026.225c-.076.845.323 1.866.96 2.453l.144.133.108-.09c.317-.259.859-.614.888-.582.043.05.034.09-.033.155-.085.082-.196.226-.496.642l-.259.361.007.104c.01.13.076.337.147.451.03.046.09.15.136.229.167.284.321.52.424.642.096.117.184.241.284.401.028.046.05.064.07.06.04-.01.09.026.279.204.186.171.083.122.845.408.823.309 1.131.369 1.465.286.09-.022.097-.022.168.015.09.047.09.047.163-.002v.005zm-6.348-3.82c.003-.003.02-.103.037-.225.017-.12.053-.286.08-.367l.193-.644c.177-.602.159-.55.178-.52.025.042.015-.01-.016-.074a1.69 1.69 0 0 1-.066-.171l-.034-.108.034-.588.034-.589-.055-.397a6.234 6.234 0 0 0-.064-.406c-.032-.032-.464.145-.599.245-.175.13-.401.679-.492 1.194-.12.694-.008 1.647.226 1.916.07.08.226.378.357.68l.034.077.075-.01a.347.347 0 0 0 .078-.013zm14.775-1.31c.126-.088.238-.177.246-.196.015-.037.213-1.099.281-1.507l.042-.257-.042-.447-.042-.446-.125-.48c-.124-.475-.128-.484-.264-.755-.216-.431-.194-.236-.379-3.358l-.035-.578-.053-.055c-.07-.075-.337-.458-.963-1.388a14.255 14.255 0 0 0-.546-.781 16.04 16.04 0 0 0-.821-.146c-.395-.055-.736-.025-1.055.094-.128.048-.14.05-.201.03-.138-.047-.293-.003-.569.164l-.145.087-.166-.221c-.191-.251-.194-.255-.57-.502-.64-.416-.716-.449-1.632-.692-.353-.094-.311-.062-.993-.742l-.535-.532-.585.008c-.989.015-1.482.06-1.6.142-.712.508-1.094.888-1.538 1.531l-.103.15-.386.184c-.422.203-1.034.392-1.201.369-.092-.012-.421-.18-.658-.341-.438-.291-.739-.326-1.088-.124-.284.163-.483.269-1.286.675-.861.437-.965.496-1.246.705l-.164.123-.143.288c-.311.626-.305.602-.321 1.415-.03 1.52-.035 2.008-.016 2.122.03.179.036.374.016.505-.028.171-.387 1.387-.585 1.977-.066.199-.135.52-.159.736-.01.07 0 .175.036.391.026.164.07.451.097.64.066.464.12.562.358.64.209.07.309.063.732-.045.793-.203.813-.197 1.134.378.078.137.112.184.143.193.105.03.725.01.866-.027l.194-.05.056-.015-.02-.096a2.414 2.414 0 0 1-.023-.624c.024-.268.064-.506.153-.889.004-.017-.006-.004-.022.03l-.03.06-.035-.157a1.876 1.876 0 0 0-.291-.672 6.435 6.435 0 0 1-.421-.87l-.093-.231.01-.161c.05-.718.224-1.186.597-1.595.227-.248.293-.351.325-.522.025-.13.106-.807.171-1.395l.057-.547.241-.715c.13-.394.231-.722.225-.728a9.003 9.003 0 0 0-.693-.321c-.124-.035-.412-.023-.632.027-.705.161-1.224.532-1.25.893-.007.08-.362.827-.395.828-.02 0-.074-.098-.093-.169-.02-.074-.012-.115.073-.351.032-.088.097-.307.145-.487.12-.443.14-.477.405-.599l.467-.221c.675-.325 1.657-.578 1.899-.49.143.05.359.226.552.446l.092.104-.09.246c-.458 1.266-.575 1.617-.606 1.823-.071.458-.066 1.465.008 1.695.058.188.054.195-.223.377-.477.316-.953.919-.973 1.233-.013.211.09.634.221.888.052.105.589.913.606.913.006 0 .03-.04.057-.09a1.9 1.9 0 0 1 .704-.773c.316-.204.317-.204.341-.313.161-.725.425-1.144.88-1.385l.197-.105-.095-.035c-.145-.053-.271-.143-.428-.308-.697-.733-.966-1.566-.688-2.127.02-.04.052-.122.072-.184.245-.755.931-1.164 1.842-1.101.896.063 1.294.296 1.618.94.311.624.294 1.191-.055 1.751l-.066.106.06-.058c.068-.063.766-.374.946-.421.268-.07.659-.03 1.894.193.499.09.584.125.823.341.05.048.09.076.09.064s-.01-.104-.025-.206c-.083-.617.034-1.401.269-1.786.284-.466 1.375-.883 1.974-.754.301.065.769.355 1.021.635.032.036.06.056.06.045 0-.01-.02-.325-.04-.699a11.12 11.12 0 0 1-.03-.689c.008-.006.144-.084.306-.174l.293-.161.052.04c.044.032.169.063.78.191l.763.164c.027.006.085.09.216.307.097.164.271.448.388.632.425.68.502.856.411.941-.093.087-.108.086-.169-.007-.03-.045-.201-.314-.379-.595a16.068 16.068 0 0 0-.351-.539c-.02-.02-.998-.346-1.487-.497-.239-.073-.492 1.151-.434 2.099.02.319.02.321.291.57.169.156.858 1.126.993 1.397l.067.136-.006.826-.005.825-.105.177c-.441.742-.694 1.021-.985 1.096-.161.04-.339.175-.324.241.005.022.013.154.02.291.006.167.016.253.027.253.01 0 .073-.01.14-.023.455-.087.958-.057 1.261.073.11.047.116.064.128.344.017.375.042.407.353.457.348.055.468.114.695.344l.128.13.462.002.462.002.245-.168zM9.954 3.808a13.19 13.19 0 0 0-.833-.494c-.338-.171-.351-.201-.117-.309.341-.157.474-.11.908.315.168.165.369.349.448.411.156.118.328.275.328.297 0 .027-.115.14-.14.14a8.7 8.7 0 0 1-.594-.36zm3.987.19a.518.518 0 0 1-.235-.191l-.042-.07.03-.068c.04-.095.076-.13.231-.231.074-.05.159-.11.188-.134.159-.14 1.179-.558 1.358-.558.1 0 .13.02.176.125a.63.63 0 0 0 .065.12c.05.056-.06.115-.341.179-.15.034-.502.176-.818.328l-.258.125-.148.201c-.08.11-.15.201-.15.201L13.94 4l.001-.002zm2.25 8.609c.117-.103.612-.334.898-.418.104-.03.171-.307.171-.72v-.234l-.092-.191c-.097-.201-.203-.378-.221-.371-.006.002-.203.251-.437.556l-.427.552.01.249.01.444c0 .225-.006.218.09.134l-.002-.001zm1.639-1.799l.344-.132.112-.169c.391-.592.512-.901.529-1.379l.01-.308-.228-.361c-.386-.607-.636-1.018-.722-1.187a.36.36 0 0 0-.062-.1c-.062-.04-.245.12-.458.401-.15.201-.226.254-.674.478-.592.295-.876.341-1.292.213a7.003 7.003 0 0 0-.552-.156c-.042 0 .133.206.294.347.126.11.221.161.346.181.191.03.547.231 1.094.612.303.214.261.133.291.55l.027.351.08.078c.1.098.214.251.381.51.07.112.13.205.132.205l.348-.134zm-1.719-3.119c.446-.164.704-.665.527-1.023-.194-.391-.634-.702-.998-.702-.425 0-.979.482-.979.853 0 .008.058-.038.127-.104.329-.311.692-.346 1.05-.105.385.258.469.77.173 1.064-.078.08-.067.08.1.02v-.003zm-7.704-.16a.963.963 0 0 1-.026-.396c.145-.778 1.09-1.005 1.51-.364l.064.1-.009-.129c-.069-.971-1.094-1.234-1.726-.443-.356.447-.299 1.13.105 1.274.103.036.107.034.08-.042h.002zm8.86-.527c.261-.439.226-1.053-.088-1.53-.421-.642-1.084-.841-1.806-.542-.559.231-.776.517-.825 1.081-.022.251-.012.281.058.176.238-.354.644-.547 1.094-.522.712.038 1.24.539 1.385 1.316l.034.183.008.04.04-.05a1.56 1.56 0 0 0 .1-.154v.002zm-1.431.09a.125.125 0 0 0 .04-.096.127.127 0 0 0-.04-.095.128.128 0 0 0-.096-.04c-.04 0-.07.013-.097.04a.13.13 0 0 0-.038.096c0 .123.146.185.233.098l-.002-.003zm-8.218-.209c.138-.773.732-1.289 1.48-1.289.485 0 .838.274 1.043.809.054.14.06.143.087.035.07-.274-.01-.848-.163-1.149-.26-.518-1.032-.779-1.751-.591-.415.106-.9.64-.997 1.097-.1.468-.038.849.191 1.167.077.108.077.108.11-.08v.001zm1.928 0a.128.128 0 0 0 .04-.096c0-.04-.013-.07-.04-.097-.027-.028-.056-.041-.096-.041s-.07.013-.096.04a.128.128 0 0 0-.04.097.136.136 0 0 0 .232.097z"></path></svg>
                </a>
            </div>

            <div class="search-box">
                <input type="text" id="search" placeholder="Search endpoints...">
            </div>

            <div class="nav-section">
                <div class="nav-title">Endpoints</div>
                <div class="nav-items" id="nav-items"></div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main">
            <div class="api-info">
                <h1>API Documentation</h1>
                <p>Intent-driven documentation for your Laravel API</p>
                <div class="generated-info">
                    <span id="endpoint-count">0 endpoints</span> |
                    Generated: <span id="generated-at">-</span>
                </div>
            </div>

            <div id="loading" class="loading">Loading documentation...</div>
            <div id="endpoints-container"></div>
            <div id="no-results" class="no-results" style="display: none;">
                No endpoints found matching your search.
            </div>
        </main>
    </div>

    <script>
        let documentation = null;

        fetch('{{ route("intent-doc.api") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('loading').style.display = 'none';
                documentation = data;
                displayMetaInfo(data);
                displayNavItems(data.endpoints);
                displayEndpoints(data.endpoints);
                setupSearch();
            })
            .catch(error => {
                console.error('Error loading documentation:', error);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('endpoints-container').innerHTML =
                    '<div class="no-results">Error loading documentation.</div>';
            });

        function displayMetaInfo(data) {
            document.getElementById('sidebar-version').textContent = 'v' + (data.version || '1.0');
            document.getElementById('endpoint-count').textContent = `${data.endpoints.length} endpoint${data.endpoints.length !== 1 ? 's' : ''}`;

            if (data.generated_at) {
                const date = new Date(data.generated_at);
                document.getElementById('generated-at').textContent = date.toLocaleString();
            }
        }

        function displayNavItems(endpoints) {
            const container = document.getElementById('nav-items');
            container.innerHTML = '';

            endpoints.forEach((endpoint, index) => {
                const method = (endpoint.method || 'GET').toLowerCase();
                const item = document.createElement('a');
                item.className = 'nav-item';
                item.href = '#endpoint-' + index;
                item.innerHTML = `
                    <span class="method-badge-small method-${method}">${(endpoint.method || 'GET').substring(0, 3).toUpperCase()}</span>
                    ${endpoint.name || endpoint.intent || 'Unnamed'}
                `;
                container.appendChild(item);
            });
        }

        function displayEndpoints(endpoints) {
            const container = document.getElementById('endpoints-container');
            container.innerHTML = '';

            if (endpoints.length === 0) {
                document.getElementById('no-results').style.display = 'block';
                return;
            }

            document.getElementById('no-results').style.display = 'none';

            endpoints.forEach((endpoint, index) => {
                const endpointEl = createEndpointElement(endpoint, index);
                container.appendChild(endpointEl);
            });
        }

        function createEndpointElement(endpoint, index) {
            const div = document.createElement('div');
            div.className = 'endpoint-card';
            div.id = 'endpoint-' + index;

            const method = (endpoint.method || 'GET').toLowerCase();

            let bodyContent = '';

            // Description
            if (endpoint.description) {
                bodyContent += `<p style="margin-bottom: 1rem;">${escapeHtml(endpoint.description)}</p>`;
            }

            // Rules
            if (endpoint.rules && endpoint.rules.length > 0) {
                bodyContent += `
                    <h4 class="section-title">Rules & Constraints</h4>
                    <ul class="rules-list">
                        ${endpoint.rules.map(rule => `<li>${escapeHtml(rule)}</li>`).join('')}
                    </ul>
                `;
            }

            // Request
            if (endpoint.request) {
                bodyContent += `
                    <h4 class="section-title">Request Example</h4>
                    <div class="code-block"><pre>${syntaxHighlight(endpoint.request)}</pre></div>
                `;
            }

            // Response
            if (endpoint.response) {
                bodyContent += `
                    <h4 class="section-title">Response Example</h4>
                    <div class="code-block"><pre>${syntaxHighlight(endpoint.response)}</pre></div>
                `;
            }

            div.innerHTML = `
                <div class="endpoint-header">
                    <span class="method method-${method}">${endpoint.method || 'GET'}</span>
                    <code class="endpoint-path">${escapeHtml(endpoint.endpoint || endpoint.uri || '-')}</code>
                    <span class="endpoint-summary">${escapeHtml(endpoint.name || endpoint.intent || 'Unnamed endpoint')}</span>
                    <span class="arrow">&#9660;</span>
                </div>
                <div class="endpoint-body">
                    ${bodyContent || '<p>No additional information available.</p>'}
                </div>
            `;

            div.querySelector('.endpoint-header').addEventListener('click', () => {
                div.classList.toggle('expanded');
            });

            return div;
        }

        function escapeHtml(text) {
            if (typeof text !== 'string') return text;
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function syntaxHighlight(obj) {
            let json = JSON.stringify(obj, null, 2);
            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                let cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                        match = match.slice(0, -1) + '</span>:';
                        return '<span class="' + cls + '">' + match;
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
        }

        function setupSearch() {
            const searchInput = document.getElementById('search');
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();

                if (!query) {
                    displayNavItems(documentation.endpoints);
                    displayEndpoints(documentation.endpoints);
                    return;
                }

                const filtered = documentation.endpoints.filter(endpoint => {
                    return (
                        (endpoint.name || '').toLowerCase().includes(query) ||
                        (endpoint.intent || '').toLowerCase().includes(query) ||
                        (endpoint.endpoint || '').toLowerCase().includes(query) ||
                        (endpoint.uri || '').toLowerCase().includes(query) ||
                        (endpoint.method || '').toLowerCase().includes(query) ||
                        (endpoint.description || '').toLowerCase().includes(query)
                    );
                });

                displayNavItems(filtered);
                displayEndpoints(filtered);
            });
        }
    </script>
</body>
</html>
