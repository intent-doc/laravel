<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes with IntentDoc
|--------------------------------------------------------------------------
|
| This example demonstrates how to use IntentDoc to document your API
| endpoints with clear intent, business rules, and examples.
|
*/

// Authentication Routes
Route::prefix('auth')->group(function () {
    
    Route::post('/register', [AuthController::class, 'register'])
        ->intent('User registration')
        ->description('Creates a new user account in the system')
        ->rules([
            'Email must be unique',
            'Password must be at least 8 characters',
            'Rate limited to 3 attempts per hour per IP',
        ])
        ->request([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])
        ->response([
            'user' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
            ],
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...',
        ]);

    Route::post('/login', [AuthController::class, 'login'])
        ->intent('User authentication')
        ->description('Authenticates a user and returns an access token')
        ->rules([
            'Rate limited to 5 attempts per minute',
            'Credentials must be valid',
            'Account must not be suspended',
        ])
        ->request([
            'email' => 'user@example.com',
            'password' => 'password123',
        ])
        ->response([
            'token' => 'eyJhbGciOiJIUzI1...',
            'expires_at' => '2024-01-15T12:00:00Z',
        ]);

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->intent('User logout')
        ->description('Invalidates the current user session token')
        ->rules([
            'Authenticated user required',
            'Token must be valid',
        ]);
});

// Protected User Routes
Route::middleware('auth:sanctum')->prefix('users')->group(function () {

    Route::get('/me', [UserController::class, 'me'])
        ->intent('Get current user')
        ->description('Retrieves the authenticated user profile information')
        ->rules([
            'Authenticated user required',
        ])
        ->response([
            'data' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'created_at' => '2024-01-01T00:00:00Z',
            ],
        ]);

    Route::put('/me', [UserController::class, 'update'])
        ->intent('Update current user')
        ->description('Updates the authenticated user profile information')
        ->rules([
            'Authenticated user required',
            'Email must be unique if changed',
            'Password requires current password confirmation',
        ])
        ->request([
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
        ])
        ->response([
            'data' => [
                'id' => 1,
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
            ],
        ]);

    Route::delete('/me', [UserController::class, 'destroy'])
        ->intent('Delete user account')
        ->description('Permanently deletes the authenticated user account')
        ->rules([
            'Authenticated user required',
            'Requires password confirmation',
            'Action is irreversible',
            'All user data will be deleted',
        ]);
});

// Payment Routes
Route::middleware('auth:sanctum')->prefix('payments')->group(function () {

    Route::post('/', [PaymentController::class, 'store'])
        ->intent('Process payment')
        ->description('Processes a customer payment transaction')
        ->rules([
            'Authenticated user required',
            'User must have valid payment method',
            'Amount must be positive',
            'Order must exist and be unpaid',
            'Idempotent operation using idempotency key',
        ])
        ->request([
            'order_id' => 123,
            'payment_method_id' => 5,
            'amount' => 99.99,
            'currency' => 'USD',
            'idempotency_key' => 'unique-key-12345',
        ])
        ->response([
            'data' => [
                'id' => 456,
                'status' => 'completed',
                'amount' => 99.99,
                'currency' => 'USD',
                'processed_at' => '2024-01-15T10:30:00Z',
            ],
        ]);

    Route::get('/{payment}', [PaymentController::class, 'show'])
        ->intent('Get payment details')
        ->description('Retrieves detailed information about a specific payment')
        ->rules([
            'Authenticated user required',
            'User must own the payment',
        ])
        ->response([
            'data' => [
                'id' => 456,
                'order_id' => 123,
                'amount' => 99.99,
                'status' => 'completed',
                'processed_at' => '2024-01-15T10:30:00Z',
            ],
        ]);
});

// Order Routes
Route::middleware('auth:sanctum')->prefix('orders')->group(function () {

    Route::get('/', [OrderController::class, 'index'])
        ->intent('List user orders')
        ->description('Retrieves a paginated list of orders for the authenticated user')
        ->rules([
            'Authenticated user required',
            'Returns only orders owned by the user',
            'Results are paginated (15 per page)',
            'Can be filtered by status',
        ])
        ->request([
            'status' => 'completed', // optional filter
            'page' => 1,
        ])
        ->response([
            'data' => [
                ['id' => 1, 'total' => 99.99, 'status' => 'completed'],
                ['id' => 2, 'total' => 149.99, 'status' => 'pending'],
            ],
            'meta' => [
                'current_page' => 1,
                'last_page' => 3,
                'total' => 45,
            ],
        ]);

    Route::post('/', [OrderController::class, 'store'])
        ->intent('Create new order')
        ->description('Creates a new order for the authenticated user')
        ->rules([
            'Authenticated user required',
            'Cart must not be empty',
            'All items must be in stock',
            'Payment method must be valid',
            'Shipping address must be complete',
        ])
        ->request([
            'items' => [
                ['product_id' => 1, 'quantity' => 2],
                ['product_id' => 5, 'quantity' => 1],
            ],
            'shipping_address_id' => 3,
            'payment_method_id' => 2,
            'notes' => 'Please deliver to the back door',
        ])
        ->response([
            'data' => [
                'id' => 123,
                'total' => 199.99,
                'status' => 'pending',
                'created_at' => '2024-01-15T10:30:00Z',
            ],
        ]);

    Route::get('/{order}', [OrderController::class, 'show'])
        ->intent('Get order details')
        ->description('Retrieves detailed information about a specific order')
        ->rules([
            'Authenticated user required',
            'User must own the order',
        ])
        ->response([
            'data' => [
                'id' => 123,
                'items' => [
                    ['product' => 'Widget A', 'quantity' => 2, 'price' => 49.99],
                    ['product' => 'Gadget B', 'quantity' => 1, 'price' => 99.99],
                ],
                'subtotal' => 199.97,
                'tax' => 15.00,
                'total' => 214.97,
                'status' => 'completed',
            ],
        ]);

    Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])
        ->intent('Cancel order')
        ->description('Cancels an existing order if it has not been shipped')
        ->rules([
            'Authenticated user required',
            'User must own the order',
            'Order must be in pending or processing status',
            'Refund will be issued if payment was processed',
        ])
        ->response([
            'data' => [
                'id' => 123,
                'status' => 'cancelled',
                'cancelled_at' => '2024-01-15T11:00:00Z',
                'refund_status' => 'pending',
            ],
        ]);
});
