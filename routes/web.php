<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    DashboardController,
    TextileWasteController,
    WasteExchangeController,
    ProductController,
    MessagingController,
    ShopController
};

Route::get('/', fn () => view('welcome'));

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('home',[ShopController::class,'home'])->name('home');
Route::get('shop',[ShopController::class,'index'])->name('shop.index');
Route::get('shop/search',[ShopController::class,'search'])->name('shop.search');



Route::middleware('auth')->group(function () {
    // Profile routes
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    // Textile waste routes
    Route::prefix('textile-waste')->name('textile-waste.')->controller(TextileWasteController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{textileWaste}/show', 'show')->name('show');
        Route::get('/{textileWaste}/edit', 'edit')->name('edit');
        Route::patch('/{textileWaste}', 'update')->name('update');
        Route::delete('/{textileWaste}', 'destroy')->name('destroy');
    });

    // Marketplace route
    Route::get('/marketplace', [TextileWasteController::class, 'marketplace'])->name('marketplace.index');

    // Waste Exchange routes
    Route::prefix('waste-exchanges')->name('waste-exchanges.')->controller(WasteExchangeController::class)->group(function () {
        Route::get('/sent', 'sentRequests')->name('sent');
        Route::get('/received', 'receivedRequests')->name('received');
        Route::get('/history', 'history')->name('history');
        Route::get('/{textileWaste}/create', 'create')->name('create');
        Route::post('/{textileWaste}', 'store')->name('store');
        Route::get('/{wasteExchange}', 'show')->name('show');
        Route::post('/{wasteExchange}/accept', 'accept')->name('accept');
        Route::post('/{wasteExchange}/reject', 'reject')->name('reject');
        Route::post('/{wasteExchange}/complete', 'complete')->name('complete');
        Route::post('/{wasteExchange}/cancel', 'cancel')->name('cancel');
    });

    // Product routes (Artisan)
    Route::resource('products', ProductController::class);
    // products marketplace
    Route::get('products/marketplace',[ProductController::class,"marketplace"])->name("products.marketplace");

    // Messaging routes
    Route::prefix('messaging')->name('messaging.')->controller(MessagingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{conversation}', 'show')->name('show');
        Route::post('/messages', 'store')->name('store');
        Route::post('/conversations', 'createConversation')->name('create');
    });
});

require __DIR__.'/auth.php';
