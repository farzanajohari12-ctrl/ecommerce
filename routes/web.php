<?php
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\AddProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VariantAttributeController;
use App\Http\Controllers\VariantValueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolePermissionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Custom AuthController routes: manually handling login/register logic in AuthController
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password reset routes: not using Laravel Breeze, Jetstream, or Fortify
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Profile management routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// google/facebook
Route::get('/login/{provider}', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/login/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);

// Authenticated E-Commerce & Management Group
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    
    // Standard User Dashboard
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    // Centralized dynamic dashboard router
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

   // Product Form Page
    Route::get('/add-product', [AddProductController::class, 'create'])->name('add-product.create');

    // Save Product
    Route::post('/add-product', [ProductController::class, 'store'])->name('add-product.store');

    // Product List
    Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

    // Delete Product
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])
    ->name('products.destroy');

    // View Product
    Route::get('/products/{id}/view', [ProductController::class, 'view'])
    ->name('products.view');

    // Update Product
    Route::post('/product/update', [ProductController::class, 'update'])
    ->name('product.update');

    // Toggle Product Status (Active/Inactive)
    Route::get('/admin/product/toggle-status/{id}', [ProductController::class, 'toggleStatus'])
    ->name('product.toggleStatus');

    // Quick-add AJAX routes for the popups ( Protected inside auth middleware for safety )
    Route::post('/categories/store', [ProductController::class, 'storeCategory'])->name('categories.store');
    Route::post('/tags/store', [ProductController::class, 'storeTag'])->name('tags.store');
    Route::post('/badges/store', [ProductController::class, 'storeBadge'])->name('badges.store');
    Route::post('/variant-attributes/store', [ProductController::class, 'storeVariantAttribute'])
    ->name('variant-attributes.store');

    // Admin Restricted Access Layer
    // Route::middleware(['is_admin'])->group(function () {
    //     Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // });

    // Stock Management Route
    Route::get('/stock', [ProductController::class, 'stockList'])
    ->name('product.stockList');

    // Update Stock Route (for AJAX updates from the stock management page)
    Route::post('/product/update-stock', [ProductController::class, 'updateStock'])
    ->name('product.updateStock');

    // Category Management Routes (using resource controller for standard CRUD operations)
    Route::resource('categories', CategoryController::class);

    // Badge Management Routes (using resource controller for standard CRUD operations)
    Route::resource('badges', BadgeController::class);

    // Tag Management Routes (using resource controller for standard CRUD operations)
    Route::resource('tags', TagController::class);

    // Live Search Route for AJAX product search
    Route::get('/global-search', [SearchController::class, 'globalSearch'])
    ->name('global.search');

    // Variant Management Routes (using resource controller for standard CRUD operations)
    Route::resource('variant-attributes', VariantAttributeController::class);
    Route::post('/variant/update', [VariantAttributeController::class, 'updateVariant']);
    Route::post('/variant/store-new', [VariantAttributeController::class, 'storeNewVariant']);

    // User Management Routes
    Route::resource('users', UserController::class);

    // Roles Management Routes
    Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions.index');
    Route::put('/roles-permissions/{id}', [RolePermissionController::class, 'update'])->name('roles.permissions.update');
});
