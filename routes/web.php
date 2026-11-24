<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Website\WebsiteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TutorController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Website\BookingController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\TehsilController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\BoardController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ExamPreparationController;
use App\Http\Controllers\Admin\LibraryDetailController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\LibraryVisitorController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clear-cache', function () {
   
   
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize');
    Artisan::call('optimize:clear');
    try {
        Artisan::call('permission:cache-reset');
    } catch (\Exception $e) {}
  
    return '<h1>Cache cleared</h1>';
});

Route::get('/debug-permissions', function() {
    $user = auth()->user();
    if (!$user) return 'Not logged in';

    $role = \Spatie\Permission\Models\Role::where('name', 'Sub Admin')->first();
    $rolePerms = $role ? $role->permissions->pluck('name') : [];

    return response()->json([
        'user_roles' => $user->getRoleNames(),
        'user_direct_permissions' => $user->getDirectPermissions()->pluck('name'),
        'user_all_permissions' => $user->getAllPermissions()->pluck('name'),
        'sub_admin_role_permissions' => $rolePerms,
        'has_library_details' => $user->hasPermissionTo('libraryDetails.view'),
        'has_any' => $user->hasAnyPermission(['plans.view', 'libraryDetails.view', 'students.view']),
    ]);
});

Route::get('/sitemap.xml', function () {
    $libraries = \App\Models\Library::whereNotNull('slug')->get();
    $tutors = \App\Models\Tutor::get();
    $blogs = \App\Models\Blog::whereNotNull('slug')->get();
    $urls = [];

    // Homepage
    $urls[] = [
        'loc' => url('/'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'daily',
        'priority' => '1.0',
    ];

    // All Libraries Page
    $urls[] = [
        'loc' => url('/all-libraries'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'daily',
        'priority' => '0.9',
    ];

    // All Tutors Page
    $urls[] = [
        'loc' => url('/all-tutors'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'daily',
        'priority' => '0.9',
    ];

    // Static Pages
    $urls[] = [
        'loc' => url('/about'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'monthly',
        'priority' => '0.7',
    ];

    $urls[] = [
        'loc' => url('/privacy-policy'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'monthly',
        'priority' => '0.6',
    ];

    $urls[] = [
        'loc' => url('/faq'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'monthly',
        'priority' => '0.7',
    ];

    $urls[] = [
        'loc' => url('/contact-us'),
        'lastmod' => now()->toAtomString(),
        'changefreq' => 'monthly',
        'priority' => '0.6',
    ];

    // Library Detail Pages
    foreach ($libraries as $library) {
        $urls[] = [
            'loc' => url('/library/' . $library->slug),
            'lastmod' => optional($library->updated_at ?? $library->created_at)->toAtomString() ?: now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];
    }

    // Tutor Detail Pages
    foreach ($tutors as $tutor) {
        $slug = $tutor->slug ?? \Illuminate\Support\Str::slug($tutor->full_name);
        $urls[] = [
            'loc' => url('/tutor/' . $slug),
            'lastmod' => optional($tutor->updated_at ?? $tutor->created_at)->toAtomString() ?: now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];
    }

    // Blog Detail Pages
    foreach ($blogs as $blog) {
        $urls[] = [
            'loc' => url('/blog/' . $blog->slug),
            'lastmod' => optional($blog->updated_at ?? $blog->created_at)->toAtomString() ?: now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];
    }

    // Split into chunks (sitemap protocol allows up to 50k URLs per sitemap)
    $chunkSize = 50000;
    $chunks = array_chunk($urls, $chunkSize);

    if (count($chunks) <= 1) {
        // Single sitemap
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . e($url['lastmod']) . "</lastmod>\n";
            $xml .= "    <changefreq>" . e($url['changefreq']) . "</changefreq>\n";
            $xml .= "    <priority>" . e($url['priority']) . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= "</urlset>";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    // Sitemap index when multiple chunks
    $indexXml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $indexXml .= "<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    for ($i = 0; $i < count($chunks); $i++) {
        $indexXml .= "  <sitemap>\n";
        $indexXml .= "    <loc>" . url('/sitemap-' . ($i + 1) . '.xml') . "</loc>\n";
        $indexXml .= "    <lastmod>" . now()->toAtomString() . "</lastmod>\n";
        $indexXml .= "  </sitemap>\n";
    }

    $indexXml .= "</sitemapindex>";

    return response($indexXml, 200, ['Content-Type' => 'application/xml']);
});

// Child sitemap pages: /sitemap-1.xml, /sitemap-2.xml, ...
Route::get('/sitemap-{index}.xml', function ($index) {
    $libraries = \App\Models\Library::whereNotNull('slug')->get();
    $tutors = \App\Models\Tutor::get();
    $blogs = \App\Models\Blog::whereNotNull('slug')->get();
    $urls = [];

    $urls[] = [ 'loc' => url('/'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '1.0' ];
    $urls[] = [ 'loc' => url('/all-libraries'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '0.9' ];
    $urls[] = [ 'loc' => url('/all-tutors'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '0.9' ];
    $urls[] = [ 'loc' => url('/about'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.7' ];
    $urls[] = [ 'loc' => url('/privacy-policy'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.6' ];
    $urls[] = [ 'loc' => url('/faq'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.7' ];
    $urls[] = [ 'loc' => url('/contact-us'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.6' ];

    foreach ($libraries as $library) {
        $urls[] = [ 'loc' => url('/library/' . $library->slug), 'lastmod' => optional($library->updated_at ?? $library->created_at)->toAtomString() ?: now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.7' ];
    }

    foreach ($tutors as $tutor) {
        $slug = $tutor->slug ?? \Illuminate\Support\Str::slug($tutor->full_name);
        $urls[] = [ 'loc' => url('/tutor/' . $slug), 'lastmod' => optional($tutor->updated_at ?? $tutor->created_at)->toAtomString() ?: now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.7' ];
    }

    foreach ($blogs as $blog) {
        $urls[] = [ 'loc' => url('/blog/' . $blog->slug), 'lastmod' => optional($blog->updated_at ?? $blog->created_at)->toAtomString() ?: now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.7' ];
    }

    $chunkSize = 50000;
    $chunks = array_chunk($urls, $chunkSize);

    $idx = max(0, intval($index) - 1);
    if (!isset($chunks[$idx])) {
        abort(404);
    }

    $selected = $chunks[$idx];

    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";

    foreach ($selected as $url) {
        $xml .= "  <url>\n";
        $xml .= "    <loc>" . e($url['loc']) . "</loc>\n";
        $xml .= "    <lastmod>" . e($url['lastmod']) . "</lastmod>\n";
        $xml .= "    <changefreq>" . e($url['changefreq']) . "</changefreq>\n";
        $xml .= "    <priority>" . e($url['priority']) . "</priority>\n";
        $xml .= "  </url>\n";
    }

    $xml .= "</urlset>";

    return response($xml, 200, ['Content-Type' => 'application/xml']);
})->where('index', '[0-9]+');

Route::get('/', [WebsiteController::class,'website']);
Route::view('/offer', 'offer')->name('offer');

// 📌 301 Permanent SEO Redirects
Route::redirect('/libraries/state/gujrat', '/libraries/state/gujarat', 301);
Route::redirect('/library/brain-hub-reading-library-gujrat-ahmedabad', '/library/brain-hub-reading-library-gujarat-ahmedabad', 301);

Route::get('/library/{slug}', [WebsiteController::class, 'libraryDetails'])->name('library.details');
Route::get('/blog', [WebsiteController::class, 'blogIndex'])->name('blogs.index');
Route::get('/blog/{slug}', [WebsiteController::class, 'blogDetails'])->name('blogs.show');
Route::get('/search', [WebsiteController::class, 'search'])->name('search');
Route::get('/search-ajax', [WebsiteController::class, 'searchAjax']);
Route::post('/booking-store', [BookingController::class, 'store'])->name('booking.store');
Route::post('/commando-classes/submit', [\App\Http\Controllers\Admin\CommandoTrialController::class, 'publicStore'])->name('commando-classes.submit');
Route::post('/track-library-click', [WebsiteController::class, 'trackClick'])->name('library.trackClick');

Route::get('/all-libraries', [WebsiteController::class, 'allLibraries'])->name('libraries.all');
Route::get('/libraries/state/{slug}', [WebsiteController::class, 'stateLibraries'])->name('libraries.state');
Route::get('/libraries/district/{slug}', [WebsiteController::class, 'districtLibraries'])->name('libraries.district');
Route::get('/all-tutors', [WebsiteController::class, 'allTutors'])->name('tutors.all');
// Public authentication routes for tutors and students
Route::get('/auth/register', [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register.post');
Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// Authenticated dashboards for tutors and students
Route::middleware(['auth'])->group(function () {
    Route::get('/tutor/dashboard', function () {
        return view('tutor.dashboard');
    })->name('tutor.dashboard');

    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::view('/privacy-policy', 'static.privacy')->name('privacy.policy');
Route::view('/faq', 'static.faq')->name('faq');
Route::view('/contact-us', 'static.contact')->name('contact.us');
Route::post('/contact-us', [UserController::class, 'Contactstore'])->name('contact.store');
Route::get('/library-template-download', function () {
    $path = public_path('downloads/libraries_alwar_jaipur_template.csv');

    if (! file_exists($path)) {
        abort(404, 'Template file not found.');
    }

    return response()->download($path, 'libraries_alwar_jaipur_template.csv', [
        'Content-Type' => 'text/csv',
    ]);
})->name('libraries.template.download');
Route::view('/3d-car', 'car-demo');
Route::get('/the-movement', [\App\Http\Controllers\Website\WebsiteController::class, 'theMovement'])->name('the-movement');
Route::get('/sports-and-hobby', [\App\Http\Controllers\Website\WebsiteController::class, 'sportsAndHobby'])->name('sports-hobby');
Route::get('/sports-hobby', function() { return redirect()->route('sports-hobby'); });
Route::get('/coaching', function() { return redirect()->route('sports-hobby'); });
Route::get('/coaching/{slug}', [\App\Http\Controllers\Website\WebsiteController::class, 'showCoaching'])->name('coaching.show');
Route::get('/movement-studio', [\App\Http\Controllers\Website\WebsiteController::class, 'theMovement'])->name('movement.studio');
Route::get('/tutor/{identifier}', [WebsiteController::class, 'tutorDetails'])->name('tutor.details');
Route::get('/libraries/load-more', [WebsiteController::class, 'loadMoreLibraries'])->name('libraries.loadMore');
Route::get('/libraries/by-state', [WebsiteController::class, 'getStateLibraries'])->name('libraries.byState');
Route::controller(LoginController::class)->group(function () {
    // Route::get('/', 'create')->name('admin.user.login');
    Route::get('/login', 'create')->name('admin.user.login');
    Route::post('/login', 'signIn')->name('admin.signIn');
    Route::get('/logout', 'signOut')->name('admin.signOut');
    Route::get('/forget-password', 'createForgetPassword')->name('admin.access.forgetPassword');
    Route::post('/forget-password', 'storeForgetPassword')->name('admin.access.forgetPasswordProcess');
    Route::get('/reset-password', 'editForgetPassword')->name('admin.access.changePassword');
    Route::post('/reset-password', 'updateForgetPassword')->name('admin.access.changePasswordProcess');


});


// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/admin', function () {
//     return view('admin');
// });
// Test route removed to avoid duplicate content with /library/{slug}
// Route::get('/library', function () {
//     return view('singlelibrary');
// });

Route::middleware(['admin.login'])->group(function () {
Route::get('/get-districts/{state_id}', function ($state_id) {
    return \App\Models\District::where('state_id', $state_id)->get();
});

Route::get('/get-tehsils/{district_id}', function ($district_id) {
    return \App\Models\Tehsil::where('district_id', $district_id)->get();
});

// Library search for admin autocomplete
Route::get('/get-libraries-search', [LibraryDetailController::class, 'searchLibraries']);

    Route::resource('subadmins', \App\Http\Controllers\Admin\SubAdminController::class);

    // 📌 Call Center Routes
    Route::get('/call-center/dashboard', [\App\Http\Controllers\Admin\CallCenterController::class, 'dashboard'])->name('call-center.dashboard');
    Route::get('/call-center/libraries', [\App\Http\Controllers\Admin\CallCenterController::class, 'index'])->name('call-center.libraries');
    Route::get('/call-center/report', [\App\Http\Controllers\Admin\CallCenterController::class, 'report'])->name('call-center.report');
    Route::post('/call-center/log', [\App\Http\Controllers\Admin\CallCenterController::class, 'storeCallLog'])->name('call-center.log');
    Route::get('/call-center/history/{id}', [\App\Http\Controllers\Admin\CallCenterController::class, 'history'])->name('call-center.history');

    Route::controller(LoginController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        // Route::get('/profile', 'Profile')->name('Profile');
        // Route::patch('/update', 'updateprofile')->name('UpdateProfile');
        // Route::post('/update-password', 'UpdateProfilePassword')->name('UpdateProfilePassword');
       
    
    
    });
// 📌 Library Visitors & Interest Report
Route::get('/library-visitors', [LibraryVisitorController::class, 'index'])->name('library-visitors.index');
Route::get('/library-visitors/live-stats', [LibraryVisitorController::class, 'liveStats'])->name('library-visitors.liveStats');
Route::get('/library-visitors/{libraryId}/details-ajax', [LibraryVisitorController::class, 'libraryDetailsAjax'])->name('library-visitors.detailsAjax');
Route::get('/library-visitors/export', [LibraryVisitorController::class, 'exportCsv'])->name('library-visitors.export');
Route::get('/library-visitors/{libraryId}', [LibraryVisitorController::class, 'show'])->name('library-visitors.show');

Route::get('/bookings', [UserController::class, 'bookingindex'])->name('booking.index');

// 📌 Centralized Coaching Management (Master Coachings CRUD: The Movement, The Achievers, etc.)
Route::resource('coachings', \App\Http\Controllers\Admin\CoachingController::class);
Route::post('/coachings/{id}/toggle-status', [\App\Http\Controllers\Admin\CoachingController::class, 'toggleStatus'])->name('coachings.toggle-status');

// 📌 Commando Classes CRUD (Disciplines Curriculum - Standalone, No Library Dependency)
Route::resource('commando-classes', \App\Http\Controllers\Admin\CommandoClassController::class);
Route::post('/commando-classes/{id}/toggle-status', [\App\Http\Controllers\Admin\CommandoClassController::class, 'toggleStatus'])->name('commando-classes.toggle-status');

// 📌 Commando Master Instructors CRUD
Route::resource('commando-instructors', \App\Http\Controllers\Admin\CommandoInstructorController::class);
Route::post('/commando-instructors/{id}/toggle-status', [\App\Http\Controllers\Admin\CommandoInstructorController::class, 'toggleStatus'])->name('commando-instructors.toggle-status');

// 📌 Commando Weekly Schedules CRUD
Route::resource('commando-schedules', \App\Http\Controllers\Admin\CommandoScheduleController::class);
Route::post('/commando-schedules/{id}/toggle-status', [\App\Http\Controllers\Admin\CommandoScheduleController::class, 'toggleStatus'])->name('commando-schedules.toggle-status');

// 📌 Commando Classes Trial Bookings
Route::get('/commando-trials', [\App\Http\Controllers\Admin\CommandoTrialController::class, 'index'])->name('commando-trials.index');
Route::post('/commando-trials/{id}/status', [\App\Http\Controllers\Admin\CommandoTrialController::class, 'updateStatus'])->name('commando-trials.updateStatus');
Route::delete('/commando-trials/{id}', [\App\Http\Controllers\Admin\CommandoTrialController::class, 'destroy'])->name('commando-trials.destroy');
Route::get('/commando-trials-export', [\App\Http\Controllers\Admin\CommandoTrialController::class, 'exportCsv'])->name('commando-trials.export');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
Route::resource('students', \App\Http\Controllers\Admin\StudentController::class)->middleware('can:students.view');
// 📌 Show All Libraries
Route::get('/libraries', [LibraryController::class, 'index'])->name('libraries.index');

// 📌 Show Add Form
Route::get('/libraries/create', [LibraryController::class, 'create'])->name('libraries.create');

// 📌 Store Data
Route::post('/libraries/store', [LibraryController::class, 'store'])->name('libraries.store');

// 📌 Edit Form
Route::get('/libraries/edit/{id}', [LibraryController::class, 'edit'])->name('libraries.edit');

// 📌 Update Data
Route::put('/libraries/update/{id}', [LibraryController::class, 'update'])->name('libraries.update');

// 📌 Delete
Route::delete('/libraries/delete/{id}', [LibraryController::class, 'destroy'])->name('libraries.destroy');


// 📌 Plans CRUD
Route::middleware(['can:plans.view'])->group(function () {
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/create', [PlanController::class, 'create'])->name('plans.create');
    Route::post('/plans/store', [PlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/edit/{id}', [PlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/update/{id}', [PlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/delete/{id}', [PlanController::class, 'destroy'])->name('plans.destroy');
});


// 📌 Library Details CRUD
Route::middleware(['can:libraryDetails.view'])->group(function () {
    Route::get('/library-details', [LibraryDetailController::class, 'index'])->name('library-details.index');
    Route::get('/library-details/create', [LibraryDetailController::class, 'create'])->name('library-details.create');
    Route::post('/library-details/store', [LibraryDetailController::class, 'store'])->name('library-details.store');
    Route::get('/library-details/edit/{id}', [LibraryDetailController::class, 'edit'])->name('library-details.edit');
    Route::put('/library-details/update/{id}', [LibraryDetailController::class, 'update'])->name('library-details.update');
    Route::delete('/library-details/delete/{id}', [LibraryDetailController::class, 'destroy'])->name('library-details.destroy');
});





    Route::get('subjects', [SubjectController::class, 'index'])->name('Subject.index');
    Route::get('subjects/create', [SubjectController::class, 'create'])->name('Subject.create');
    Route::post('subjects/store', [SubjectController::class, 'store'])->name('Subject.store');
    Route::get('subjects/edit/{id}', [SubjectController::class, 'edit'])->name('Subject.edit');
    Route::post('subjects/update/{id}', [SubjectController::class, 'update'])->name('Subject.update');
    Route::delete('subjects/delete/{id}', [SubjectController::class, 'destroy'])->name('Subject.delete');


Route::get('tutors', [TutorController::class, 'index'])->name('tutors.index');
Route::get('tutors/create', [TutorController::class, 'create'])->name('tutors.create');
Route::post('tutors/store', [TutorController::class, 'store'])->name('tutors.store');
Route::get('tutors/edit/{id}', [TutorController::class, 'edit'])->name('tutors.edit');
Route::put('tutors/update/{id}', [TutorController::class, 'update'])->name('tutors.update');
Route::delete('tutors/delete/{id}', [TutorController::class, 'destroy'])->name('tutors.destroy');



    Route::get('classes', [ClassController::class, 'index'])->name('classes.index');

    // CREATE FORM
    Route::get('classes/create', [ClassController::class, 'create'])->name('classes.create');

    // STORE
    Route::post('classes/store', [ClassController::class, 'store'])->name('classes.store');

    // EDIT FORM
    Route::get('classes/edit/{id}', [ClassController::class, 'edit'])->name('classes.edit');

    // UPDATE
    Route::post('classes/update/{id}', [ClassController::class, 'update'])->name('classes.update');

    // DELETE
    Route::get('classes/delete/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');



  Route::get('/states', [StateController::class, 'index'])
        ->name('states.index');

    // Show create form
    Route::get('/states/create', [StateController::class, 'create'])
        ->name('states.create');

    // Store new state
    Route::post('/states/store', [StateController::class, 'store'])
        ->name('states.store');

    // Show edit form
    Route::get('/states/edit/{state}', [StateController::class, 'edit'])
        ->name('states.edit');

    // Update state
    Route::put('/states/update/{state}', [StateController::class, 'update'])
        ->name('states.update');

    // Delete state
    Route::delete('/states/delete/{state}', [StateController::class, 'destroy'])
        ->name('states.destroy');

Route::get('/districts', [DistrictController::class, 'index'])
    ->name('districts.index');

Route::get('/districts/create', [DistrictController::class, 'create'])
    ->name('districts.create');

Route::post('/districts', [DistrictController::class, 'store'])
    ->name('districts.store');

Route::get('/districts/{district}/edit', [DistrictController::class, 'edit'])
    ->name('districts.edit');

Route::put('/districts/{district}', [DistrictController::class, 'update'])
    ->name('districts.update');

Route::delete('/districts/{district}', [DistrictController::class, 'destroy'])
    ->name('districts.destroy');


Route::get('/tehsils', [TehsilController::class, 'index'])
    ->name('tehsils.index');

Route::get('/tehsils/create', [TehsilController::class, 'create'])
    ->name('tehsils.create');

Route::post('/tehsils', [TehsilController::class, 'store'])
    ->name('tehsils.store');

Route::get('/tehsils/{tehsil}/edit', [TehsilController::class, 'edit'])
    ->name('tehsils.edit');

Route::put('/tehsils/{tehsil}', [TehsilController::class, 'update'])
    ->name('tehsils.update');

Route::delete('/tehsils/{tehsil}', [TehsilController::class, 'destroy'])
    ->name('tehsils.destroy');
      
    
    // routes/web.php



Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create');
Route::post('/boards/store', [BoardController::class, 'store'])->name('boards.store');
Route::get('/boards/edit/{id}', [BoardController::class, 'edit'])->name('boards.edit');
Route::post('/boards/update/{id}', [BoardController::class, 'update'])->name('boards.update');
Route::get('/boards/delete/{id}', [BoardController::class, 'destroy'])->name('boards.delete');

// 📌 Admin Blog CRUD
Route::get('/admin/blogs', [BlogController::class, 'index'])->name('admin.blogs.index');
Route::get('/admin/blogs/create', [BlogController::class, 'create'])->name('admin.blogs.create');
Route::post('/admin/blogs/store', [BlogController::class, 'store'])->name('admin.blogs.store');
Route::get('/admin/blogs/edit/{id}', [BlogController::class, 'edit'])->name('admin.blogs.edit');
Route::put('/admin/blogs/update/{id}', [BlogController::class, 'update'])->name('admin.blogs.update');
Route::delete('/admin/blogs/delete/{id}', [BlogController::class, 'destroy'])->name('admin.blogs.destroy');
    
    
    // routes/web.php



Route::get('/exam-preparations', [ExamPreparationController::class, 'index'])
    ->name('exam_preparations.index');

Route::get('/exam-preparations/create', [ExamPreparationController::class, 'create'])
    ->name('exam_preparations.create');

Route::post('/exam-preparations/store', [ExamPreparationController::class, 'store'])
    ->name('exam_preparations.store');

Route::get('/exam-preparations/edit/{id}', [ExamPreparationController::class, 'edit'])
    ->name('exam_preparations.edit');

Route::post('/exam-preparations/update/{id}', [ExamPreparationController::class, 'update'])
    ->name('exam_preparations.update');

Route::get('/exam-preparations/delete/{id}', [ExamPreparationController::class, 'destroy'])
    ->name('exam_preparations.delete');


// ─────────────────────────────────────────────────
// 📌 Members CRUD
// ─────────────────────────────────────────────────
Route::get('/members',                  [MemberController::class, 'index'])  ->name('members.index');
Route::get('/members/create',           [MemberController::class, 'create']) ->name('members.create');
Route::post('/members/store',           [MemberController::class, 'store'])  ->name('members.store');
Route::get('/members/{id}',             [MemberController::class, 'show'])   ->name('members.show');
Route::get('/members/{id}/edit',        [MemberController::class, 'edit'])   ->name('members.edit');
Route::put('/members/{id}/update',      [MemberController::class, 'update']) ->name('members.update');
Route::delete('/members/{id}/delete',   [MemberController::class, 'destroy'])->name('members.destroy');

// AJAX — fetch available seats for selected library + shift
Route::get('/members/ajax/seats', [MemberController::class, 'getSeats'])->name('members.ajax.seats');

// ─────────────────────────────────────────────────
// 📌 Seat Map & Management
// ─────────────────────────────────────────────────
Route::get('/seats/{library}/map',         [SeatController::class, 'index'])     ->name('seats.index');
Route::post('/seats/{library}/generate',   [SeatController::class, 'generate'])  ->name('seats.generate');
Route::delete('/seats/{library}/clear',    [SeatController::class, 'destroyAll'])->name('seats.destroyAll');
Route::put('/seats/{id}/update',           [SeatController::class, 'update'])    ->name('seats.update');
Route::get('/seats/available',             [SeatController::class, 'available']) ->name('seats.available');

    });

