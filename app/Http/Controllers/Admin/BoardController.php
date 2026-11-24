<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Board;


class BoardController extends Controller
{
    /**
     * Display a listing of boards
     */
    public function index()
    {
        $boards = Board::latest()->get();
        return view('admin.boards.index', compact('boards'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.boards.create');
    }

    /**
     * Store new board
     */
    public function store(Request $request)
    {
        $request->validate([
            'board_name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Board::create([
            'board_name' => $request->board_name,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('boards.index')
            ->with('success', 'Board added successfully');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $board = Board::findOrFail($id);
        return view('admin.boards.edit', compact('board'));
    }

    /**
     * Update board
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'board_name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $board = Board::findOrFail($id);

        $board->update([
            'board_name' => $request->board_name,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('boards.index')
            ->with('success', 'Board updated successfully');
    }

    /**
     * Delete board
     */
    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        return redirect()->route('boards.index')
            ->with('success', 'Board deleted successfully');
    }
}