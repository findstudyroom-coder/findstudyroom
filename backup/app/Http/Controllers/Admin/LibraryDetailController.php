<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LibraryDetail;
use App\Models\Library;
use App\Models\Plan;
use App\Services\LibraryUserService;

class LibraryDetailController extends Controller
{
    public function __construct(private LibraryUserService $libraryUserService)
    {
    }

    public function index()
    {
        $details = LibraryDetail::with('library')->latest()->get();
        return view('admin.library_details.index', compact('details'));
    }

    public function create()
    {
        $plans = Plan::all();
        return view('admin.library_details.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'library_id' => 'required|exists:libraries,id',
            'plan_id' => 'nullable|exists:plans,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capacity' => 'nullable|integer|min:0',
            'logo_url' => 'nullable|string|max:255',
        ]);

        $library = Library::findOrFail($request->library_id);
      
        if ($request->plan_id) {
            $plan = Plan::findOrFail($request->plan_id);
        }

        LibraryDetail::create([
            'library_id' => $library->id,
            'name' => $library->name,
            'location' => $library->location,
            'phone' => $library->phone,
            'plan_id' => $request->plan_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'logo_url' => $request->logo_url,
        ]);

        $library->update([
            'capacity' => $request->capacity,
            'logo_url' => $request->logo_url,
        ]);

        $this->libraryUserService->syncFromLibrary($library);

        return redirect()->route('library-details.index')->with('success','Detail saved');
    }

    public function edit($id)
    {
        $detail = LibraryDetail::findOrFail($id);
        $plans = Plan::all();
        return view('admin.library_details.edit', compact('detail', 'plans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'library_id' => 'required|exists:libraries,id',
            'plan_id' => 'nullable|exists:plans,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capacity' => 'nullable|integer|min:0',
            'logo_url' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
        ]);

        $detail = LibraryDetail::findOrFail($id);
      
        if ($request->plan_id) {
            $plan = Plan::findOrFail($request->plan_id);
        }

        $detail->update([
            'library_id' => $request->library_id,
            'name' => $request->name,
            'location' => $request->location,
            'phone' => $request->phone,
            'plan_id' => $request->plan_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'capacity' => $request->capacity,
            'logo_url' => $request->logo_url,
        ]);

        $library = Library::find($request->library_id);
        if ($library) {
            $library->update([
                'name' => $request->name,
                'address' => $request->location,
                'phone' => $request->phone,
                'capacity' => $request->capacity,
                'logo_url' => $request->logo_url,
            ]);
            $this->libraryUserService->syncFromLibrary($library);
        }

        return redirect()->route('library-details.index')->with('success','Detail updated');
    }

    public function destroy($id)
    {
        $detail = LibraryDetail::findOrFail($id);
        $detail->delete();
        return redirect()->back()->with('success','Deleted');
    }

    // AJAX search for libraries
    public function searchLibraries(Request $request)
    {
        $q = $request->query('q');
        $items = Library::where(function ($query) use ($q) {
                $query->where('name', 'like', '%'.($q ?? '').'%')
                      ->orWhere('location', 'like', '%'.($q ?? '').'%')
                      ->orWhere('phone', 'like', '%'.($q ?? '').'%')
                      ->orWhere('descriptions', 'like', '%'.($q ?? '').'%');
            })
            ->select('id','name','location','phone')
            ->limit(20)
            ->get();
        return response()->json($items);
    }
}
