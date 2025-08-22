<?php

use App\Http\Controllers\AdvertisementsController;
use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\API\BankController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\Discount_couponsController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\Currencies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Route::get('/', function () {
//     return view('home');
// });

// Route::get('/admin/login', App\Filament\Pages\CustomLogin::class);
Route::resource('users', UserController::class);

Route::post('/github/deploy', function (Request $request) {
    $secret = '14171417Nn';

    $signature = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);
    if (!hash_equals($signature, $request->header('X-Hub-Signature-256'))) {
        Log::warning('Signature mismatch!');
        abort(403, 'Unauthorized');
    }

    exec('cd /home/wajhah/htdocs/Wajhah-Project && git pull origin main');
    return response()->json(['status' => 'Deployed']);
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('homepage', [HomeController::class, 'homepage']);
Route::get('region', [RegionController::class, 'index']);
Route::get('bank', [BanksController::class, 'index']);
Route::get('advertisement', [AdvertisementsController::class, 'index']);
Route::get('currency', [CurrenciesController::class, 'index']);
Route::get('establishment_features_icons', [CurrenciesController::class, 'establishment_features_icons']);

Route::get('establishments', [EstablishmentController::class, 'index']);
Route::get('establishment/{id}', [EstablishmentController::class, 'show'])->name('establishment.show');

Route::get('booking', [BookingController::class, 'index']);


Route::get('packages', [BanksController::class, 'show_package']);
Route::get('price_packages', [BanksController::class, 'show_packages']);

Route::get('/discount_coupons', [Discount_couponsController::class, 'index'])->name('discount-coupons.index');