<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notes\StoreRequest;
use App\Http\Requests\Notes\UpdateRequest;
use App\Models\Department;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        activity('PROJECT Notes')
            ->log("Accessed the Project Notes")->causer(request()->user());
        $notes = Note::orderBy('id','desc')->get();;
        return view('notes.index')->with([
            'cpage' => "notes",
            'notes'=>$notes
        ]);
    }
    public function create()
    {
        $departments = Department::all();
        return view('notes.create')->with([
            'cpage'=>"notes",
            'departments' => $departments,
        ]);
    }
    public function store(StoreRequest $request)
    {
        $data = $request->post();
        Note::create($data);
        activity('PROJECT NOTES')
            ->log("Created Project Notes")->causer(request()->user());
        return redirect()->route('notes.index')->with([
            'success-notification'=>"a Note successfully Created"
        ]);
    }
    public function show(Note $note)
    {
        return view('notes.show')->with([
            'cpage'=>"budgets",
            'note'=>$note,
        ]);
    }
    public function edit( Note $note)
    {
        return view('notes.edit')->with([
            'cpage' => "notes",
            'note' => $note,
        ]);
    }
    public function update(UpdateRequest $request, Note $note)
    {
        $data = $request->post();

        $note->update($data);
        activity('PROJECT NOTES')
            ->log("Updated Project Notes")->causer(request()->user());
        return redirect()->route('notes.show',$note->id)->with([
            'success-notification'=>"a Note successfully Updated"
        ]);
    }
    public function destroy(Note $note)
    {
        try{
            $note->delete();
            activity('PROJECT NOTE')
                ->log("Deleted Project Note")->causer(request()->user());
            return redirect()->route('notes.index')->with([
                'success-notification'=>"a Note successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('notes.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
