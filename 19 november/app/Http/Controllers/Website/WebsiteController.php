<?php

namespace App\Http\Controllers\Website;
use App\Http\Controllers\Controller;
use App\Http\Request\Auth\UserSignInRequest;
use App\Http\Request\Auth\ForgetPasswordRequest;
use App\Http\Request\Auth\EditRequest;
use App\Http\Request\Auth\EditPasswordRequest;
use App\Mail\AdminForgetPassword;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Service;
use App\Models\About;
use App\Models\Testimonial;
use App\Models\Product;
use App\Models\Library;
use App\Models\Tutor;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use DB;
class WebsiteController extends BaseController
{


public function website(Request $request){
    $library = Library::with('state','district')->orderByDesc('is_verified')->latest()->take(6)->get();
    $tutors = \App\Models\Tutor::latest()->take(6)->get();
    $blogs = Blog::where('status', 'published')->latest('published_at')->take(3)->get();
    $topStates = \App\Models\State::withCount('libraries')->has('libraries')->orderByDesc('libraries_count')->take(10)->get();
    return view('website', compact('library', 'tutors', 'blogs', 'topStates'));
}

public function blogIndex()
{
    $blogs = Blog::where('status', 'published')->latest('published_at')->get();
    return view('blogs.index', compact('blogs'));
}

public function blogDetails($slug)
{
    $blog = Blog::where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

    return view('blogs.show', compact('blog'));
}

public function libraryDetails(Request $request)
{
   $library = Library::with('state','district')
    ->where(function($query) use ($request) {
        $query->where('slug', $request->slug)
              ->orWhere('id', $request->slug);
    })
    ->firstOrFail();
    
    // Get nearby libraries (same district/city)
    $nearbyQuery = Library::with('state', 'district')->where('id', '!=', $library->id);
    if ($library->city_id) {
        $nearbyQuery->where('city_id', $library->city_id);
    } elseif ($library->state_id) {
        $nearbyQuery->where('state_id', $library->state_id);
    }
    $nearbyLibraries = $nearbyQuery->orderByDesc('is_verified')->latest()->take(4)->get();

    // Get similar libraries
    $similarLibraries = Library::with('state', 'district')
        ->where('id', '!=', $library->id)
        ->inRandomOrder()
        ->take(3)
        ->get();

    // Record visitor click / view
    try {
        \App\Models\LibraryView::recordVisit($library->id, $request, 'view');
    } catch (\Exception $e) {}

    if ($library && $library->is_verified) {
        return view('verified-library', compact('library', 'nearbyLibraries', 'similarLibraries'));
    }
    
    return view('singlelibrary', compact('library', 'nearbyLibraries', 'similarLibraries'));
}

public function trackClick(Request $request)
{
    $libraryId = $request->input('library_id');
    $actionType = $request->input('action_type', 'click');

    if ($libraryId) {
        try {
            \App\Models\LibraryView::recordVisit($libraryId, $request, $actionType);
        } catch (\Exception $e) {}
    }

    return response()->json(['status' => 'success']);
}

public function allLibraries()
{
    // Return all libraries with states
    $libraries = Library::with('state','district')
        ->orderByDesc('is_verified')
        ->latest()
        ->get();
    
    // Get all states with their library counts
    $states = \App\Models\State::with('districts')
        ->withCount('libraries')
        ->get();
    
    return view('all-libraries', compact('libraries', 'states'));
}

public function stateLibraries($slug)
{
    // Get the state by slug (using Str::slug to match)
    $state = \App\Models\State::get()
        ->first(function($state) use ($slug) {
            return \Illuminate\Support\Str::slug($state->name) === $slug;
        });
    
    if (!$state) {
        abort(404, 'State not found');
    }
    
    // Get all libraries for this state with district info
    $libraries = Library::with(['state', 'district'])
        ->where('state_id', $state->id)
        ->orderByDesc('is_verified')
        ->latest()
        ->get();
    
    // Group libraries by district
    $librariesByDistrict = $libraries->groupBy(function($lib) {
        return $lib->district->name ?? 'Unknown District';
    });
    
    // Get districts for this state
    $districts = \App\Models\District::where('state_id', $state->id)
        ->withCount('libraries')
        ->get();
    
    // Get all states for the state selector on the page
    $states = \App\Models\State::with('districts')
        ->withCount('libraries')
        ->get();
    
    return view('state-libraries', compact('state', 'libraries', 'librariesByDistrict', 'states', 'districts'));
}

public function districtLibraries($slug)
{
    // Get the district by slug
    $district = \App\Models\District::with('state')
        ->get()
        ->first(function($district) use ($slug) {
            return \Illuminate\Support\Str::slug($district->name) === $slug;
        });
    
    if (!$district) {
        abort(404, 'District not found');
    }
    
    // Get all libraries for this district
    $libraries = Library::with(['state', 'district'])
        ->where('city_id', $district->id)
        ->orderByDesc('is_verified')
        ->latest()
        ->get();
    
    // Get the state
    $state = $district->state;
    
    // Get all districts in this state for quick navigation
    $districts = \App\Models\District::where('state_id', $state->id)
        ->withCount('libraries')
        ->get();
    
    // Get all states for the state selector on the page
    $allStates = \App\Models\State::with('districts')
        ->withCount('libraries')
        ->get();
    
    return view('district-libraries', compact('district', 'state', 'libraries', 'districts', 'allStates'));
}

public function allTutors()
{
    $tutors = \App\Models\Tutor::latest()->get();
    return view('all-tutors', compact('tutors'));
}

public function tutorDetails($identifier)
{
    $slugified = \Illuminate\Support\Str::slug(str_replace('-', ' ', $identifier));

    $tutor = \App\Models\Tutor::where('id', $identifier)
        ->orWhere('slug', $identifier)
        ->orWhereRaw('LOWER(REPLACE(full_name, " ", "-")) = ?', [strtolower($slugified)])
        ->orWhereRaw('LOWER(full_name) = ?', [strtolower(str_replace('-', ' ', $identifier))])
        ->first();

    if (! $tutor) {
        abort(404);
    }

    return view('single-tutor', compact('tutor'));
}

public function loadMoreLibraries(Request $request)
{
    $offset = $request->offset;

    $libraries = Library::orderByDesc('is_verified')
        ->latest()
        ->skip($offset)
        ->take(10)
        ->get();

    return response()->json($libraries);
}

public function getStateLibraries(Request $request)
{
    $stateId = $request->state_id;
    
    // Get libraries for the selected state, grouped by district
    $libraries = Library::with(['state', 'district'])
        ->where('state_id', $stateId)
        ->orderByDesc('is_verified')
        ->latest()
        ->get();
    
    // Group libraries by district
    $librariesByDistrict = $libraries->groupBy(function($lib) {
        return $lib->district->name ?? 'Unknown District';
    });
    
    return response()->json([
        'libraries' => $libraries,
        'groupedByDistrict' => $librariesByDistrict->map(function($items) {
            return $items->values();
        })
    ]);
}

public function about()
{
   
    return view('static.about');
}



    public function search(Request $request)
    {
        $queryStr = trim($request->search);
        
        $query = Library::query();

        // 1. Filter by Generic Search String
        if ($queryStr) {
            $query->where(function($q) use ($queryStr) {
                $q->where('name', 'LIKE', "%{$queryStr}%")
                  ->orWhere('location', 'LIKE', "%{$queryStr}%")
                  ->orWhereHas('district', function($dq) use ($queryStr) {
                      $dq->where('name', 'LIKE', "%{$queryStr}%");
                  })
                  ->orWhereHas('state', function($sq) use ($queryStr) {
                      $sq->where('name', 'LIKE', "%{$queryStr}%");
                  })
                  ->orWhere('pincode', 'LIKE', "%{$queryStr}%");
            });
        }

        // 2. Filter by Type (LIKE on facilities or descriptions)
        if ($request->filled('type')) {
            $type = $request->type;
            $query->where(function($q) use ($type) {
                $q->where('facilities', 'LIKE', "%{$type}%")
                  ->orWhere('descriptions', 'LIKE', "%{$type}%")
                  ->orWhere('name', 'LIKE', "%{$type}%");
            });
        }

        // 3. Filter by Location
        if ($request->filled('state_id')) {
            $query->where('state_id', $request->state_id);
        }
        if ($request->filled('district_id')) {
            $query->where('city_id', $request->district_id);
        }

        // 4. Filter by Budget
        if ($request->filled('budget')) {
            $budget = $request->budget;
            if ($budget === 'Under ₹500') {
                $query->where('price', '<', 500);
            } elseif ($budget === '₹500–1000') {
                $query->whereBetween('price', [500, 1000]);
            } elseif ($budget === '₹1000–1500') {
                $query->whereBetween('price', [1000, 1500]);
            } elseif ($budget === '₹1500–2000') {
                $query->whereBetween('price', [1500, 2000]);
            } elseif ($budget === 'Above ₹2000') {
                $query->where('price', '>', 2000);
            }
        }

        $libraries = $query->orderByDesc('is_verified')->latest()->get();

        // Tutors logic (keep generic search)
        $tutorsQuery = Tutor::query();
        if ($queryStr) {
            $tutorsQuery->where('full_name', 'LIKE', "%{$queryStr}%")
                ->orWhere('bio', 'LIKE', "%{$queryStr}%")
                ->orWhere('qualification', 'LIKE', "%{$queryStr}%")
                ->orWhere('subject', 'LIKE', "%{$queryStr}%")
                ->orWhere('classes', 'LIKE', "%{$queryStr}%")
                ->orWhere('boards', 'LIKE', "%{$queryStr}%");
        }
        $tutors = $tutorsQuery->latest()->get();

        return view('search-results', [
            'libraries' => $libraries,
            'tutors' => $tutors,
            'query' => $queryStr
        ]);
    }

    public function searchAjax(Request $request)
    {
        $query = trim($request->search);

        if (! $query) {
            return response()->json(['libraries' => [], 'tutors' => []]);
        }

        $libraries = Library::where('name', 'LIKE', "%{$query}%")
            ->orWhere('location', 'LIKE', "%{$query}%")
            ->orWhereHas('district', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('state', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orderByDesc('is_verified')
            ->take(4)
            ->get(['name', 'slug', 'location', 'rating', 'price', 'status']);

        $tutors = Tutor::where('full_name', 'LIKE', "%{$query}%")
            ->orWhere('bio', 'LIKE', "%{$query}%")
            ->orWhere('qualification', 'LIKE', "%{$query}%")
            ->orWhere('subject', 'LIKE', "%{$query}%")
            ->orWhere('classes', 'LIKE', "%{$query}%")
            ->orWhere('boards', 'LIKE', "%{$query}%")
            ->take(4)
            ->get(['full_name', 'slug', 'hourly_rate', 'experience_years', 'subject']);

        return response()->json(['libraries' => $libraries, 'tutors' => $tutors]);
    }

    public function theMovement()
    {
        $commandoClasses = \App\Models\CommandoClass::where('status', 1)->orderBy('order_no', 'asc')->get();
        $instructors = \App\Models\CommandoInstructor::where('status', 1)->orderBy('order_no', 'asc')->get();
        $schedules = \App\Models\CommandoSchedule::where('status', 1)->orderBy('order_no', 'asc')->get();
        $plans = \App\Models\CommandoPlan::where('status', 1)->orderBy('order_no', 'asc')->get();
        $galleries = \App\Models\CommandoGallery::where('status', 1)->orderBy('order_no', 'asc')->get();

        return view('the-movement', compact('commandoClasses', 'instructors', 'schedules', 'plans', 'galleries'));
    }
}
