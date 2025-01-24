<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Admin;
use App\Models\Organizer;
use App\Models\Event;
use App\Models\VolunteerRequest;

class ReportController extends Controller
{
    public function index()
    {
        $selectedRole = session('selected_role');

        if ($selectedRole === 'admin') {
            // Fetch the admin associated with the authenticated user
            $admin = Admin::where('user_id', Auth::id())->firstOrFail();

            // Fetch total events, active/suspended events, venue utilization, news count
            $totalEvents = DB::table('events')->count();
            $activeEvents = DB::table('events')->where('event_status', 'active')->count();
            $suspendedEvents = DB::table('events')->where('event_status', 'suspended')->count();
            $totalVenues = DB::table('venues')->count();
            $bookedVenues = DB::table('venue_book')
                  ->whereDate('booking_end_date', '>=', now()) // Filter for ongoing/future bookings
                  ->count();
            $availableVenues = $totalVenues - $bookedVenues;
            $totalNews = DB::table('news')->count();

            // Fetch new user counts grouped by year
            $newUserCount = DB::table('users')
                ->select(DB::raw('COUNT(id) as total_users'), DB::raw('YEAR(created_at) as year'))
                ->groupBy('year')
                ->orderBy('year')
                ->get();

            // Get the selected year (default to current year if not specified)
            $selectedYear = request('year', date('Y'));

            // Get last 4 years for the dropdown
            $availableYears = array_map(function($i) {
                return date('Y') - $i;
            }, range(0, 3));

            // Modify events per month query to use selected year
            $eventsData = DB::table('events')
                ->select(
                    DB::raw('COUNT(event_id) as total_events'),
                    DB::raw('MONTH(created_at) as month')
                )
                ->whereYear('created_at', $selectedYear)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total_events', 'month')
                ->toArray();

            // Create array for all 12 months
            $eventsPerMonth = [];
            for ($month = 1; $month <= 12; $month++) {
                $eventsPerMonth[] = (object)[
                    'month' => $month,
                    'month_name' => date("F", mktime(0, 0, 0, $month, 1)),
                    'total_events' => $eventsData[$month] ?? 0,
                    'year' => $selectedYear
                ];
            }
            $eventsPerMonth = collect($eventsPerMonth); // Convert to collection

            // Generate colors for all 12 months
            $chartColors = array_map(function($index) {
                return 'hsl(' . (($index * 30) % 360) . ', 70%, 50%)';
            }, range(0, 11));

            // Return the admin view with data
            return view('admin.report.index', compact(
                'totalEvents', 'activeEvents', 'suspendedEvents',
                'totalVenues', 'bookedVenues', 'availableVenues',
                'totalNews', 'newUserCount', 'eventsPerMonth', 
                'chartColors', 'selectedYear', 'availableYears'
            ));
        } elseif ($selectedRole === 'event_organizer') {
            // Fetch the organizer's data
            $organizer = Organizer::where('user_id', Auth::id())->firstOrFail();
            $organizerId = $organizer->organizer_id;

            // Fetch data for report summary
            $totalEvents = Event::where('organizer_id', $organizerId)->count();
            $pendingVolunteers = VolunteerRequest::join('volunteers', 'volunteer_requests.volunteer_id', '=', 'volunteers.volunteer_id')
                ->where('volunteers.organizer_id', $organizerId)
                ->where('volunteer_requests.status', 'pending')
                ->count();
            $totalForumPosts = DB::table('community_forum')
                ->where('organizer_id', $organizerId)
                ->count();
            $averageEventRating = DB::table('feedback')
                ->join('events', 'feedback.event_id', '=', 'events.event_id')
                ->where('events.organizer_id', $organizerId)
                ->avg('feedback.rating');

            // Get events data by year
            $selectedYear = request('year', date('Y'));
            $availableYears = array_map(function($i) {
                return date('Y') - $i;
            }, range(0, 3));

            // Volunteer analysis remains the same
            $volunteerAnalysis = DB::table('volunteer_requests')
                ->join('volunteers', 'volunteer_requests.volunteer_id', '=', 'volunteers.volunteer_id')
                ->where('volunteers.organizer_id', $organizerId)
                ->select(
                    DB::raw('COUNT(volunteer_requests.request_id) as total_requests'),
                    DB::raw('SUM(CASE WHEN volunteer_requests.status = "accepted" THEN 1 ELSE 0 END) as accepted_requests'),
                    DB::raw('SUM(CASE WHEN volunteer_requests.status = "rejected" THEN 1 ELSE 0 END) as rejected_requests')
                )
                ->first();

            return view('organizer.report.index', compact(
                'totalEvents', 'pendingVolunteers', 'totalForumPosts',
                'averageEventRating', 'volunteerAnalysis', 'selectedYear', 'availableYears'
            ));
        } elseif ($selectedRole === 'student') {
            // Fetch the authenticated student's ID
            $student = Student::where('user_id', Auth::id())->firstOrFail();
            $studentId = $student->stud_id;  // Extract stud_id

            // Fetch total events joined by this student
            $totalEventsJoined = DB::table('attendance')
                ->where('stud_id', $studentId)
                ->count();

            // Fetch total certifications received by this student
            $certificationsReceived = DB::table('ecertificates')
                ->where('stud_id', $studentId)
                ->count();

            // Fetch total volunteer requests made by this student
            $totalVolunteerRequests = DB::table('volunteer_requests')
                ->where('stud_id', $studentId)
                ->count();

            // Fetch total forum views made by this student
            $totalForumViews = DB::table('forum_action')
                ->where('stud_id', $studentId)
                ->where('action_type', 'view')
                ->count();

            // Fetch total forum likes made by this student
            $totalForumLikes = DB::table('forum_action')
                ->where('stud_id', $studentId)
                ->where('action_type', 'like')
                ->count();

            // Fetch average event rating given by this student
            $averageEventRatingGiven = DB::table('feedback')
                ->where('stud_id', $studentId)
                ->avg('rating');

            // Fetch data for Registered Events (monthly breakdown)
            $eventRegistrations = DB::table('attendance')
            ->where('stud_id', $studentId)
            ->whereNotNull('register_datetime')
            ->select(DB::raw('MONTH(register_datetime) as month'), DB::raw('COUNT(event_id) as registered_count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            // Fetch data for Attended Events (monthly breakdown)
            $eventAttendance = DB::table('attendance')
            ->where('stud_id', $studentId)
            ->whereNotNull('attendance_datetime')
            ->select(DB::raw('MONTH(attendance_datetime) as month'), DB::raw('COUNT(event_id) as attended_count'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            // Fetch data for Certifications Received Over Time (monthly breakdown)
            $certificationsReceivedOverTime = DB::table('ecertificates')
                ->where('stud_id', $studentId)
                ->select(DB::raw('MONTH(ecert_datetime) as month'), DB::raw('COUNT(event_id) as certifications'))
                ->groupBy('month')
                ->get();

            // Fetch data for Feedback Given Over Time (monthly breakdown)
            $feedbackGivenOverTime = DB::table('feedback')
                ->where('stud_id', $studentId)
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(event_id) as feedbacks_given'))
                ->groupBy('month')
                ->get();

            // Return the student view with data
            return view('student.report.index', compact(
                'totalEventsJoined', 'certificationsReceived', 'totalVolunteerRequests', 
                'totalForumViews', 'totalForumLikes', 'averageEventRatingGiven', 
                'eventRegistrations', 'eventAttendance', 'certificationsReceivedOverTime', 'feedbackGivenOverTime'
            ));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }

    public function show($eventId)
    {
        $selectedRole = session('selected_role');
    
        // Get the event with feedback and associated student details
        $event = Event::with('feedback.student')->findOrFail($eventId);
    
        // Fetch participants by joining attendance with students and users tables
        $participants = DB::table('attendance')
            ->join('students', 'attendance.stud_id', '=', 'students.stud_id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('attendance.event_id', $eventId)
            ->where('roles.role_name', 'student')
            ->select(
                'students.stud_name as user_name',
                'users.matric_no',
                'users.email',
                'students.stud_phoneNo',
                'students.stud_course',
                'attendance.register_datetime',
                'attendance.status',
                'attendance.attendance_datetime'
            )
            ->get();
    
        if ($selectedRole === 'admin') {
            // Pass both event and participants data to the admin view
            return view('admin.report.show', compact('event', 'participants'));
        } elseif ($selectedRole === 'event_organizer') {
            return view('organizer.report.show', compact('event'));
        } else {
            return redirect()->route('login')->with('error', 'Access denied.');
        }
    }    

    public function getEventsByYear($year)
    {
        try {
            $eventsPerMonth = DB::table('events')
                ->selectRaw('MONTH(created_at) as month')
                ->selectRaw('MONTHNAME(created_at) as month_name')
                ->selectRaw('COUNT(*) as total_events')
                ->whereYear('created_at', $year)
                ->groupBy('month', 'month_name')
                ->orderBy('month')
                ->get();

            // Generate colors for each month
            $colors = collect($eventsPerMonth)->map(function($item, $index) {
                return 'hsl(' . (($index * 30) % 360) . ', 70%, 50%)';
            });

            return response()->json([
                'events' => $eventsPerMonth,
                'colors' => $colors,
                'total' => $eventsPerMonth->sum('total_events')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }
}

