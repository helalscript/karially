<?php



use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\V1\CMS\HomePageController;
use App\Http\Controllers\API\V1\User\BookingController;
use App\Http\Controllers\API\V1\User\CareerGoalController;
use App\Http\Controllers\API\V1\User\CoachController;
use App\Http\Controllers\API\V1\User\NewsletterController;
use App\Http\Controllers\API\V1\User\SubcriptionController;
use App\Http\Controllers\API\V1\User\UserContactSupportController;
use App\Http\Controllers\API\V1\User\UserFaqController;
use App\Http\Controllers\API\V1\User\WeekController;
use App\Http\Controllers\API\V1\User\WeeklyTaskController;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest:api'], function ($router) {
    //register
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
    Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
    //login
    Route::post('login', [LoginController::class, 'login']);
    //forgot password
    Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/verify-otp', [ResetPasswordController::class, 'VerifyOTP']);
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);

});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/update-password', [UserController::class, 'changePassword']);
    Route::delete('/delete-profile', [UserController::class, 'deleteProfile']);

});


// only for user
Route::group(['middleware' => ['auth:api', 'check_is_user']], function ($router) {
    //route for coaches
    Route::apiResource('/coaches', CoachController::class)->only(['index', 'show']);
    //route for bookings
    Route::apiResource('/my-bookings', BookingController::class);
    Route::post('/my-bookings/cancel/{id}', [BookingController::class, 'cancelBooking'])->name('my_bookings.cancel');
    //route for subscription
    Route::get('/packages', [SubcriptionController::class, 'getPackages']);
    Route::get('/packages/single/{id}', [SubcriptionController::class, 'getSinglePackages']);
    Route::get('/my-subscription', [SubcriptionController::class, 'getMySubscription']);
    Route::post('/create-subscription/{packageId}', [SubcriptionController::class, 'createSubscription']);

    //route for career goals and weekly tasks
    Route::apiResource('/career-goals', CareerGoalController::class)->except(['update']);
    Route::apiResource('/weeks', WeekController::class);
    Route::post('/weeks/mark-as-completed/{id}', [WeekController::class, 'markAsCompleted']);
    Route::apiResource('/weekly-tasks', WeeklyTaskController::class)->except(methods: ['index']);
    Route::post('/weekly-tasks/mark-as-complete-single-task/{weeklyTaskId}', [WeeklyTaskController::class, 'markAsCompleteSingleTask']);



});
Route::post('/stripe-webhook', [SubcriptionController::class, 'handleWebhook']);



// only for user and host
Route::group(['middleware' => ['auth:api']], function ($router) {
    Route::post('/contact-support-message/sent', [UserContactSupportController::class, 'store']);
});


// --------- cms part --------------
Route::get('/cms/social-link', [HomePageController::class, 'getSocialLinks']);
Route::get('/cms/system-info', [HomePageController::class, 'getSystemInfo']);
// Route::get('/faqs', [UserFaqController::class, 'index']);

// dynamic page
Route::get("dynamic-pages", [HomePageController::class, "getDynamicPages"]);
Route::get("dynamic-pages/single/{slug}", [HomePageController::class, "showDaynamicPage"]);
//route for newsletter
Route::post('/subscribe-newsletter', [NewsletterController::class, 'store']);
//route for home page 
Route::get('/cms/home-page/hero-section', [HomePageController::class, 'getHeroSection']);
Route::get('/cms/home-page/trust-section', [HomePageController::class, 'getTrustSection']);
Route::get('/cms/home-page/feature-section', [HomePageController::class, 'getFeatureSection']);
Route::get('/cms/home-page/why-choose-us-section', [HomePageController::class, 'getWhyChooseUsSection']);
Route::get('/cms/home-page/price-section', [HomePageController::class, 'getPriceSection']);
Route::get('/cms/home-page/spotlight-section', [HomePageController::class, 'getSpotlightSection']);
Route::get('/cms/home-page/faqs-section', [HomePageController::class, 'getFaqsSection']);
//route for auth page 
Route::get('/cms/home-page/login-section', [HomePageController::class, 'getLoginSection']);