<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\LibraryDetail;
use App\Models\Plan;
use App\Services\LibraryUserService;
use Illuminate\Http\Request;

class LibraryDetailController extends Controller
{
    public function __construct(private LibraryUserService $libraryUserService)
    {
    }

    public function searchLibraries(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:100',
        ]);

        $q = trim((string) $request->query('q', ''));

        $libraries = Library::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'like', '%'.$q.'%')
                        ->orWhere('location', 'like', '%'.$q.'%')
                        ->orWhere('phone', 'like', '%'.$q.'%')
                        ->orWhere('descriptions', 'like', '%'.$q.'%');
                });
            })
            ->select('id', 'name', 'location', 'phone', 'price', 'rating', 'open_time', 'close_time', 'status')
            ->latest()
            ->limit(20)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $libraries,
        ]);
    }

    public function plans()
    {
        return response()->json([
            'status' => 'success',
            'data' => Plan::select('id', 'name', 'amount', 'validity', 'whats_text_limit', 'text_limit')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'library_id' => 'required|exists:libraries,id',
            'plan_id' => 'nullable|exists:plans,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $library = Library::findOrFail($request->library_id);

        $detail = LibraryDetail::create([
            'library_id' => $library->id,
            'name' => $library->name,
            'location' => $library->location,
            'phone' => $library->phone,
            'plan_id' => $request->plan_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        $credentials = $this->libraryUserService->syncFromLibrary($library);

        return response()->json([
            'status' => 'success',
            'message' => 'Library detail submitted and login credentials generated.',
            'data' => [
                'library_detail' => $detail->load('library', 'plan'),
                'login' => [
                    'phone' => $credentials['user']->phone,
                    'default_password' => $credentials['default_password'],
                    'password_change_required' => $credentials['user']->password_change_required,
                ],
            ],
        ], 201);
    }

    public function myDetail(Request $request)
    {
        $user = $request->user();

        $detail = LibraryDetail::with('library', 'plan')
            ->where('phone', $user->phone)
            ->latest()
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user,
                'library_detail' => $detail,
            ],
        ]);
    }
}
