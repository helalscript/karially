<?php

/**
 * Backend Admin Routes for Web Application
 *
 * This file contains all the routes for managing the admin panel, including routes for
 * dashboard, system Settings, profile Settings, daily tips, blogs, and natural care.
 * Routes are grouped under the 'admin' prefix and require authentication with the 'admin' middleware.
 */


use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\CMS\CMSAboutPageController;
use App\Http\Controllers\Web\Backend\CMS\CMSFeaturePageController;
use App\Http\Controllers\Web\Backend\CMS\CMSOurMissionController;
use App\Http\Controllers\Web\Backend\CMS\CMSPricePageController;
use App\Http\Controllers\Web\Backend\CMS\CMSSuccessStoryController;
use App\Http\Controllers\Web\Backend\CMS\CMSWhyChooseUsController;
use App\Http\Controllers\Web\Backend\CMS\FaqController;
use App\Http\Controllers\Web\Backend\CMS\FeatureController;
use App\Http\Controllers\Web\Backend\CMS\HomePageController;
use App\Http\Controllers\Web\Backend\CMS\HomePageSocialLinkContainerController;
use App\Http\Controllers\Web\Backend\CoachController;
use App\Http\Controllers\Web\Backend\ContactMessageController;
use App\Http\Controllers\Web\Backend\NewsletterController;
use App\Http\Controllers\Web\Backend\NotificationController;
use App\Http\Controllers\Web\Backend\PackageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\ProfileController;
use App\Http\Controllers\Web\Backend\DynamicPageController;
use App\Http\Controllers\Web\Backend\SystemSettingController;


Route::middleware(['auth:web', 'role_check'])->prefix('admin')->group(function () {

  // Routes for managing guests
  Route::get('/', function () {
    return view('backend.layouts.dashboard.index');
  })->name('admin.dashboard');

  Route::get('settings-profile', [ProfileController::class, 'index'])->name('profile_settings.index');
  Route::post('settings-profile', [ProfileController::class, 'update'])->name('profile_settings.update');
  Route::get('settings-profile-password', [ProfileController::class, 'passwordChange'])->name('profile_settings.password_change');
  Route::post('settings-profile-password', [ProfileController::class, 'UpdatePassword'])->name('profile_settings.password');

  // Route for system settings index
  Route::get('system-settings', [SystemSettingController::class, 'index'])->name('system_settings.index');

  // Route for updating system settings
  Route::post('system-settings-update', [SystemSettingController::class, 'update'])->name('system_settings.update');

  // Mail Settings index
  Route::get('system-settings-mail', [SystemSettingController::class, 'mailSettingGet'])->name('system_settings.mail_get');
  // Mail Settings routes
  Route::post('system-settings-mail', [SystemSettingController::class, 'mailSettingUpdate'])->name('system_settings.mail');
  // Social App Settings
  Route::get('setting-configuration-social', [SystemSettingController::class, 'socialConfigGet'])->name('system_settings.configuration.social_get');
  Route::post('setting-configuration-social', [SystemSettingController::class, 'socialAppUpdate'])->name('system_settings.configuration.social');
  // Payments Settings
  Route::get('setting-configuration-payment', [SystemSettingController::class, 'paymentSettingGet'])->name('system_settings.configuration.payment_get');
  Route::post('setting-configuration-payment', [SystemSettingController::class, 'paymentSettingUpdate'])->name('system_settings.configuration.payment');


  // Routes for DynamicPageController
  Route::resource('/dynamic-page', DynamicPageController::class)->names('dynamic_page');
  Route::post('/dynamic-page/status/{id}', [DynamicPageController::class, 'status'])->name('dynamic_page.status');

  // Routes for FaqController
  Route::resource('/faqs', FaqController::class)->names('faqs');
  Route::post('/faqs/status/{id}', [FaqController::class, 'status'])->name('faqs.status');
  Route::post('/faqs/container-update', [FaqController::class, 'faqContainerUpdate'])->name('faqs.container_update');
  // Route Social link
  Route::resource('/home-page/social-link/index', HomePageSocialLinkContainerController::class)->names('cms.home_page.social_link')->except('show');
  Route::post('/home-page/social-link/status/{id}', [HomePageSocialLinkContainerController::class, 'status'])->name('cms.home_page.social_link.status');

  // Route for CategoryController
  Route::resource('/category', CategoryController::class)->names('category')->except(['create']);
  Route::post('/category/status/{id}', [CategoryController::class, 'status'])->name('category.status');

  // Route for PackageController
  Route::resource('/packages', PackageController::class)->names('packages');
  Route::post('/packages/status/{id}', [PackageController::class, 'status'])->name('packages.status');

  // Route for NewsletterController
  Route::resource('/newsletters', NewsletterController::class)->names('newsletters')->only(['index', 'destroy']);

  // Route for CoachController
  Route::resource('/coaches', CoachController::class)->names('coaches');
  Route::post('/coaches/status/{id}', [CoachController::class, 'status'])->name('coaches.status');

  //cms all part home page 
  Route::get('/cms/home-page/hero-section', [HomePageController::class, 'getHeroSection'])->name('cms.home_page.hero_section.create');
  Route::post('/cms/home-page/hero-section', [HomePageController::class, 'updateHerosection'])->name('cms.home_page.hero_section.update');
  Route::get('/cms/home-page/trust-section', [HomePageController::class, 'getTrustSection'])->name('cms.home_page.trust_section.create');
  Route::post('/cms/home-page/trust-section', [HomePageController::class, 'updateTrustSection'])->name('cms.home_page.trust_section.update');
  Route::get('/cms/home-page/spotlight-section', [HomePageController::class, 'getSpotlight'])->name('cms.home_page.spotlight_section.create');
  Route::post('/cms/home-page/spotlight-section', [HomePageController::class, 'updateSpotlight'])->name('cms.home_page.spotlight_section.update');
  Route::get('/cms/home-page/price-section', [HomePageController::class, 'getPriceSection'])->name('cms.home_page.price_section.create');
  Route::post('/cms/home-page/price-section', [HomePageController::class, 'updatePriceSection'])->name('cms.home_page.price_section.update');
  //for auth page 
  Route::get('/cms/home-page/login-section', [HomePageController::class, 'getLoginSection'])->name('cms.auth_page.login_section.create');
  Route::post('/cms/home-page/login-section', [HomePageController::class, 'updateLoginSection'])->name('cms.auth_page.login_section.update');
  // Route for FeatureController
  Route::resource('/cms/home-page/feature-section', FeatureController::class)->names('cms.home_page.feature')->except(['show']);
  Route::post('/cms/home-page/feature-section/status/{id}', [FeatureController::class, 'status'])->name('cms.home_page.feature.status');
  Route::post('/cms/home-page/feature-container-update', [FeatureController::class, 'featureContainerUpdate'])->name('cms.home_page.feature.container_update');
  // Route for CMSWhyChooseUsController
  Route::resource('/cms/home-page/why-choose-us', CMSWhyChooseUsController::class)->names('cms.home_page.why_choose_us')->except(['show']);
  Route::post('/cms/home-page/why-choose-us/status/{id}', [CMSWhyChooseUsController::class, 'status'])->name('cms.home_page.why_choose_us.status');
  Route::post('/cms/home-page/why-choose-us-container-update', [CMSWhyChooseUsController::class, 'whyChooseUsContainerUpdate'])->name('cms.home_page.why_choose_us.container_update');

  //cms all part about page
  Route::get('/cms/about-page/hero-section', [CMSAboutPageController::class, 'getHeroSection'])->name('cms.about_page.hero_section.create');
  Route::post('/cms/about-page/hero-section', [CMSAboutPageController::class, 'updateHerosection'])->name('cms.about_page.hero_section.update');
  Route::get('/cms/about-page/spotlight-section', [CMSAboutPageController::class, 'getSpotlight'])->name('cms.about_page.spotlight_section.create');
  Route::post('/cms/about-page/spotlight-section', [CMSAboutPageController::class, 'updateSpotlight'])->name('cms.about_page.spotlight_section.update');

  // Route for CMSOurMissionController
  Route::resource('/cms/about-page/our-mission', CMSOurMissionController::class)->names('cms.about_page.our_mission')->except(['show']);
  Route::post('/cms/about-page/our-mission/status/{id}', [CMSOurMissionController::class, 'status'])->name('cms.about_page.our_mission.status');
  Route::post('/cms/about-page/our-mission-container-update', [CMSOurMissionController::class, 'ourMissionContainerUpdate'])->name('cms.about_page.our_mission.container_update');
  // Route for CMSSuccessStoryController
  Route::resource('/cms/about-page/success-story', CMSSuccessStoryController::class)->names('cms.about_page.success_story')->except(['show']);
  Route::post('/cms/about-page/success-story/status/{id}', [CMSSuccessStoryController::class, 'status'])->name('cms.about_page.success_story.status');
  Route::post('/cms/about-page/success-story-container-update', [CMSSuccessStoryController::class, 'successStoryContainerUpdate'])->name('cms.about_page.success_story.container_update');

  //cms all part feature page
  Route::get('/cms/feature-page/hero-section', [CMSFeaturePageController::class, 'getHeroSection'])->name('cms.feature_page.hero_section.create');
  Route::post('/cms/feature-page/hero-section', [CMSFeaturePageController::class, 'updateHerosection'])->name('cms.feature_page.hero_section.update');
  Route::get('/cms/feature-page/feature-section', [CMSFeaturePageController::class, 'getFeatureSection'])->name('cms.feature_page.feature_section.create');
  Route::post('/cms/feature-page/feature-section', [CMSFeaturePageController::class, 'updateFeaturesection'])->name('cms.feature_page.feature_section.update');

  //cms all part price page
  Route::get('/cms/price-page/hero-section', [CMSPricePageController::class, 'getHeroSection'])->name('cms.price_page.hero_section.create');
  Route::post('/cms/price-page/hero-section', [CMSPricePageController::class, 'updateHerosection'])->name('cms.price_page.hero_section.update');




  // Routes for NotificationController
  Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notification.read');
  Route::post('/notifications/mark-as-read/single/{id}', [NotificationController::class, 'markAsSingleRead'])->name('notification.read_single');
  Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notification.delete');
  Route::delete('/notifications', [NotificationController::class, 'deleteAll'])->name('notification.deleteall');

  Route::get('/contact-us-message', [ContactMessageController::class, 'index'])->name('admin_contact_us.index');
  Route::get('/contact-us-message/{id}', [ContactMessageController::class, 'show'])->name('admin_contact_us.show');
  Route::delete('/contact-us-message/{id}', [ContactMessageController::class, 'destroy'])->name('admin_contact_us.destroy');
});


// Public route for dynamic pages accessible to all users
Route::get('/pages/{slug}', [DynamicPageController::class, 'showDaynamicPage'])->name('pages');
