<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\dashboard\Crm;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\layouts\CollapsedMenu;
use App\Http\Controllers\layouts\ContentNavbar;
use App\Http\Controllers\layouts\ContentNavSidebar;
use App\Http\Controllers\layouts\NavbarFull;
use App\Http\Controllers\layouts\NavbarFullSidebar;
use App\Http\Controllers\layouts\Horizontal;
use App\Http\Controllers\layouts\Vertical;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\front_pages\Pricing;
use App\Http\Controllers\front_pages\Payment;
use App\Http\Controllers\front_pages\Checkout;
use App\Http\Controllers\front_pages\HelpCenter;
use App\Http\Controllers\front_pages\HelpCenterArticle;
use App\Http\Controllers\apps\Email;
use App\Http\Controllers\apps\Chat;
use App\Http\Controllers\apps\Calendar;
use App\Http\Controllers\apps\Kanban;
use App\Http\Controllers\apps\EcommerceDashboard;
use App\Http\Controllers\apps\EcommerceProductList;
use App\Http\Controllers\apps\EcommerceProductAdd;
use App\Http\Controllers\apps\EcommerceProductCategory;
use App\Http\Controllers\apps\EcommerceOrderList;
use App\Http\Controllers\apps\EcommerceOrderDetails;
use App\Http\Controllers\apps\EcommerceCustomerAll;
use App\Http\Controllers\apps\EcommerceCustomerDetailsOverview;
use App\Http\Controllers\apps\EcommerceCustomerDetailsSecurity;
use App\Http\Controllers\apps\EcommerceCustomerDetailsBilling;
use App\Http\Controllers\apps\EcommerceCustomerDetailsNotifications;
use App\Http\Controllers\apps\EcommerceManageReviews;
use App\Http\Controllers\apps\EcommerceReferrals;
use App\Http\Controllers\apps\EcommerceSettingsDetails;
use App\Http\Controllers\apps\EcommerceSettingsPayments;
use App\Http\Controllers\apps\EcommerceSettingsCheckout;
use App\Http\Controllers\apps\EcommerceSettingsShipping;
use App\Http\Controllers\apps\EcommerceSettingsLocations;
use App\Http\Controllers\apps\EcommerceSettingsNotifications;
use App\Http\Controllers\apps\AcademyDashboard;
use App\Http\Controllers\apps\AcademyCourse;
use App\Http\Controllers\apps\AcademyCourseDetails;
use App\Http\Controllers\apps\LogisticsDashboard;
use App\Http\Controllers\apps\LogisticsFleet;
use App\Http\Controllers\apps\InvoiceList;
use App\Http\Controllers\apps\InvoicePreview;
use App\Http\Controllers\apps\InvoicePrint;
use App\Http\Controllers\apps\InvoiceEdit;
use App\Http\Controllers\apps\InvoiceAdd;
use App\Http\Controllers\apps\UserList;
use App\Http\Controllers\apps\UserViewAccount;
use App\Http\Controllers\apps\UserViewSecurity;
use App\Http\Controllers\apps\UserViewBilling;
use App\Http\Controllers\apps\UserViewNotifications;
use App\Http\Controllers\apps\UserViewConnections;
use App\Http\Controllers\apps\AccessRoles;
use App\Http\Controllers\apps\AccessPermission;
use App\Http\Controllers\pages\UserProfile;
use App\Http\Controllers\pages\UserTeams;
use App\Http\Controllers\pages\UserProjects;
use App\Http\Controllers\pages\UserConnections;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsSecurity;
use App\Http\Controllers\pages\AccountSettingsBilling;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\Faq;
use App\Http\Controllers\pages\Pricing as PagesPricing;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\pages\MiscComingSoon;
use App\Http\Controllers\pages\MiscNotAuthorized;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\LoginCover;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\RegisterCover;
use App\Http\Controllers\authentications\RegisterMultiSteps;
use App\Http\Controllers\authentications\VerifyEmailBasic;
use App\Http\Controllers\authentications\VerifyEmailCover;
use App\Http\Controllers\authentications\ResetPasswordBasic;
use App\Http\Controllers\authentications\ResetPasswordCover;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\ForgotPasswordCover;
use App\Http\Controllers\authentications\TwoStepsBasic;
use App\Http\Controllers\authentications\TwoStepsCover;
use App\Http\Controllers\wizard_example\Checkout as WizardCheckout;
use App\Http\Controllers\wizard_example\PropertyListing;
use App\Http\Controllers\wizard_example\CreateDeal;
use App\Http\Controllers\modal\ModalExample;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\cards\CardAdvance;
use App\Http\Controllers\cards\CardStatistics;
use App\Http\Controllers\cards\CardAnalytics;
use App\Http\Controllers\cards\CardGamifications;
use App\Http\Controllers\cards\CardActions;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\Avatar;
use App\Http\Controllers\extended_ui\BlockUI;
use App\Http\Controllers\extended_ui\DragAndDrop;
use App\Http\Controllers\extended_ui\MediaPlayer;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\StarRatings;
use App\Http\Controllers\extended_ui\SweetAlert;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\extended_ui\TimelineBasic;
use App\Http\Controllers\extended_ui\TimelineFullscreen;
use App\Http\Controllers\extended_ui\Tour;
use App\Http\Controllers\extended_ui\Treeview;
use App\Http\Controllers\extended_ui\Misc;
use App\Http\Controllers\icons\Tabler;
use App\Http\Controllers\icons\FontAwesome;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_elements\CustomOptions;
use App\Http\Controllers\form_elements\Editors;
use App\Http\Controllers\form_elements\FileUpload;
use App\Http\Controllers\form_elements\Picker;
use App\Http\Controllers\form_elements\Selects;
use App\Http\Controllers\form_elements\Sliders;
use App\Http\Controllers\form_elements\Switches;
use App\Http\Controllers\form_elements\Extras;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\form_layouts\StickyActions;
use App\Http\Controllers\form_wizard\Numbered as FormWizardNumbered;
use App\Http\Controllers\form_wizard\Icons as FormWizardIcons;
use App\Http\Controllers\form_validation\Validation;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\tables\DatatableBasic;
use App\Http\Controllers\tables\DatatableAdvanced;
use App\Http\Controllers\tables\DatatableExtensions;
use App\Http\Controllers\charts\ApexCharts;
use App\Http\Controllers\charts\ChartJs;
use App\Http\Controllers\maps\Leaflet;
use App\Http\Controllers\Hospital\ManageHospital;
use App\Http\Controllers\Hospital\AddHospital;
use App\Http\Controllers\users\ManageUser;
use App\Http\Controllers\users\AddUser;
use App\Http\Controllers\hospital_reviews\HospitalReviews;
use App\Http\Controllers\business_listing\AddBusinessListing;
use App\Http\Controllers\business_listing\ManageBusinessListing;
use App\Http\Controllers\advertisements\AddAdvertisements;
use App\Http\Controllers\advertisements\Common as AdvertisementsCommon;
use App\Http\Controllers\advertisements\ManageAdvertisements;
use App\Http\Controllers\settings\MainCategories;
use App\Http\Controllers\settings\AddMainSubCategories;
use App\Http\Controllers\settings\ManageMainSubCategories;
use App\Http\Controllers\settings\BusinessCategories;
use App\Http\Controllers\settings\LocationMaster;
use App\Http\Controllers\common\Common;
use App\Http\Controllers\Frontend\AllergicFoodController;
use App\Http\Controllers\Frontend\AllergicMedicineController;
use App\Http\Controllers\Frontend\BusinessListingController;
use App\Http\Controllers\Frontend\DoctorController;
use App\Http\Controllers\Frontend\FamilyHealthHistoryController;
use App\Http\Controllers\Frontend\MedicalRecordController;
use App\Http\Controllers\Frontend\UserAppointmentsController;
use App\Http\Controllers\Frontend\UsersController;
use App\Http\Controllers\Frontend\UserProfileDashboardController;
use App\Http\Controllers\Frontend\UserProfileMedicalRecordController;
use App\Http\Controllers\Frontend\UserSideNavbarController;
use App\Http\Controllers\Frontend\DoctorSideNavbarController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Hospital\EmergencyRequestsController;
use App\Http\Controllers\Hospital\HospitalsController;

//********************* HOSPITAL EASY ******************/
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::get('business-listing', [BusinessListingController::class, 'index'])->name('business_listing');
Route::get('business-listing-catgory/{slug}', [BusinessListingController::class, 'index'])->name('business_listing_cat');
Route::get('single-business-listing/{slug}', [BusinessListingController::class, 'show'])->name('business.show');
Route::get('business-categories', [BusinessCategories::class, 'viewAll'])->name('business-categories-all');

Route::prefix('sadmin')->group(function () {
  //login logout
  Route::get('/auth/login', [LoginCover::class, 'index'])->name('login');
  Route::POST('login', [LoginCover::class, 'login'])->name('sadmin-login');
  Route::get('logout', [LoginCover::class, 'logout'])->name('logout');

  /*locations */
  Route::get('/get-countries', [LocationMaster::class, 'getCountries'])->name('sadmin.getCountries');
  Route::get('/get-states', [LocationMaster::class, 'getStates'])->name('sadmin.getStates');;
  Route::get('/get-cities', [LocationMaster::class, 'getCities'])->name('sadmin.getCities');;
  Route::get('/get-localities', [LocationMaster::class, 'getLocalities'])->name('sadmin.getLocalities');;

  Route::group(['middleware' => ['auth', 'check.sadmin.type']], function () {
    Route::get('/', [Analytics::class, 'index'])->name('sadmin.dashboard');
    // Hospital
    Route::prefix('hospital')->group(function () {
      Route::get('manage-hospital', [ManageHospital::class, 'index'])->name('manage-hospital');
      Route::get('hospitals-table-json', [ManageHospital::class, 'hospitals_table_json'])->name('hospitals.hospitals_table_json');
      Route::get('hospitals/export-csv', [ManageHospital::class, 'hospitalsExportCsv'])->name('hospitals.exportcsv');
      Route::post('hospitals/block-unblock/{id}', [ManageHospital::class, 'hospital_unblock_review'])->name('hospitals.block_unblock');

      Route::get('add-hospital', [AddHospital::class, 'index'])->name('add-hospital');
      Route::get('edit-hospital/{id}', [AddHospital::class, 'edit'])->name('edit_hospital');
      Route::post('store-hospital', [AddHospital::class, 'creatHospital'])->name('store_hospital');
      Route::post('update-hospital/{id}', [AddHospital::class, 'updateHospital'])->name('update_hospital');
      Route::post('upload-case8', [AddHospital::class, 'uploadImageCase8'])->name('upload_imageCase8');
      Route::delete('main-sub-categories/{id}', [AddHospital::class, 'destroy'])->name('hospital_destroy');

      Route::get('reviews', [HospitalReviews::class, 'index'])->name('index');
      Route::delete('review-delete/{id}', [HospitalReviews::class, 'review_destroy'])->name('reviews.destroy');
      Route::get('reviews-table-json', [HospitalReviews::class, 'review_table_json'])->name('reviews_table_json');
      Route::get('reviews/export-csv', [HospitalReviews::class, 'reviewsExportCsv'])->name('reviews.exportcsv');
      Route::post('reviews/block-unblock/{id}', [HospitalReviews::class, 'block_unblock_review'])->name('reviews.block_unblock');


      Route::get('appointments', [ManageHospital::class, 'appointment_list'])->name('appointmentList');
      Route::delete('appointment-delete/{id}', [ManageHospital::class, 'appointment_destroy'])->name('appointments.destroy');
      Route::get('appointments-table-json', [ManageHospital::class, 'appointment_table_json'])->name('appointments_table_json');
      Route::get('appointments/export-csv', [ManageHospital::class, 'exportCsv'])->name('appointments.exportcsv');
    });
    //User
    Route::get('/users/add-user', [AddUser::class, 'index'])->name('add-user');
    Route::get('/users/edit-user/{id}', [AddUser::class, 'edit'])->name('edit-user');
    Route::post('/users/store-user', [AddUser::class, 'store'])->name('user.store');
    Route::post('/users/update-user/{id}', [AddUser::class, 'update'])->name('user.update');
    Route::get('/users/manage-user', [ManageUser::class, 'index'])->name('manage-user');
    Route::get('/users/manage-user-table', [ManageUser::class, 'users_table_json'])->name('users.table_json');
    Route::delete('/users/delete/{id}', [ManageUser::class, 'user_destroy'])->name('users.destroy');
    Route::post('/users/block-unblock/{id}', [ManageUser::class, 'user_status_update'])->name('users.block_unblock');
    Route::get('appointments/export-csv', [ManageUser::class, 'export_users_csv'])->name('users.exportcsv');

    // Main Page Route
    //Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');
    Route::get('/dashboard/analytics', [Analytics::class, 'index'])->name('dashboard-analytics');
    Route::get('/dashboard/crm', [Crm::class, 'index'])->name('dashboard-crm');


    // Advertisements
    Route::prefix('advertisements')->group(function () {
      Route::get('manage-advertisements', [ManageAdvertisements::class, 'index'])->name('manage-advertisements');
      Route::get('manage-advertisements/table-json', [ManageAdvertisements::class, 'table_json'])->name('manage-advertisements.tablejson');
      Route::get('add-advertisements', [AddAdvertisements::class, 'index'])->name('add-advertisements');
      Route::get('edit-advertisements/{id}', [ManageAdvertisements::class, 'edit'])->name('edit-advertisements');
      Route::get('manage-advertisements/export-csv', [ManageAdvertisements::class, 'exportCsv'])->name('manage-advertisements.export-csv');
      Route::delete('advertisements/{id}', [ManageAdvertisements::class, 'destroy'])->name('advertisements.destroy');
      Route::post('advertisements/{id}/block', [ManageAdvertisements::class, 'block'])->name('advertisements.block');
      Route::post('add-advertisements/store', [AddAdvertisements::class, 'store'])->name('advertisements.store');
      Route::put('advertisements/update/{id}', [AddAdvertisements::class, 'update'])->name('advertisements.update');
    });

    // Business Listing
    Route::prefix('business-listing')->group(function () {
      Route::get('/export-csv', [ManageBusinessListing::class, 'exportCsv'])->name('business.export-csv');
      Route::post('/{id}/block', [ManageBusinessListing::class, 'block'])->name('business.block');
      Route::get('/manage-business-listing', [ManageBusinessListing::class, 'index'])->name('manage-business-listing');
      Route::get('/add-business-listing', [AddBusinessListing::class, 'index'])->name('add-business-listing');
    });
    // Settings
    Route::prefix('settings')->group(function () {
      Route::get('add-main-sub-categories', [ManageMainSubCategories::class, 'create'])->name('add-main-sub-categories');
      Route::get('manage-main-sub-categories', [ManageMainSubCategories::class, 'index'])->name('manageMainSubCategories');
      Route::get('main-sub-categories/table-json', [ManageMainSubCategories::class, 'table_json'])->name('manageMainSubCategories.tablejson');
      Route::post('main-sub-categories/store', [ManageMainSubCategories::class, 'store'])->name('subCategory.store');
      Route::get('main-sub-categories/{id}/edit', [ManageMainSubCategories::class, 'edit'])->name('manageMainSubCategories.edit');
      Route::put('main-sub-categories/{id}', [ManageMainSubCategories::class, 'update'])->name('subCategory.update');
      Route::delete('main-sub-categories/{id}', [ManageMainSubCategories::class, 'destroy'])->name('manageMainSubCategories.destroy');
      //Business Categories

      Route::get('business-categories', [BusinessCategories::class, 'index'])->name('business-categories');
      Route::get('business-categories/table-json', [BusinessCategories::class, 'table_json'])->name('business-categories.table-json');
      Route::get('business-categories/{id}/edit', [BusinessCategories::class, 'edit'])->name('business-categories.edit');
      Route::put('business-categories/{id}', [BusinessCategories::class, 'update'])->name('business-categories.update');
      Route::delete('business-categories/{id}', [BusinessCategories::class, 'destroy'])->name('business-categories.destroy');
      Route::post('business-categories/store', [BusinessCategories::class, 'store'])->name('business-categories.store');
      //Location Master
      Route::get('locality-city-state-country-master', [LocationMaster::class, 'index'])->name('locality-city-state-country-master');
      Route::get('locality-city-state-country-master', [LocationMaster::class, 'index'])->name('locality-city-state-country-master');
      Route::get('location-master/table-json', [LocationMaster::class, 'table_json'])->name('locationMaster.tablejson');
      Route::post('location-master', [LocationMaster::class, 'store'])->name('locationMaster.store');
      Route::get('location-master/{id}/edit', [LocationMaster::class, 'edit'])->name('locationMaster.edit');
      Route::put('location-master/{id}', [LocationMaster::class, 'update'])->name('locationMaster.update');
      Route::delete('location-master/{id}', [LocationMaster::class, 'destroy'])->name('locationMaster.destroy');

      //Main Category
      Route::get('main-categories', [MainCategories::class, 'index'])->name('main-categories');
      Route::get('main-categories/table-json', [MainCategories::class, 'table_json'])->name('mainCategory.tablejson');
      Route::post('main-categories', [MainCategories::class, 'store'])->name('mainCategory.store');
      Route::get('main-categories/{id}/edit', [MainCategories::class, 'edit'])->name('mainCategory.edit');
      Route::put('main-categories/{id}', [MainCategories::class, 'update'])->name('mainCategory.update');
      Route::delete('main-categories/{id}', [MainCategories::class, 'destroy'])->name('mainCategory.destroy');


      // Add attachments of Listing
      Route::post('upload-photos', [AddBusinessListing::class, 'uploadPhotos'])->name('upload.photos');
      Route::post('delete-file', [AddBusinessListing::class, 'deleteUploadedImage'])->name('delete.file');

      Route::post('business-listings', [AddBusinessListing::class, 'store'])->name('business-listings.store');
      Route::get('business-listings/{id}/edit', [ManageBusinessListing::class, 'edit'])->name('business.edit');
      Route::put('business-listings/{id}', [AddBusinessListing::class, 'update'])->name('business.update');
      Route::delete('business-listing/{id}', [ManageBusinessListing::class, 'destroy'])->name('business.destroy');
      Route::get('manage-bussiness-listing/table-json', [ManageBusinessListing::class, 'table_json'])->name('manageBusinessListing.tablejson');
    });
  });
});

//USERS PATH
Route::prefix('user')->group(function () {
  Route::get('/', [UsersController::class, 'userLogin'])->name('users.login.home');
  Route::get('login', [UsersController::class, 'userLogin'])->name('users.login');
  Route::post('login', [UsersController::class, 'user_login'])->name('users.login.submit');
  Route::get('register', [UsersController::class, 'userRegister'])->name('users.register');
  Route::post('register', [UsersController::class, 'user_register'])->name('users.register.submit');
  Route::get('logout', [UsersController::class, 'user_logout'])->name('users.logout');
  Route::group(['middleware' => ['auth', 'check.user.type']], function () {
    Route::get('/', [UserSideNavbarController::class, 'dashboard'])->name('user.home');
    Route::get('dashboard', [UserSideNavbarController::class, 'dashboard'])->name('user.dashboard');
    Route::get('user-profile', [UserSideNavbarController::class, 'profile'])->name('user.profile');
    Route::post('user-profile-update', [UserSideNavbarController::class, 'profile_update'])->name('user.profileUpdate');
    Route::put('user-profile-update-all', [UserSideNavbarController::class, 'profile_update_all'])->name('user.profileUpdateAll');
    Route::get('user-personnel-info', [UserSideNavbarController::class, 'personnel_info'])->name('user.personnelInfo');
    Route::get('user-edit-personnel-info', [UserSideNavbarController::class, 'edit_personnel_info'])->name('user.editPersonnelInfo');
    Route::get('user-fav', [UserSideNavbarController::class, 'user_profile_fav'])->name('user.profileFav');
    Route::post('change-password', [UsersController::class, 'userChangePassword'])->name('user.changePassword');

    Route::get('user-allergic-medicine', [AllergicMedicineController::class, 'allergic_medicine'])->name('user.allergicMedicine');
    Route::get('ajax-allergic-medicine', [AllergicMedicineController::class, 'getMedicines'])->name('user.getMedicines');
    Route::get('edit-allergic-medicine/{id}', [AllergicMedicineController::class, 'editGetMedicines'])->name('user.editGetMedicines');
    Route::post('add-allergic-medicine', [AllergicMedicineController::class, 'store'])->name('user.allergicMedicineStore');
    Route::put('update-allergic-medicine/{id}', [AllergicMedicineController::class, 'updateMedicine'])->name('user.allergicMedicineUpdate');
    Route::delete('/medicine/{id}', [AllergicMedicineController::class, 'deleteMedicine'])->name('medicine.delete');

    /* Family Health History */
    Route::get('user-family-health-history', [FamilyHealthHistoryController::class, 'familyHealthHistory'])->name('user.familyHealthHistory');
    Route::get('ajax-family-health-history', [FamilyHealthHistoryController::class, 'getFamilyHealthHistory'])->name('user.getFamilyHealthHistory');
    Route::get('edit-family-health-history/{id}', [FamilyHealthHistoryController::class, 'editGetFamilyHealthHistory'])->name('user.editGetFamilyHealthHistory');
    Route::post('add-family-health-history', [FamilyHealthHistoryController::class, 'store'])->name('user.familyHealthHistoryStore');
    Route::put('update-family-health-history/{id}', [FamilyHealthHistoryController::class, 'updateFamilyHealthHistory'])->name('user.updateFamilyHealthHistory');
    Route::delete('/family-health-history/{id}', [FamilyHealthHistoryController::class, 'deleteFamilyHealthHistory'])->name('user.deleteFamilyHealthHistory');

    Route::get('user-allergic-food', [AllergicFoodController::class, 'allergic_food'])->name('user.allergicFood');
    Route::get('ajax-allergic-food', [AllergicFoodController::class, 'get'])->name('user.allergicFood.get');
    Route::get('edit-allergic-food/{id}', [AllergicFoodController::class, 'edit'])->name('user.allergicFood.edit');
    Route::post('add-allergic-food', [AllergicFoodController::class, 'store'])->name('user.allergicFood.store');
    Route::put('update-allergic-food/{id}', [AllergicFoodController::class, 'update'])->name('user.allergicFood.update');
    Route::delete('/allergi-food-medicine/{id}', [AllergicFoodController::class, 'delete'])->name('user.allergicFood.delete');

    Route::get('user-medical-records', [MedicalRecordController::class, 'index'])->name('user.medicalRecords');
    Route::post('add-medical-records', [MedicalRecordController::class, 'store'])->name('user.medicalRecords.store');
    Route::delete('user-medical-records/{id}', [MedicalRecordController::class, 'destroy'])->name('user.medicalRecords.delete');
    Route::get('user-appointments', [UserAppointmentsController::class, 'index'])->name('user.appointments');
    Route::get('get-appointments/{id}', [UserAppointmentsController::class, 'getUserAppointments'])->name('user.getappointment');
    Route::delete('user-appointments/{id}', [UserAppointmentsController::class, 'destroy'])->name('user.appointments.destroy');
    Route::post('review-send', [HospitalReviews::class, 'sendReview'])->name('review.send');

    Route::post('/emergency-request', [EmergencyRequestsController::class, 'addEmergencyRequest'])->name('addEmergencyRequest');
  });
});

//Hospital PATH
Route::get('hospitals', [HospitalsController::class, 'index'])->name('hospital.all');
Route::get('hospitals/{medical_system}', [HospitalsController::class, 'index'])->name('hospital.list');
Route::get('single-hospital/{hospital_slug}', [HospitalsController::class, 'show'])->name('hospital.show');
Route::post('hospitals/book-appointment', [HospitalsController::class, 'hospitalBookAppointment'])->name('hospital.book.appointment');
Route::post('hospitals/set-hospital-favorite', [HospitalsController::class, 'setFavorite'])->name('hospital.setFavorite');
Route::prefix('hospital')->group(function () {
  Route::get('/', [UsersController::class, 'hospitalLogin'])->name('hospitals.login.home');
  Route::get('signin', [UsersController::class, 'hospitalLogin'])->name('hospital.login');
  Route::post('login', [UsersController::class, 'hospital_login'])->name('hospitals.login.submit');
  Route::get('signup', [UsersController::class, 'hospitalRegister'])->name('hospitals.signup');
  Route::post('signup', [UsersController::class, 'hospital_signup'])->name('hospitals.signup.submit');
  Route::get('verify-otp', [UsersController::class, 'hospitalverifyOTP'])->name('hospitals.verifyOTP');
  Route::post('/verify-otp', [UsersController::class, 'verifyOtp'])->name('hospitals.verifyOtp');
  Route::post('/resend-otp', [UsersController::class, 'resendOtp'])->name('hospitals.resendOtp');
  Route::get('logout', [UsersController::class, 'hospital_logout'])->name('hospitals.logout');
  Route::group(['middleware' => ['auth', 'check.hospital.type']], function () {
    Route::get('profile', [HospitalsController::class, 'hospitalProfile'])->name('hospital.profile');
    Route::post('profile-update', [HospitalsController::class, 'hospitalProfileUpdate'])->name('hospital.profile.update');
    Route::get('appointment', [HospitalsController::class, 'hospitalAppointment'])->name('hospital.appointment');
    Route::get('appointment-setting', [HospitalsController::class, 'hospitalAppointmentSetting'])->name('hospital.appointment.setting');
    Route::post('appointment-setting-update', [HospitalsController::class, 'hospitalAppointmentSettingUpdate'])->name('hospital.appointment.setting.update');
    Route::get('get-appointment/{id}', [HospitalsController::class, 'getHospitalAppointment'])->name('hospital.get.appointment');
    Route::get('export-csv', [HospitalsController::class, 'exportCsv'])->name('hospital.export.csv');
    Route::delete('appointments/{id}', [HospitalsController::class, 'destroyAppointments'])->name('hospital.appointments.destroy');


    Route::get('get-reviews/{id}', [HospitalsController::class, 'getHospitalReviews'])->name('hospital.get.reviews');
    Route::get('review', [HospitalsController::class, 'hospitalReview'])->name('hospital.review');
    Route::get('review-export-csv', [HospitalsController::class, 'reviewExportCsv'])->name('hospital.review.exportcsv');
    Route::get('reviews/{id}', [HospitalsController::class, 'viewReview'])->name('reviews.view');
    Route::post('reviews/{id}/reply', [HospitalsController::class, 'replyToReview'])->name('reviews.reply');

    Route::get('setting', [HospitalsController::class, 'hospitalSetting'])->name('hospital.setting');
    Route::post('update-locality', [HospitalsController::class, 'updateLocality'])->name('hospital.update.locality');
    Route::post('change-password', [UsersController::class, 'hospitalChangePassword'])->name('hospital.changePassword');


    Route::get('doctor', [HospitalsController::class, 'hospitalDoctor'])->name('hospital.doctor');
    Route::post('doctor/store', [DoctorController::class, 'store'])->name('hospital.doctor.store');
    Route::post('/upload-image', [HospitalsController::class, 'uploadImageCase8'])->name('hospital.uploadImageCase8');
    Route::post('/delet-image', [HospitalsController::class, 'deleteImageCase8'])->name('hospital.deleteImageCase8');
  });
});


//Doctors PATH
Route::prefix('doctor')->group(function () {
  Route::get('/', [UsersController::class, 'doctorLogin'])->name('doctors.login.home');
  Route::get('login', [UsersController::class, 'doctorLogin'])->name('doctors.login');
  Route::post('login', [UsersController::class, 'doctor_login'])->name('doctors.login.submit');
  Route::get('register', [UsersController::class, 'doctorRegister'])->name('doctors.register');
  Route::post('register', [UsersController::class, 'doctor_register'])->name('doctors.register.submit');

  Route::group(['middleware' => ['auth', 'check.user.type']], function () {
    Route::get('/', [DoctorSideNavbarController::class, 'dashboard'])->name('doctor.home');
    Route::get('dashboard', [DoctorSideNavbarController::class, 'dashboard'])->name('doctor.dashboard');

    // Personnel Info
    Route::get('personnel-info', [DoctorSideNavbarController::class, 'personnel_info'])->name('doctor.personnelInfo');
    Route::get('doctor-edit-personnel-info', [DoctorSideNavbarController::class, 'edit_personnel_info'])->name('doctor.editPersonnelInfo');
    
    // Profile
    Route::get('doctor-profile', [DoctorSideNavbarController::class, 'profile'])->name('doctor.profile');
    Route::post('doctor-profile-update', [DoctorSideNavbarController::class, 'profile_update'])->name('doctor.profileUpdate');
    Route::put('doctor-profile-update-all', [DoctorSideNavbarController::class, 'profile_update_all'])->name('doctor.profileUpdateAll');

    // Educational
    Route::get('educational-qualifications', [DoctorSideNavbarController::class, 'educationalQualifications'])->name('doctor.educational-qualifications');
    Route::get('doctor-educational-qualifications', [DoctorSideNavbarController::class, 'edit_educational_qualifications'])->name('doctor.editEducationalQualifications');
    Route::put('doctor-educational-qualifications-update-all', [DoctorSideNavbarController::class, 'educational_qualifications_update_all'])->name('doctor.EducationalQualificationsUpdateAll');

    // Route::get('user-fav', [UserSideNavbarController::class, 'user_profile_fav'])->name('user.profileFav');

    // Change Password
    Route::post('change-password', [UsersController::class, 'doctorChangePassword'])->name('doctor.changePassword');

    // Social Media Crud
    Route::get('social-media', [DoctorController::class, 'social_media'])->name('doctor.social-media');
    Route::post('social-media-store', [DoctorController::class, 'social_media_store'])->name('doctor.social-media-store');
    Route::get('ajax-social-media', [DoctorController::class, 'getSocialMedia'])->name('doctor.getSocialMedia');
    Route::put('update-social-media/{id}', [DoctorController::class, 'updateSocialMedia'])->name('doctor.updateSocialMedia');
    Route::get('edit-social-media/{id}', [DoctorController::class, 'editGetSocialMedia'])->name('doctor.editGetSocialMedia');
    Route::delete('/social-media/{id}', [DoctorController::class, 'deleteSocialMedia'])->name('social-media.delete');


    // Appointments Crud
    Route::get('appointments', [DoctorController::class, 'appointments'])->name('doctor.appointments');
    Route::post('appointment-store', [DoctorController::class, 'appointment_store'])->name('doctor.appointment-store');
    Route::get('ajax-appointment', [DoctorController::class, 'getAppointments'])->name('doctor.getAppointments');
    Route::put('update-appointment/{id}', [DoctorController::class, 'updateAppointment'])->name('doctor.updateAppointment');
    Route::get('edit-appointment/{id}', [DoctorController::class, 'editGetAppointment'])->name('doctor.editGetAppointment');
    Route::delete('/appointment/{id}', [DoctorController::class, 'deleteSocialMedia'])->name('appointment.delete');

    // Logout
  Route::get('logout', [DoctorController::class, 'doctor_logout'])->name('doctors.logout');

    /* Family Health History */
    // Route::get('user-family-health-history', [FamilyHealthHistoryController::class, 'familyHealthHistory'])->name('user.familyHealthHistory');
    // Route::get('ajax-family-health-history', [FamilyHealthHistoryController::class, 'getFamilyHealthHistory'])->name('user.getFamilyHealthHistory');
    // Route::get('edit-family-health-history/{id}', [FamilyHealthHistoryController::class, 'editGetFamilyHealthHistory'])->name('user.editGetFamilyHealthHistory');
    // Route::post('add-family-health-history', [FamilyHealthHistoryController::class, 'store'])->name('user.familyHealthHistoryStore');
    // Route::put('update-family-health-history/{id}', [FamilyHealthHistoryController::class, 'updateFamilyHealthHistory'])->name('user.updateFamilyHealthHistory');
    // Route::delete('/family-health-history/{id}', [FamilyHealthHistoryController::class, 'deleteFamilyHealthHistory'])->name('user.deleteFamilyHealthHistory');

    // Route::get('user-allergic-food', [AllergicFoodController::class, 'allergic_food'])->name('user.allergicFood');
    // Route::get('ajax-allergic-food', [AllergicFoodController::class, 'get'])->name('user.allergicFood.get');
    // Route::get('edit-allergic-food/{id}', [AllergicFoodController::class, 'edit'])->name('user.allergicFood.edit');
    // Route::post('add-allergic-food', [AllergicFoodController::class, 'store'])->name('user.allergicFood.store');
    // Route::put('update-allergic-food/{id}', [AllergicFoodController::class, 'update'])->name('user.allergicFood.update');
    // Route::delete('/allergi-food-medicine/{id}', [AllergicFoodController::class, 'delete'])->name('user.allergicFood.delete');

    // Route::get('user-medical-records', [MedicalRecordController::class, 'index'])->name('user.medicalRecords');
    // Route::post('add-medical-records', [MedicalRecordController::class, 'store'])->name('user.medicalRecords.store');
    // Route::delete('user-medical-records/{id}', [MedicalRecordController::class, 'destroy'])->name('user.medicalRecords.delete');
    // Route::get('user-appointments', [UserAppointmentsController::class, 'index'])->name('user.appointments');
    // Route::get('get-appointments/{id}', [UserAppointmentsController::class, 'getUserAppointments'])->name('user.getappointment');
    // Route::delete('user-appointments/{id}', [UserAppointmentsController::class, 'destroy'])->name('user.appointments.destroy');
    // Route::post('review-send', [HospitalReviews::class, 'sendReview'])->name('review.send');

    // Route::post('/emergency-request', [EmergencyRequestsController::class, 'addEmergencyRequest'])->name('addEmergencyRequest');
  });
});