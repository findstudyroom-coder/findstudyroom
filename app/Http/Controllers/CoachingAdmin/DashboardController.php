<?php

namespace App\Http\Controllers\CoachingAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Coaching;
use App\Models\CoachingLead;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        
        // Determine which coaching to scope to
        if ($user->isSuperAdmin()) {
            $coachingId = $request->get('coaching_id') ?: ($user->coaching_id ?: Coaching::value('id'));
        } else {
            $coachingId = $user->coaching_id;
        }

        $coaching = Coaching::withCount(['classes', 'instructors', 'schedules', 'plans', 'galleries'])
            ->findOrFail($coachingId);

        // 1. KPI Stats
        $leadsQuery = CoachingLead::forCoaching($coachingId);
        
        $totalLeads     = (clone $leadsQuery)->count();
        $newLeads       = (clone $leadsQuery)->where('status', CoachingLead::STATUS_NEW)->count();
        $contactedLeads = (clone $leadsQuery)->where('status', CoachingLead::STATUS_CONTACTED)->count();
        $interestedLeads= (clone $leadsQuery)->where('status', CoachingLead::STATUS_INTERESTED)->count();
        $convertedLeads = (clone $leadsQuery)->where('status', CoachingLead::STATUS_CONVERTED)->count();
        $closedLeads    = (clone $leadsQuery)->where('status', CoachingLead::STATUS_CLOSED)->count();

        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;
        $activePipeline = $newLeads + $contactedLeads + $interestedLeads;

        // 2. Daily Lead Analytics (Last 14 Days)
        $dailyLabels = [];
        $dailyValues = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $dateStr = $day->format('Y-m-d');
            $dailyLabels[] = $day->format('d M');

            $count = (clone $leadsQuery)->where(function($q) use ($dateStr) {
                $q->whereDate('enquiry_date', $dateStr)
                  ->orWhere(function($sub) use ($dateStr) {
                      $sub->whereNull('enquiry_date')->whereDate('created_at', $dateStr);
                  });
            })->count();

            $dailyValues[] = $count;
        }

        // 3. Monthly Lead Analytics (Last 6 Months)
        $monthlyLabels = [];
        $monthlyValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::today()->startOfMonth()->subMonths($i);
            $monthStart = $monthDate->copy()->startOfMonth();
            $monthEnd = $monthDate->copy()->endOfMonth();
            $monthlyLabels[] = $monthDate->format('M Y');

            $count = (clone $leadsQuery)->whereBetween('created_at', [$monthStart, $monthEnd])->count();
            $monthlyValues[] = $count;
        }

        // 4. Status Breakdown for Doughnut Chart
        $statusDistribution = [
            'New'        => $newLeads,
            'Contacted'  => $contactedLeads,
            'Interested' => $interestedLeads,
            'Converted'  => $convertedLeads,
            'Closed'     => $closedLeads,
        ];

        // 5. Top Courses / Disciplines interested
        $topCourses = CoachingLead::forCoaching($coachingId)
            ->whereNotNull('course_interested')
            ->where('course_interested', '!=', '')
            ->select('course_interested', DB::raw('COUNT(*) as total'))
            ->groupBy('course_interested')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 6. Recent Leads (Last 8)
        $recentLeads = CoachingLead::forCoaching($coachingId)
            ->latest('id')
            ->take(8)
            ->get();

        // All coachings list if Super Admin is switching view
        $allCoachings = $user->isSuperAdmin() ? Coaching::orderBy('coaching_name')->get() : collect();

        return view('coaching_admin.dashboard', compact(
            'coaching',
            'totalLeads',
            'newLeads',
            'contactedLeads',
            'interestedLeads',
            'convertedLeads',
            'closedLeads',
            'conversionRate',
            'activePipeline',
            'dailyLabels',
            'dailyValues',
            'monthlyLabels',
            'monthlyValues',
            'statusDistribution',
            'topCourses',
            'recentLeads',
            'allCoachings'
        ));
    }
}
