<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Plan;
use App\Models\Library;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ConfigController extends Controller
{
    public function getShifts(Request $request)
    {
        $shifts = Shift::where('library_id', $request->user()->library_id)->get();
        return response()->json(['status' => 'success', 'data' => $shifts]);
    }

    public function getPlans(Request $request)
    {
        $plans = \App\Models\LibraryPlan::get();
        return response()->json(['status' => 'success', 'data' => $plans]);
    }

    public function getPlan($id)
    {
        $plan = \App\Models\LibraryPlan::find($id);

        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan not found.'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $plan]);
    }

    public function saveShift(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:shifts,id',
            'name' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $libraryId = $request->user()->library_id;

        $shift = Shift::updateOrCreate(
            ['id' => $request->id, 'library_id' => $libraryId],
            [
                'library_id' => $libraryId,
                'name' => $request->name,
                'key_name' => strtolower(str_replace(' ', '', $request->name)),
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'icon' => 'light_mode',
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Shift saved successfully',
            'data' => $shift
        ]);
    }

    public function savePlan(Request $request)
    {
        $request->validate([
            'id' => 'nullable|exists:library_plans,id',
            'name' => 'required|string',
            'duration_days' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'nullable|string'
        ]);

        $plan = \App\Models\LibraryPlan::updateOrCreate(
            ['id' => $request->id],
            [
                'name' => $request->name,
                'duration' => $request->duration_days,
                'price' => $request->price,
                'description' => $request->description,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Plan saved successfully',
            'data' => $plan
        ]);
    }

    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string',
            'duration_days' => 'sometimes|required|integer',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string'
        ]);

        $plan = \App\Models\LibraryPlan::find($id);

        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan not found.'], 404);
        }

        if ($request->has('name')) $plan->name = $request->name;
        if ($request->has('duration_days')) $plan->duration = $request->duration_days;
        if ($request->has('price')) $plan->price = $request->price;
        if ($request->has('description')) $plan->description = $request->description;

        $plan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Plan updated successfully',
            'data' => $plan
        ]);
    }

    public function deletePlan($id)
    {
        $plan = \App\Models\LibraryPlan::find($id);

        if (!$plan) {
            return response()->json(['status' => 'error', 'message' => 'Plan not found.'], 404);
        }

        $plan->delete();

        return response()->json(['status' => 'success', 'message' => 'Plan deleted successfully.']);
    }

    public function getStaff(Request $request, User $user)
    {
        if ($user->library_id !== $request->user()->library_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized action.'], 403);
        }

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function listStaff(Request $request)
    {
        $staff = User::where('library_id', $request->user()->library_id)
            ->where('role', '!=', 'owner')
            ->get();
            
        return response()->json(['status' => 'success', 'data' => $staff]);
    }

    public function addStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:4',
            'role' => 'required|in:manager,receptionist'
        ]);

        $staff = User::create([
            'library_id' => $request->user()->library_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Staff added successfully',
            'data' => $staff
        ], 201);
    }

    public function removeStaff(Request $request, User $user)
    {
        if ($user->library_id !== $request->user()->library_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized action.'], 403);
        }

        $user->delete();
        return response()->json(['status' => 'success', 'message' => 'Staff member removed.']);
    }
}
