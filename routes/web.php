<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessagingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // textile waste routes
    Route::get('/textile-waste', [\App\Http\Controllers\TextileWasteController::class, 'index'])->name('textile-waste.index');
    Route::get('/textile-waste/create', [\App\Http\Controllers\TextileWasteController::class, 'create'])->name('textile-waste.create');
    Route::post('/textile-waste', [\App\Http\Controllers\TextileWasteController::class, 'store'])->name('textile-waste.store');
    Route::get('/textile-waste/{textileWaste}/show', [\App\Http\Controllers\TextileWasteController::class, 'show'])->name('textile-waste.show');
    Route::get('/textile-waste/{textileWaste}/edit', [\App\Http\Controllers\TextileWasteController::class, 'edit'])->name('textile-waste.edit');
    Route::patch('/textile-waste/{textileWaste}', [\App\Http\Controllers\TextileWasteController::class, 'update'])->name('textile-waste.update');
    Route::delete('/textile-waste/{textileWaste}', [\App\Http\Controllers\TextileWasteController::class, 'destroy'])->name('textile-waste.destroy');

    // Marketplace route
    Route::get('/marketplace', [\App\Http\Controllers\TextileWasteController::class, 'marketplace'])->name('marketplace.index');

    // Waste Exchange routes
    Route::prefix('waste-exchanges')->name('waste-exchanges.')->group(function () {
        // List routes
        Route::get('/sent', [\App\Http\Controllers\WasteExchangeController::class, 'sentRequests'])->name('sent');
        Route::get('/received', [\App\Http\Controllers\WasteExchangeController::class, 'receivedRequests'])->name('received');
        Route::get('/history', [\App\Http\Controllers\WasteExchangeController::class, 'history'])->name('history');

        // Exchange creation routes
        Route::get('/{textileWaste}/create', [\App\Http\Controllers\WasteExchangeController::class, 'create'])->name('create');
        Route::post('/{textileWaste}', [\App\Http\Controllers\WasteExchangeController::class, 'store'])->name('store');

        // Exchange details route
        Route::get('/{wasteExchange}', [\App\Http\Controllers\WasteExchangeController::class, 'show'])->name('show');

        // Exchange action routes
        Route::post('/{wasteExchange}/accept', [\App\Http\Controllers\WasteExchangeController::class, 'accept'])->name('accept');
        Route::post('/{wasteExchange}/reject', [\App\Http\Controllers\WasteExchangeController::class, 'reject'])->name('reject');
        Route::post('/{wasteExchange}/complete', [\App\Http\Controllers\WasteExchangeController::class, 'complete'])->name('complete');
        Route::post('/{wasteExchange}/cancel', [\App\Http\Controllers\WasteExchangeController::class, 'cancel'])->name('cancel');
    });
    
    // Messaging system routes
    Route::prefix('messaging')->name('messaging.')->group(function () {
        // View conversations
        Route::get('/', [MessagingController::class, 'index'])->name('index');
        Route::get('/{conversation}', [MessagingController::class, 'show'])->name('show');
        
        // Create/send messages
        Route::post('/messages', [MessagingController::class, 'store'])->name('store');
        Route::post('/conversations', [MessagingController::class, 'createConversation'])->name('create');
    });
});

require __DIR__.'/auth.php';
