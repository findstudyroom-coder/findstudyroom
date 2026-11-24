<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Library;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['library', 'activeMembership.seat', 'activeMembership.plan'])->latest();

        if ($request->filled('library_id')) {
            $query->where('library_id', $request->library_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('parent_phone', 'like', "%{$s}%");
            });
        }

        $students = $query->paginate(20)->withQueryString();
        $libraries = Library::orderBy('name')->get();

        return view('admin.students.index', compact('students', 'libraries'));
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->back()->with('success', 'Student deleted successfully.');
    }
}
