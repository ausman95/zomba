<?php

namespace App\Http\Controllers;

use App\Models\Position; // Don't forget to import your Position model
use App\Models\User;     // Assuming 'created_by' and 'updated_by' link to a User model
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // For unique validation with ignore

class PositionController extends Controller
{
    /**
     * Display a listing of the positions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        activity('Positions')->log('Accessed Positions list.')->causer(request()->user());

        $query = Position::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where('name', 'like', $searchTerm);
        }

        // --- CRITICAL CHANGE: Use withTrashed() if SoftDeletes trait is active ---
        $positions = $query->withTrashed() // Include soft-deleted records in the results
        ->with(['creator', 'updater'])
            ->withCount('members')
             ->where('soft_delete', 0) // You can remove this line entirely if you rely on SoftDeletes
            ->orderBy('name', 'asc')
            ->paginate(15);

        $cpage = 'members';
        return view('positions.index', compact('positions', 'cpage'));
    }

    /**
     * Show the form for creating a new position.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Pass 'cpage' for sidebar/navigation highlighting
        $cpage = 'members';

        activity('Positions')->log('Accessed create position form.')->causer(request()->user());

        return view('positions.create', compact('cpage'));
    }

    /**
     * Store a newly created position in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:positions,name,NULL,id,soft_delete,0', // Unique name, ignore soft-deleted
        ]);

        $position = Position::create([
            'name'       => $request->input('name'),
            'created_by' => auth()->id(), // Assign current authenticated user as creator
            'soft_delete' => 0,          // Ensure it's active by default
        ]);

        activity('Positions')->log("Created new position: {$position->name}.")->causer(request()->user());

        return redirect()->route('positions.index')->with('success-notification', 'Position created successfully!');
    }

    /**
     * Display the specified position.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\View\View
     */
    public function show(Position $position)
    {
        // Optional: Ensure the position is active if you don't want to show deleted ones
        // If you're using SoftDeletes trait, you might also check $position->trashed()
        if ($position->soft_delete) {
            abort(404, 'Position not found.'); // Or redirect to index with error
        }

        // Eager load creator and updater for display on the position's details table
        $position->load(['creator', 'updater']);

        // Fetch members associated with this position
        // Make sure the 'members' relationship is defined in your Position model (as discussed before)
        // Ensure 'department' and 'position' relationships are defined in your Member model
        $members = $position->members()->with(['position'])->paginate(10);
        // You can change paginate(10) to get() if you don't want pagination for members on this page
        // $members = $position->members()->with(['department', 'position'])->get();


        // Pass 'cpage' for sidebar/navigation highlighting
        $cpage = 'members';

        activity('Positions')->log("Viewed details for position: {$position->name}.")->causer(request()->user());

        // --- CRUCIAL: Pass the $members variable to the view ---
        return view('positions.show', compact('position', 'members', 'cpage'));
    }

    /**
     * Show the form for editing the specified position.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\View\View
     */
    public function edit(Position $position)
    {
        // Prevent editing soft-deleted positions
        if ($position->soft_delete) {
            return redirect()->route('positions.index')->with('error', 'Cannot edit a deleted position.');
        }

        // Pass 'cpage' for sidebar/navigation highlighting
        $cpage = 'members';

        activity('Positions')->log("Accessed edit form for position: {$position->name}.")->causer(request()->user());

        return view('positions.edit', compact('position', 'cpage'));
    }

    /**
     * Update the specified position in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Position $position)
    {
        // Prevent updating soft-deleted positions
        if ($position->soft_delete) {
            return redirect()->route('positions.index')->with('error', 'Cannot update a deleted position.');
        }

        $request->validate([
            // Ensure unique name, but ignore the current position's ID
            'name' => ['required', 'string', 'max:255', Rule::unique('positions')->ignore($position->id)->where(function ($query) {
                $query->where('soft_delete', 0); // Still unique among active positions
            })],
        ]);

        $position->update([
            'name'       => $request->input('name'),
            'updated_by' => auth()->id(), // Assign current authenticated user as updater
        ]);

        activity('Positions')->log("Updated position: {$position->name}.")->causer(request()->user());

        return redirect()->route('positions.index')->with('success-notification', 'Position updated successfully!');
    }

    /**
     * Remove the specified position from storage (soft delete).
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Position $position)
    {
        // Prevent deleting an already soft-deleted position again
        if ($position->soft_delete) {
            return redirect()->route('positions.index')->with('error-notification', 'Position is already deleted.');
        }

        // Soft delete the position
        $position->update([
            'soft_delete' => 1,          // Set soft_delete flag to 1
            'deleted_at'  => now(),      // Record deletion timestamp
            'updated_by'  => auth()->id(), // Record who performed the deletion
        ]);
        // Or simply: $position->delete(); if you rely on the SoftDeletes trait to handle flags/timestamps automatically
        // However, explicitly setting soft_delete = 1 might be useful if your view filters by it

        activity('Positions')->log("Soft-deleted position: {$position->name}.")->causer(request()->user());

        return redirect()->route('positions.index')->with('success', 'Position deleted successfully!');
    }

    /**
     * Restore a soft-deleted position.
     * This function is optional but good for soft deletes.
     *
     * @param int $id The ID of the position to restore.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $position = Position::withTrashed()->find($id); // Find even soft-deleted positions

        if (!$position) {
            return redirect()->route('positions.index')->with('error', 'Position not found.');
        }

        if (!$position->soft_delete) {
            return redirect()->route('positions.index')->with('info', 'Position is not deleted.');
        }

        // Restore the position
        $position->update([
            'soft_delete' => 0,          // Set soft_delete flag back to 0
            'deleted_at'  => null,       // Clear deletion timestamp
            'updated_by'  => auth()->id(), // Record who performed the restoration
        ]);
        // Or simply: $position->restore(); if you rely on the SoftDeletes trait

        activity('Positions')->log("Restored position: {$position->name}.")->causer(request()->user());

        return redirect()->route('positions.index')->with('success', 'Position restored successfully!');
    }


    /**
     * Permanently delete a position.
     * Use with caution. Only if truly needed.
     *
     * @param int $id The ID of the position to force delete.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete($id)
    {
        $position = Position::withTrashed()->find($id);

        if (!$position) {
            return redirect()->route('positions.index')->with('error', 'Position not found.');
        }

        $positionName = $position->name;
        $position->forceDelete(); // Permanently delete

        activity('Positions')->log("Permanently deleted position: {$positionName}.")->causer(request()->user());

        return redirect()->route('positions.index')->with('success', 'Position permanently deleted!');
    }
}
