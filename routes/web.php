<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EcertController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\CommunityForumController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::check()) {
        // Get the selected role from session
        $selectedRole = session('selected_role');
        
        if ($selectedRole) {
            // Convert event_organizer to organizer for the route
            if ($selectedRole === 'event_organizer') {
                return redirect()->route('organizer.dashboard');
            }
            return redirect()->route($selectedRole . '.dashboard');
        }

        // If no role is selected, get user's roles
        $userRole = DB::table('role_user')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', Auth::id())
            ->select('roles.role_name as name')
            ->first();
            
        if ($userRole) {
            if ($userRole->name === 'event_organizer') {
                return redirect()->route('organizer.dashboard');
            }
            return redirect()->route($userRole->name . '.dashboard');
        }
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('events', EventController::class);
    Route::resource('venue', VenueController::class);
    Route::resource('news', NewsController::class);
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Report routes
    Route::resource('report', ReportController::class)->only(['index', 'show']);

    // Admin management routes
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-as-read');
});


// Organizer routes
Route::middleware(['auth'])->prefix('organizer')->name('organizer.')->group(function () {
    Route::get('/dashboard', [OrganizerController::class, 'index'])->name('dashboard');

    // Events management
    Route::resource('events', EventController::class);
    Route::get('/events/{event}/participants', [EventController::class, 'showParticipants'])->name('events.participants');

    // Venues management
    Route::resource('venues', VenueController::class);
    Route::post('/venues/available', [VenueController::class, 'getAvailableVenues'])->name('venues.available');
    Route::post('venues/{venue}/book', [VenueController::class, 'book'])->name('venues.book');
    Route::get('venues/booked', [VenueController::class, 'bookedVenues'])->name('venues.booked');
    Route::put('venues/{venue}/removeBooking', [VenueController::class, 'removeBooking'])->name('venues.removeBooking');
    
    // Volunteers management
    Route::resource('volunteers', VolunteerController::class);
    Route::put('/volunteers/{id}/status', [VolunteerController::class, 'updateStatus'])->name('volunteers.updateStatus');
    
    // Community forum management
    Route::resource('community', CommunityForumController::class);
    
    // Profile management
    Route::resource('profile', ProfileController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Feedback and Rating management
    Route::resource('feedback', FeedbackController::class)->only(['index', 'show', 'create', 'store']); // Add more methods as needed

    // Report management
    Route::resource('report', ReportController::class)->only(['index', 'show']);
    //Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    
    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\Organizer\NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\Organizer\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\Organizer\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-as-read');
});

// Student routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'index'])->name('dashboard');
    Route::resource('events', EventController::class)->only(['index', 'show']);

    // Custom routes for volunteering requests
    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::get('/volunteers/{volunteer}', [VolunteerController::class, 'show'])->name('volunteers.show');
    Route::post('/volunteers', [VolunteerController::class, 'storeStudentRequest'])->name('volunteers.store');
    Route::post('/volunteers/{id}/revoke', [VolunteerController::class, 'revokeStudentRequest'])->name('volunteers.revoke');

    // Custom route for events routes for students
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');
    Route::post('/events/{event}/unregister', [EventController::class, 'unregister'])->name('events.unregister');
    Route::post('/events/{event}/attend', [EventController::class, 'confirmAttendance'])->name('events.attend');

    // Feedback routes
    Route::post('/events/{event}/feedback', [FeedbackController::class, 'store'])->name('events.feedback'); 

    // Custom routes for Community routes for students
    Route::get('/community', [CommunityForumController::class, 'index'])->name('community.index');
    Route::post('/community/{id}/like', [CommunityForumController::class, 'toggleLike'])->name('community.like');
    Route::post('/community/{id}/view', [CommunityForumController::class, 'registerView'])->name('community.registerView');

    // Student profile routes
    Route::resource('profile', ProfileController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Custom routes for E-Certificates for students
    Route::post('/events/{event}/ecert/generate', [EcertController::class, 'generateEcert'])->name('ecert.generate');
    Route::get('/ecert/{ecert}/download', [EcertController::class, 'downloadEcert'])->name('ecert.download');

    // Analytics/Report route for students
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\Student\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\Student\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\Student\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-as-read');
});


//Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
//Route::get('/organizer/dashboard', [OrganizerController::class, 'index'])->name('organizer.dashboard');
//Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');

require __DIR__.'/auth.php';

Route::post('/organizer/venues/available', [VenueController::class, 'getAvailableVenues'])
    ->name('organizer.venues.available');

Route::post('/organizer/venues/check-availability', [VenueController::class, 'checkAvailability'])
    ->name('organizer.venues.check-availability');

Route::get('/admin/report/events-by-year/{year}', [ReportController::class, 'getEventsByYear'])
    ->name('admin.report.events-by-year');

Route::get('/organizer/report/events-by-year/{year}', [ReportController::class, 'getEventsByYear'])
    ->name('organizer.report.events-by-year');
