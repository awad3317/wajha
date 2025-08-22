<?php

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BankController;
use App\Http\Controllers\api\FileController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\Auth\OtpController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OwnerAccountController;
use App\Http\Controllers\API\PricePackageController;
use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\API\Auth\UserAuthController;
use App\Http\Controllers\API\EstablishmentController;
use App\Http\Controllers\API\pricePackageIconController;
use App\Http\Controllers\API\EstablishmentRuleController;
use App\Http\Controllers\API\EstablishmentTypeController;
use App\Http\Controllers\API\EstablishmentImageController;
use App\Http\Controllers\API\Auth\ForgetPasswordController;
use App\Http\Controllers\API\EstablishmentFeatureController;
use App\Http\Controllers\API\Dashboard\Owner\Dashboardcontroller;
use App\Http\Controllers\API\EstablishmentFeaturesIconController;
use App\Http\Controllers\API\EstablishmentSpecificationController;
use App\Http\Controllers\API\EstablishmentUnavailabilityController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth.sanctum.api','check.banned'])->group(function () {
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::get('/bank', [BankController::class, 'index']);
    Route::apiResource('/acount', OwnerAccountController::class)->except(['show']);
    Route::apiResource('/establishment', EstablishmentController::class)->except(['index','show']);
    Route::apiResource('/establishmentFeature', EstablishmentFeatureController::class)->except(['index','show']);
    Route::apiResource('/establishmentRule', EstablishmentRuleController::class)->except(['index','show']);
    Route::apiResource('/establishmentSpecification', EstablishmentSpecificationController::class)->except(['index','show']);
    Route::apiResource('/pricePackage', PricePackageController::class)->except(['index','show']);
    // Route::apiResource('/review', ReviewController::class)->except(['index','show']);
    Route::apiResource('/unavailabilityDays', EstablishmentUnavailabilityController::class)->except(['index','show','update']);
    Route::apiResource('/booking', BookingController::class)->except([]);
    Route::post('/booking/markAsWaitingPayment', [BookingController::class, 'markAsWaitingPayment']);
    Route::post('/booking/markAsPaid', [BookingController::class, 'markAsPaid']);
    Route::post('/booking/confirmBooking', [BookingController::class, 'confirmBooking']);
    
    Route::get('/downloadReceipt/{filename}', [FileController::class, 'downloadReceipt']);
    Route::get('/viewReceipt/{filename}', [FileController::class, 'viewReceipt']);
    
    
    Route::apiResource('/pricePackageIcon', pricePackageIconController::class)->only(['index']);
    Route::get('/stats/owner',[Dashboardcontroller::class,'index']);
    Route::get('/showMyStablishment/{id}',[Dashboardcontroller::class,'showMyStablishment']);
    
    Route::get('/notifications',[NotificationController::class,'index']);
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/DeviceToken',[UserController::class,'updateDeviceToken']);
    Route::apiResource('/EstablishmentImage', EstablishmentImageController::class)->except(['index','show']);
    
    Route::apiResource('/featuresIcon', EstablishmentFeaturesIconController::class)->only(['index']);
    Route::apiResource('/Currency', CurrencyController::class)->only(['index']);
    //           Review Route        //
    Route::post('/review',[ReviewController::class,'upsertReview']);

    //          Favorite Route       //
    Route::post('/favorite/toggle',[FavoriteController::class,'toggleFavorite']);
    Route::get('/favorite',[FavoriteController::class,'index']);

    Route::post('/logout',[UserAuthController::class,'logout']);
});

    //           Auth Route          //
Route::post('/register',[UserAuthController::class,'register']);
Route::post('/login',[UserAuthController::class,'login']);
Route::post('/verifyOtpAndLogin',[OTPController::class,'verifyOtpAndLogin']);
Route::post('/resendOTP',[OTPController::class,'resendOTP']);

    //           Forget Password     //
Route::post('/forgetPassword', [ForgetPasswordController::class,'forgetPassword']);
Route::post('/resetPassword', [ForgetPasswordController::class,'resetPassword']);

    //    Establishment Type Route  //
Route::get('/establishmentType', [EstablishmentTypeController::class, 'index']);

    //       Establishment Route with out auth  //
Route::get('/establishment', [EstablishmentController::class, 'index']);
Route::get('/establishment/{id}', [EstablishmentController::class, 'show']);

    //     Region Route       //
Route::get('/regions/parents', [RegionController::class,'getParents']);
Route::get('/regions/{id}/children', [RegionController::class,'getChildren']);
Route::get('/region',[RegionController::class,'index']);

Route::get('/advertisement', [AdvertisementController::class,'index']);

Route::get('/test',function(Request $request){
   $firebaseService = new FirebaseService();
        
        // بيانات الاختبار
        $deviceToken = $request->input('device_token', 'YOUR_TEST_DEVICE_TOKEN');
        $title = "اختبار إشعار";
        $body = "هذا إشعار تجريبي من Laravel !";
        // $imageUrl = "https://example.com/logo.png"; // رابط الشعار الذي تريد إرساله
        
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];
        if(!$deviceToken){
            return response()->json('يجب ارسال deviceToken');
        }

        try {
        $result = $firebaseService->sendNotification($deviceToken, $title, $body, $data);
        
        return response()->json([
            'success' => $result,
            'message' => $result ? 'تم إرسال الإشعار بنجاح!' : 'فشل إرسال الإشعار (بدون تفاصيل خطأ).',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'فشل إرسال الإشعار.',
            'error' => $e->getMessage(), 
        ], 500); 
    }
});
