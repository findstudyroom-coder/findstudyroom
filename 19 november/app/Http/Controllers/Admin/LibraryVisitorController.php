<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Library;
use App\Models\LibraryView;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LibraryVisitorController extends Controller
{
    /**
     * Display the Dynamic Library Visitors & Interest Report
     */
    public function index(Request $request)
    {
        // 1. Resolve Date Range
        $filterType = $request->input('date_filter', 'this_month');
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');
        $selectedLibraryId = $request->input('library_id');
        $selectedLibrary = $selectedLibraryId ? Library::with(['district', 'state'])->find($selectedLibraryId) : null;
        $actionFilter = $request->input('action_type', 'all');
        $search = trim($request->input('search', ''));
        $activeTab = $request->input('tab', 'summary');

        switch ($filterType) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'all_time':
                $startDate = Carbon::parse('2020-01-01 00:00:00');
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $startDate = $customStart ? Carbon::parse($customStart)->startOfDay() : Carbon::now()->startOfMonth();
                $endDate = $customEnd ? Carbon::parse($customEnd)->endOfDay() : Carbon::now()->endOfDay();
                break;
            case 'this_month':
            default:
                $filterType = 'this_month';
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        // 2. Base Query for Views
        $viewsQuery = LibraryView::with(['library.district', 'library.state', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedLibraryId) {
            $viewsQuery->where('library_id', $selectedLibraryId);
        }

        if ($actionFilter && $actionFilter !== 'all') {
            $viewsQuery->where('action_type', $actionFilter);
        }

        if ($search) {
            $viewsQuery->where(function ($q) use ($search) {
                $q->whereHas('library', function ($lq) use ($search) {
                    $lq->where('name', 'like', "%{$search}%")
                       ->orWhere('location', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhere('ip_address', 'like', "%{$search}%")
                ->orWhere('device', 'like', "%{$search}%");
            });
        }

        // 3. Base Query for Bookings (Interested Users)
        $bookingsQuery = Booking::with(['library.district', 'library.state'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($selectedLibraryId) {
            $bookingsQuery->where('library_id', $selectedLibraryId);
        }

        if ($search) {
            $bookingsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('library', function ($lq) use ($search) {
                      $lq->where('name', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
                  });
            });
        }

        // 4. Calculate Key Metrics
        $totalClicks = (clone $viewsQuery)->count();
        $uniqueVisitors = (clone $viewsQuery)->distinct()->count(DB::raw("COALESCE(user_id, session_id, ip_address)"));
        $totalInterested = (clone $bookingsQuery)->count();
        $conversionRate = $uniqueVisitors > 0 ? round(($totalInterested / $uniqueVisitors) * 100, 1) : 0;

        $pageViewsCount = (clone $viewsQuery)->where('action_type', 'view')->count();
        $callClicksCount = (clone $viewsQuery)->where('action_type', 'call_click')->count();
        $bookClicksCount = (clone $viewsQuery)->whereIn('action_type', ['book_click', 'membership_click'])->count();

        // Today's live count
        $todayClicks = LibraryView::whereDate('created_at', Carbon::today())->count();
        $todayInterested = Booking::whereDate('created_at', Carbon::today())->count();

        // 5. Library-wise Aggregate Report (Summary Tab)
        $libraryIdsFromViews = (clone $viewsQuery)->pluck('library_id')->unique();
        $libraryIdsFromBookings = (clone $bookingsQuery)->pluck('library_id')->unique();
        $allActiveLibraryIds = $libraryIdsFromViews->merge($libraryIdsFromBookings)->filter()->unique();

        $librarySummaryQuery = Library::with(['district', 'state'])
            ->withCount([
                'views as total_clicks' => function ($q) use ($startDate, $endDate, $actionFilter) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                    if ($actionFilter && $actionFilter !== 'all') {
                        $q->where('action_type', $actionFilter);
                    }
                },
                'views as today_clicks' => function ($q) {
                    $q->whereDate('created_at', Carbon::today());
                },
                'views as call_clicks' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->where('action_type', 'call_click');
                },
                'views as book_modal_clicks' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                      ->whereIn('action_type', ['book_click', 'membership_click']);
                },
                'bookings as total_bookings' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            ]);

        if ($search) {
            $librarySummaryQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        } elseif (!$selectedLibraryId && $allActiveLibraryIds->isNotEmpty()) {
            $librarySummaryQuery->whereIn('id', $allActiveLibraryIds);
        }

        if ($selectedLibraryId) {
            $librarySummaryQuery->where('id', $selectedLibraryId);
        }

        $librarySummary = $librarySummaryQuery
            ->orderByDesc('total_clicks')
            ->orderByDesc('total_bookings')
            ->paginate(15, ['*'], 'lib_page');

        // Append unique visitors, interest conversion %, and last visited time
        $librarySummary->getCollection()->transform(function ($item) use ($startDate, $endDate) {
            $uniqueLibVisitors = LibraryView::where('library_id', $item->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct()
                ->count(DB::raw("COALESCE(user_id, session_id, ip_address)"));

            $item->unique_visitors = $uniqueLibVisitors;
            $item->interest_rate = $uniqueLibVisitors > 0 ? round(($item->total_bookings / $uniqueLibVisitors) * 100, 1) : 0;
            $item->last_visited_at = LibraryView::where('library_id', $item->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->latest()
                ->value('created_at');
            return $item;
        });

        // 6. Top 4 Leaderboard Libraries for prominent cards
        $topVisitedLibraries = Library::with(['district', 'state'])
            ->withCount([
                'views as total_clicks' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                },
                'bookings as total_bookings' => function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            ])
            ->havingRaw('total_clicks > 0 OR total_bookings > 0')
            ->orderByDesc('total_clicks')
            ->take(4)
            ->get();

        foreach ($topVisitedLibraries as $topLib) {
            $topLib->unique_visitors = LibraryView::where('library_id', $topLib->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->distinct()
                ->count(DB::raw("COALESCE(user_id, session_id, ip_address)"));
            $topLib->interest_rate = $topLib->unique_visitors > 0 ? round(($topLib->total_bookings / $topLib->unique_visitors) * 100, 1) : 0;
        }

        // 7. Detailed Visitor Clicks Log (Visitors Tab) - Unique IP addresses only (no duplicates)
        $uniqueVisitorSubquery = (clone $viewsQuery)
            ->setEagerLoads([])
            ->select(
                DB::raw('MAX(id) as max_id'),
                DB::raw('COUNT(*) as visit_frequency')
            )
            ->groupBy(DB::raw('COALESCE(NULLIF(ip_address, ""), session_id)'));

        $visitorLogs = LibraryView::with(['library.district', 'library.state', 'user'])
            ->joinSub($uniqueVisitorSubquery, 'unique_visitors_sub', function ($join) {
                $join->on('library_views.id', '=', 'unique_visitors_sub.max_id');
            })
            ->select('library_views.*', 'unique_visitors_sub.visit_frequency')
            ->latest('library_views.created_at')
            ->paginate(20, ['*'], 'vis_page');

        // 8. Interested Users Log (Interested Tab)
        $interestedUsers = (clone $bookingsQuery)
            ->latest()
            ->paginate(20, ['*'], 'int_page');

        // 9. Trend Chart Data (Daily counts for date range)
        $chartData = $this->generateTrendChartData($startDate, $endDate, $selectedLibraryId);

        // 10. Device Breakdown for Chart
        $deviceBreakdown = LibraryView::whereBetween('created_at', [$startDate, $endDate])
            ->when($selectedLibraryId, function($q) use ($selectedLibraryId) {
                $q->where('library_id', $selectedLibraryId);
            })
            ->select('device', DB::raw('count(*) as count'))
            ->groupBy('device')
            ->pluck('count', 'device')
            ->toArray();

        // 11. Libraries List for Dropdown Filter
        $allLibraries = Library::select('id', 'name')->orderBy('name')->take(300)->get();

        return view('admin.visitors.index', compact(
            'filterType',
            'startDate',
            'endDate',
            'customStart',
            'customEnd',
            'selectedLibraryId',
            'actionFilter',
            'search',
            'activeTab',
            'totalClicks',
            'uniqueVisitors',
            'totalInterested',
            'conversionRate',
            'pageViewsCount',
            'callClicksCount',
            'bookClicksCount',
            'todayClicks',
            'todayInterested',
            'librarySummary',
            'topVisitedLibraries',
            'visitorLogs',
            'interestedUsers',
            'chartData',
            'deviceBreakdown',
            'allLibraries',
            'selectedLibrary'
        ));
    }

    /**
     * Real-time AJAX endpoint for live visitor updates
     */
    public function liveStats(Request $request)
    {
        $todayClicks = LibraryView::whereDate('created_at', Carbon::today())->count();
        $todayInterested = Booking::whereDate('created_at', Carbon::today())->count();
        $totalClicks = LibraryView::count();
        $uniqueVisitors = LibraryView::distinct()->count(DB::raw("COALESCE(user_id, session_id, ip_address)"));

        // Top 5 live libraries
        $topLibraries = Library::select('libraries.id', 'libraries.name')
            ->selectSub(function ($q) {
                $q->from('library_views')
                  ->selectRaw('count(distinct coalesce(nullif(ip_address, ""), session_id))')
                  ->whereColumn('library_views.library_id', 'libraries.id');
            }, 'views_count')
            ->withCount('bookings')
            ->orderByDesc('views_count')
            ->take(5)
            ->get(['id', 'name', 'views_count', 'bookings_count']);

        // Recent 5 live click events
        $recentClicks = LibraryView::with('library')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($v) {
                return [
                    'id' => $v->id,
                    'library_name' => $v->library->name ?? 'Library',
                    'action' => $v->action_type,
                    'device' => $v->device,
                    'time_ago' => $v->created_at->diffForHumans(),
                    'is_lead' => $v->action_type === 'booking_submitted',
                ];
            });

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->format('h:i:s A'),
            'today_clicks' => $todayClicks,
            'today_interested' => $todayInterested,
            'total_clicks' => $totalClicks,
            'unique_visitors' => $uniqueVisitors,
            'top_libraries' => $topLibraries,
            'recent_clicks' => $recentClicks,
        ]);
    }

    /**
     * AJAX endpoint: Get full visitor & booking history for a specific library
     */
    public function libraryDetailsAjax($libraryId, Request $request)
    {
        $library = Library::with(['district', 'state'])->findOrFail($libraryId);

        $totalViews = LibraryView::where('library_id', $libraryId)->count();
        $uniqueVisitors = LibraryView::where('library_id', $libraryId)
            ->distinct()
            ->count(DB::raw("COALESCE(user_id, session_id, ip_address)"));
        $totalBookings = Booking::where('library_id', $libraryId)->count();
        $callClicks = LibraryView::where('library_id', $libraryId)->where('action_type', 'call_click')->count();

        // Recent 25 unique visitor logs for this library
        $uniqueSubQuery = LibraryView::where('library_id', $libraryId)
            ->select(
                DB::raw('MAX(id) as max_id'),
                DB::raw('COUNT(*) as visit_frequency')
            )
            ->groupBy(DB::raw('COALESCE(NULLIF(ip_address, ""), session_id)'));

        $logs = LibraryView::with('user')
            ->joinSub($uniqueSubQuery, 'unique_sub', function ($join) {
                $join->on('library_views.id', '=', 'unique_sub.max_id');
            })
            ->select('library_views.*', 'unique_sub.visit_frequency')
            ->latest('library_views.created_at')
            ->take(25)
            ->get()
            ->map(function ($log) {
                return [
                    'time' => $log->created_at->format('d M, Y h:i A'),
                    'action' => $log->action_type,
                    'user_name' => $log->user ? $log->user->name : 'Guest Visitor',
                    'user_contact' => $log->user ? ($log->user->phone ?? $log->user->email) : $log->ip_address,
                    'device' => $log->device ?? 'Desktop',
                    'browser' => $log->browser ?? 'Browser',
                    'referer' => $log->referer ? parse_url($log->referer, PHP_URL_PATH) : 'Direct',
                    'visit_frequency' => $log->visit_frequency ?? 1,
                ];
            });

        // Bookings for this library
        $bookings = Booking::where('library_id', $libraryId)
            ->latest()
            ->take(15)
            ->get()
            ->map(function ($b) {
                return [
                    'time' => $b->created_at->format('d M, Y h:i A'),
                    'name' => $b->name,
                    'phone' => $b->phone,
                    'visit_date' => $b->date ? date('d M, Y', strtotime($b->date)) : '-',
                ];
            });

        return response()->json([
            'status' => 'success',
            'library' => [
                'id' => $library->id,
                'name' => $library->name,
                'location' => ($library->district->name ?? '') . ', ' . ($library->state->name ?? ''),
                'total_views' => $totalViews,
                'unique_visitors' => $uniqueVisitors,
                'total_bookings' => $totalBookings,
                'call_clicks' => $callClicks,
                'conversion_rate' => $uniqueVisitors > 0 ? round(($totalBookings / $uniqueVisitors) * 100, 1) : 0,
            ],
            'visitors' => $logs,
            'leads' => $bookings,
        ]);
    }

    /**
     * Show detailed visitor & interest stats for a single library
     */
    public function show($libraryId, Request $request)
    {
        $library = Library::with(['district', 'state'])->findOrFail($libraryId);
        $request->merge(['library_id' => $libraryId]);
        return $this->index($request);
    }

    /**
     * Export visitors and interest report to CSV
     */
    public function exportCsv(Request $request)
    {
        $filterType = $request->input('date_filter', 'this_month');
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');
        $selectedLibraryId = $request->input('library_id');
        $exportType = $request->input('export_type', 'summary');

        switch ($filterType) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'last_30_days':
                $startDate = Carbon::now()->subDays(29)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'all_time':
                $startDate = Carbon::parse('2020-01-01 00:00:00');
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $startDate = $customStart ? Carbon::parse($customStart)->startOfDay() : Carbon::now()->startOfMonth();
                $endDate = $customEnd ? Carbon::parse($customEnd)->endOfDay() : Carbon::now()->endOfDay();
                break;
            case 'this_month':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $fileName = 'library_' . $exportType . '_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.csv';

        $response = new StreamedResponse(function () use ($exportType, $startDate, $endDate, $selectedLibraryId) {
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM

            if ($exportType === 'visitors') {
                fputcsv($handle, ['ID', 'Date & Time', 'Library Name', 'Visitor Type', 'User Name / Phone', 'IP Address', 'Action', 'Device', 'Browser', 'Referer']);

                $baseQuery = LibraryView::whereBetween('created_at', [$startDate, $endDate]);
                if ($selectedLibraryId) $baseQuery->where('library_id', $selectedLibraryId);

                $subQuery = (clone $baseQuery)
                    ->select(
                        DB::raw('MAX(id) as max_id')
                    )
                    ->groupBy(DB::raw('COALESCE(NULLIF(ip_address, ""), session_id)'));

                $query = LibraryView::with(['library', 'user'])
                    ->joinSub($subQuery, 'unique_export_sub', function ($join) {
                        $join->on('library_views.id', '=', 'unique_export_sub.max_id');
                    })
                    ->select('library_views.*')
                    ->latest('library_views.created_at');

                $query->chunk(500, function ($views) use ($handle) {
                    foreach ($views as $v) {
                        $visitorType = $v->user ? 'Registered User' : 'Guest Visitor';
                        $userInfo = $v->user ? ($v->user->name . ' (' . ($v->user->phone ?? $v->user->email) . ')') : 'Guest (' . $v->ip_address . ')';
                        fputcsv($handle, [
                            $v->id,
                            $v->created_at->format('Y-m-d H:i:s'),
                            $v->library->name ?? 'N/A',
                            $visitorType,
                            $userInfo,
                            $v->ip_address ?? '-',
                            $v->action_type ?? 'view',
                            $v->device ?? 'Desktop',
                            $v->browser ?? 'Other',
                            $v->referer ?? 'Direct / Unknown'
                        ]);
                    }
                });

            } elseif ($exportType === 'interested') {
                fputcsv($handle, ['ID', 'Inquiry Date', 'Library Name', 'Interested User Name', 'Phone Number', 'Preferred Visit Date']);

                $query = Booking::with('library')
                    ->whereBetween('created_at', [$startDate, $endDate]);
                if ($selectedLibraryId) $query->where('library_id', $selectedLibraryId);

                $query->chunk(500, function ($bookings) use ($handle) {
                    foreach ($bookings as $b) {
                        fputcsv($handle, [
                            $b->id,
                            $b->created_at->format('Y-m-d H:i:s'),
                            $b->library->name ?? 'N/A',
                            $b->name,
                            $b->phone,
                            $b->date ? date('Y-m-d', strtotime($b->date)) : '-'
                        ]);
                    }
                });

            } else {
                fputcsv($handle, ['Library ID', 'Library Name', 'City / District', 'State', 'Total Clicks/Views', 'Unique Visitors', 'Interested Users (Bookings)', 'Interest Rate %', 'Call Clicks', 'Book Form Clicks']);

                $libraries = Library::with(['district', 'state'])
                    ->withCount([
                        'views as total_clicks' => function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('created_at', [$startDate, $endDate]);
                        },
                        'views as call_clicks' => function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('created_at', [$startDate, $endDate])
                              ->where('action_type', 'call_click');
                        },
                        'views as book_clicks' => function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('created_at', [$startDate, $endDate])
                              ->whereIn('action_type', ['book_click', 'membership_click']);
                        },
                        'bookings as total_bookings' => function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('created_at', [$startDate, $endDate]);
                        }
                    ])
                    ->havingRaw('total_clicks > 0 OR total_bookings > 0')
                    ->orderByDesc('total_clicks')
                    ->get();

                foreach ($libraries as $lib) {
                    $uniqueLibVisitors = LibraryView::where('library_id', $lib->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->distinct()
                        ->count(DB::raw("COALESCE(user_id, session_id, ip_address)"));

                    $rate = $uniqueLibVisitors > 0 ? round(($lib->total_bookings / $uniqueLibVisitors) * 100, 1) : 0;

                    fputcsv($handle, [
                        $lib->id,
                        $lib->name,
                        $lib->district->name ?? 'N/A',
                        $lib->state->name ?? 'N/A',
                        $lib->total_clicks,
                        $uniqueLibVisitors,
                        $lib->total_bookings,
                        $rate . '%',
                        $lib->call_clicks,
                        $lib->book_clicks
                    ]);
                }
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }

    /**
     * Generate daily chart trend data
     */
    private function generateTrendChartData(Carbon $startDate, Carbon $endDate, $selectedLibraryId = null)
    {
        $diffInDays = $startDate->diffInDays($endDate);
        if ($diffInDays > 60) {
            $views = LibraryView::whereBetween('created_at', [$startDate, $endDate])
                ->when($selectedLibraryId, function($q) use ($selectedLibraryId) {
                    $q->where('library_id', $selectedLibraryId);
                })
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date_label"), DB::raw('count(*) as count'))
                ->groupBy('date_label')
                ->pluck('count', 'date_label')
                ->toArray();

            $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
                ->when($selectedLibraryId, function($q) use ($selectedLibraryId) {
                    $q->where('library_id', $selectedLibraryId);
                })
                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as date_label"), DB::raw('count(*) as count'))
                ->groupBy('date_label')
                ->pluck('count', 'date_label')
                ->toArray();

            $allKeys = array_unique(array_merge(array_keys($views), array_keys($bookings)));
            sort($allKeys);

            $labels = [];
            $viewsData = [];
            $bookingsData = [];

            foreach ($allKeys as $key) {
                $labels[] = Carbon::createFromFormat('Y-m', $key)->format('M Y');
                $viewsData[] = $views[$key] ?? 0;
                $bookingsData[] = $bookings[$key] ?? 0;
            }

            return [
                'labels' => $labels,
                'views' => $viewsData,
                'interested' => $bookingsData,
            ];
        }

        $labels = [];
        $viewsData = [];
        $bookingsData = [];

        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->format('Y-m-d');
            $labels[] = $current->format('d M');

            $dayViews = LibraryView::whereDate('created_at', $dateStr)
                ->when($selectedLibraryId, function($q) use ($selectedLibraryId) {
                    $q->where('library_id', $selectedLibraryId);
                })
                ->count();

            $dayBookings = Booking::whereDate('created_at', $dateStr)
                ->when($selectedLibraryId, function($q) use ($selectedLibraryId) {
                    $q->where('library_id', $selectedLibraryId);
                })
                ->count();

            $viewsData[] = $dayViews;
            $bookingsData[] = $dayBookings;

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'views' => $viewsData,
            'interested' => $bookingsData,
        ];
    }
}
