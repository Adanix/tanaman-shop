<?php

declare(strict_types=1);

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\GardenController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Plantify Official Marketplace & Management
|--------------------------------------------------------------------------
|
| PRD v2.1 compliant routes for storefront, cart, checkout, order tracking,
| digital garden, AI care doctor & chatbot, auth, gifts, and admin back-office.
|
*/

// ==========================================
// 1. PUBLIC STOREFRONT & CATALOG ROUTES
// ==========================================
Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/shop', [CatalogController::class, 'shop'])->name('shop');
Route::get('/product/{slug?}', [CatalogController::class, 'product'])->name('product.show');
Route::get('/about', [CatalogController::class, 'about'])->name('about');

// Web App Proposal PDF (Direct Inline Browser Viewer & Download with Strict Anti-Cache Headers)
$proposalHandler = function () {
    $path = public_path('PROPOSAL_PENGEMBANGAN_PLANTIFY.pdf');
    if (!file_exists($path)) {
        abort(404, 'Proposal document not found.');
    }
    return response(file_get_contents($path), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="Plantify-Official-Web-App-Proposal.pdf"',
        'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, s-maxage=0, private',
        'CDN-Cache-Control' => 'no-store',
        'Cloudflare-CDN-Cache-Control' => 'no-store',
        'Surrogate-Control' => 'no-store',
        'Pragma' => 'no-cache',
        'Expires' => 'Wed, 11 Jan 1984 05:00:00 GMT',
        'ETag' => '"' . md5_file($path) . '"',
    ]);
};

Route::get('/proposal-pdf', $proposalHandler)->name('proposal.pdf');
Route::get('/proposal-pdf.pdf', $proposalHandler);
Route::get('/proposal.pdf', $proposalHandler);
Route::get('/proposal', $proposalHandler);
Route::get('/proposal-en', $proposalHandler);

// ==========================================
// 2. SHOPPING CART ROUTES (Session + Member Sync)
// ==========================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// ==========================================
// 3. CHECKOUT & ORDER COMPLETION ROUTES
// ==========================================
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/success/{token}', [CheckoutController::class, 'success'])->name('order.success');
Route::get('/order/resume-stripe/{token}', [CheckoutController::class, 'resumeStripe'])->name('order.resume-stripe');
Route::post('/order/cancel/{token}', [CheckoutController::class, 'cancelUnpaid'])->name('order.cancel');
Route::post('/order/upload-proof/{token}', [CheckoutController::class, 'uploadTransferProof'])->name('order.upload-proof');

// ==========================================
// 4. ORDER TRACKING & GUEST ADOPTION ROUTES
// ==========================================
Route::get('/track-order', [OrderTrackingController::class, 'showTrackForm'])->name('order.track.search');
Route::post('/track-order', [OrderTrackingController::class, 'search'])->name('order.track.post');
Route::get('/order/{token}', [OrderTrackingController::class, 'show'])->name('order.track');
Route::post('/order/adopt/{token}', [OrderTrackingController::class, 'adoptToGarden'])->name('order.adopt');

// ==========================================
// 5. GIFT UNBOXING & CLAIM ROUTES
// ==========================================
Route::get('/gift/{token}', [GiftController::class, 'show'])->name('gift.show');
Route::post('/gift/{token}/claim', [GiftController::class, 'claim'])->name('gift.claim');

// ==========================================
// 6. CARE & AI ASSISTANT ROUTES
// ==========================================
Route::get('/care', [AiController::class, 'carePage'])->name('care.index');
Route::post('/ai/diagnose', [AiController::class, 'diagnose'])->name('ai.diagnose');
Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');

// ==========================================
// 7. STRIPE WEBHOOK (CSRF Exempt via bootstrap/app.php)
// ==========================================
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])->name('stripe.webhook');

// ==========================================
// 8. CUSTOMER AUTHENTICATION & VERIFICATION
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout handles both POST and GET to prevent MethodNotAllowedHttpException
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Email verification
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    // My Digital Garden (Member only)
    Route::get('/garden', [GardenController::class, 'index'])->name('garden.index');
    Route::post('/garden/{id}/water', [GardenController::class, 'water'])->name('garden.water');
    Route::post('/garden/{id}/fertilize', [GardenController::class, 'fertilize'])->name('garden.fertilize');
    Route::post('/garden/{id}/update', [GardenController::class, 'update'])->name('garden.update');
});

// ==========================================
// 9. ADMIN BACK-OFFICE ROUTES (Protected by 'admin' middleware)
// ==========================================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminController::class, 'orderDetail'])->name('admin.orders.detail');
    Route::post('/orders/{id}/refund', [AdminController::class, 'refund'])->name('admin.refund');

    // Bank Transfer Verifications
    Route::get('/verifications', [AdminController::class, 'verifications'])->name('admin.verifications');
    Route::post('/verifications/{id}/approve', [AdminController::class, 'approveTransfer'])->name('admin.verifications.approve');
    Route::post('/verifications/{id}/reject', [AdminController::class, 'rejectTransfer'])->name('admin.verifications.reject');

    // Store Pickup POS Desk
    Route::get('/pickup', [AdminController::class, 'pickup'])->name('admin.pickup');
    Route::post('/pickup/{id}/ready', [AdminController::class, 'markReadyForPickup'])->name('admin.pickup.ready');
    Route::post('/pickup/{id}/handover', [AdminController::class, 'confirmPickupHandover'])->name('admin.pickup.handover');

    // Shipping & Delivery Dispatch
    Route::get('/delivery', [AdminController::class, 'delivery'])->name('admin.delivery');
    Route::post('/delivery/{id}/ship', [AdminController::class, 'markShipped'])->name('admin.delivery.ship');
    Route::post('/delivery/{id}/deliver', [AdminController::class, 'markDelivered'])->name('admin.delivery.deliver');

    // Plant Catalog & Inventory
    Route::get('/plants', [AdminController::class, 'plants'])->name('admin.plants');
    Route::post('/plants/{id}/deactivate', [AdminController::class, 'deactivatePlant'])->name('admin.plants.deactivate');

    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/settings', [AdminController::class, 'saveSettings'])->name('admin.settings.save');
});

// Private receipt proof signed URL viewer (requires signed link)
Route::get('/admin/proof-view', [AdminController::class, 'viewPrivateProof'])->name('admin.proof.view');
