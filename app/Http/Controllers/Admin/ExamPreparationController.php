<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ExamPreparation;
use Illuminate\Http\Request;

class ExamPreparationController extends Controller
{
    /**
     * Display all exam preparations
     */
    public function index()
    {
        $examPreparations = ExamPreparation::latest()->get();
        return view('admin.exam_preparations.index', compact('examPreparations'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.exam_preparations.create');
    }

    /**
     * Store new record
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        ExamPreparation::create([
            'title' => $request->title,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('exam_preparations.index')
            ->with('success', 'Exam Preparation added successfully');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $examPreparation = ExamPreparation::findOrFail($id);
        return view('admin.exam_preparations.edit', compact('examPreparation'));
    }

    /**
     * Update record
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $examPreparation = ExamPreparation::findOrFail($id);

        $examPreparation->update([
            'title' => $request->title,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('exam_preparations.index')
            ->with('success', 'Exam Preparation updated successfully');
    }

    /**
     * Delete record
     */
    public function destroy($id)
    {
        $examPreparation = ExamPreparation::findOrFail($id);
        $examPreparation->delete();

        return redirect()->route('exam_preparations.index')
            ->with('success', 'Exam Preparation deleted successfully');
    }
}