<?php

namespace App\Http\Controllers;

use App\Http\Requests\Projects\StoreRequest;
use App\Http\Requests\Projects\UpdateRequest;
use App\Models\Client;
use App\Models\Incomes;
use App\Models\Labourer;
use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        activity('PROJECTS')
            ->log("Accessed Projects")->causer(request()->user());
        $projects = Project::orderBy('id','desc')->get();
        return view('projects.index')->with([
            'cpage' => "projects",
            'projects' => $projects,
        ]);
    }

    public function create()
    {
        $clients = Client::all();
        $labourers = Labourer::all();


        return view('projects.create')->with([
            'cpage' => "projects",
            'clients' => $clients,
            'labourers' => $labourers
        ]);
    }


    public function show(Project $project)
    {
        $labourers_allocs = $project->labourers;
        $incomes = $project->payments;
        //dd($incomes);
        return view('projects.show')->with([
            'cpage' => "projects",
            'project' => $project,
            'labourers' =>$labourers_allocs,
            'incomes' =>$incomes
        ]);
    }

    public function edit(Project $project)
    {
        $clients = Client::all();
        $labourers = Labourer::all();


        return view('projects.edit')->with([
            'cpage' => "projects",
            'project' => $project,
            'clients' => $clients,
            'labourers' => $labourers
        ]);
    }


    public function store(StoreRequest $request)
    {
        $data = $request->post();
        Project::create($data);
        activity('PROJECTS')
            ->log("Created a Project")->causer(request()->user());
        return redirect()->route('projects.index')->with([
            'success-notification' => "Project successfully created!"
        ]);
    }

    public function update(UpdateRequest $request, Project $project)
    {
        $data = $request->post();

        $project->update($data);
        activity('PROJECTS')
            ->log("Updated a Project")->causer(request()->user());
        return redirect()->route('projects.show', $project->id)->with([
            'success-notification' => "Project successfully updated!"
        ]);
    }


    public function destroy(Project $project)
    {
        try {
            $project->delete();
            activity('PROJECTS')
                ->log("Deleted a Project")->causer(request()->user());
            return redirect()->route('projects.index')->with([
                'success-notification' => "Project successfully deleted!"
            ]);

        } catch (\Exception $exception) {
            return redirect()->route('projects.index')->with([
                'error-notification' => "Something went wrong:" . $exception->getMessage()
            ]);
        }
    }


}
