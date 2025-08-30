<?php


use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\SocialLoginController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\V1\CMS\AboutPageController;
use App\Http\Controllers\API\V1\CMS\HomePageController;
use App\Http\Controllers\API\V1\CoachMatchController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\User\BookingController;
use App\Http\Controllers\API\V1\User\CareerGoalController;
use App\Http\Controllers\API\V1\User\CoachController;
use App\Http\Controllers\API\V1\User\CoverLetterController;
use App\Http\Controllers\API\V1\User\NewsletterController;
use App\Http\Controllers\API\V1\User\PaymentController;
use App\Http\Controllers\API\V1\User\ResumeController;
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
    //social login
    Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);

});

Route::group(['middleware' => ['auth:api', 'check_is_user']], function ($router) {
    Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/update-password', [UserController::class, 'changePassword']);
    Route::delete('/delete-profile', [UserController::class, 'deleteProfile']);

});


// only for user and mustbe subscribed
Route::group(['middleware' => ['auth:api', 'check_is_user']], function ($router) {
// Route::group(['middleware' => ['auth:api', 'check_is_user', 'check_subscription']], function ($router) {
    //route for coaches
    Route::apiResource('/coaches', CoachController::class)->only(['index', 'show']);
    //route for bookings
    Route::apiResource('/my-bookings', BookingController::class);
    Route::post('/my-bookings/cancel/{id}', [BookingController::class, 'cancelBooking'])->name('my_bookings.cancel');


    //route for career goals and weekly tasks
    Route::apiResource('/career-goals', CareerGoalController::class)->except(['update']);
    Route::post('/career-goals/mark-as-completed/{id}', [CareerGoalController::class, 'markAsCompleted']);
    Route::post('/career-goals/week-mark-as-completed/{weekId}', [CareerGoalController::class, 'markAsWeekComplete']);

    //route for weeks
    Route::apiResource('/weeks', WeekController::class);
    Route::post('/weeks/mark-as-completed/{id}', [WeekController::class, 'markAsCompleted']);
    Route::apiResource('/weekly-tasks', WeeklyTaskController::class)->except(methods: ['index']);
    Route::post('/weekly-tasks/mark-as-complete-single-task/{weeklyTaskId}', [WeeklyTaskController::class, 'markAsCompleteSingleTask']);

    //route for cover letter
    Route::apiResource('/cover-letters', CoverLetterController::class);
    Route::post('/cover-letters/generate-summary', [CoverLetterController::class, 'generateCVSummary']);
    Route::post('/cover-letters/generate-text', [CoverLetterController::class, 'generateText']);

    //route for resume
    Route::apiResource('/resumes', ResumeController::class);
    Route::post('/resumes/generate-summary', [ResumeController::class, 'generateResumeSummary']);
    Route::post('/resumes/generate', [ResumeController::class, 'generateResume']);

    //route for coach matches
    Route::apiResource('/coach-matches', CoachMatchController::class)->only(['index', 'show', 'store']);


    //route for community link
    Route::get('/community-link', [HomePageController::class, 'getCommunityLink']);

});

// without subscription see this
Route::group(['middleware' => ['auth:api', 'check_is_user']], function ($router) {
    //route for subscription
    Route::get('/my-subscription', [SubcriptionController::class, 'getMySubscription']);
    Route::post('/create-subscription/{packageId}', [SubcriptionController::class, 'createSubscription']);
    //payment
    Route::apiResource('/payments', PaymentController::class)->only(['index', 'show']);

    //route for dashboard data
    Route::get('/dashboard-data', [DashboardController::class, 'dashboardData']);
});


// only for user 
Route::group(['middleware' => ['auth:api']], function ($router) {
    Route::post('/contact-support-message/sent', [UserContactSupportController::class, 'store']);
});

// webhook
Route::post('/stripe-webhook', [SubcriptionController::class, 'handleWebhook']);
// subscription packages
Route::get('/packages', [SubcriptionController::class, 'getPackages']);
Route::get('/packages/single/{id}', [SubcriptionController::class, 'getSinglePackages']);

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
//route for about page
Route::get('/cms/about-page/hero-section', [AboutPageController::class, 'getHeroSection']);
Route::get('/cms/about-page/our-mission-section', [AboutPageController::class, 'getOurMissionSection']);
Route::get('/cms/about-page/success-story-section', [AboutPageController::class, 'getSuccessStorySection']);
Route::get('/cms/about-page/spotlight-section', [AboutPageController::class, 'getSpotlightSection']);
//route for feature page
Route::get('/cms/feature-page/hero-section', [AboutPageController::class, 'getFeaturePageHeroSection']);
Route::get('/cms/feature-page/feature-section', [AboutPageController::class, 'getFeaturePageFeatureSection']);
//route for price page
Route::get('/cms/price-page/hero-section', [AboutPageController::class, 'getPricePageHeroSection']);

