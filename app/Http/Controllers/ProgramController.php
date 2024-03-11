<?php

namespace App\Http\Controllers;

use App\Http\Requests\Programs\StoreRequest;
use App\Http\Requests\Programs\UpdateRequest;
use App\Models\Member;
use App\Models\Month;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
        public function destroy(Program  $program)
    {
        try{
            $program->delete();
            activity('PROGRAMS')
                ->log("Deleted a Program")->causer(request()->user());
            return redirect()->route('programs.index')->with([
                'success-notification'=>"Successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('programs.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
    public function update(UpdateRequest $request,Program $program)
    {
        $data = $request->post();
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('venue'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on venue"]);
        }
        $program->update($data);
        activity('ACCOUNTS')
            ->log("Updated a Program")->causer(request()->user());
        return redirect()->route('programs.show',$program->id)->with([
            'success-notification'=>"Account successfully Updated"
        ]);
    }
    public function edit( Program $program, MemberController $memberController)
    {
        $members = Member::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        if(request()->user()->designation=='church'){
            $members = $memberController->getHomeChurchMembers();
        }
        return view('programs.edit')->with([
            'cpage' => "programs",
            'members'=>$members,
            'church_id'=>$memberController->getHomeChurch(),
            'program' => $program
        ]);
    }
    public function show(Program $program)
    {
        return view('programs.show')->with([
            'cpage'=>"programs",
            'program'=>$program,
        ]);
    }
    public function store(StoreRequest $request)
    {
        if(is_numeric($request->post('name'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on Name"]);
        }
        if(is_numeric($request->post('venue'))){
            return back()->with(['error-notification'=>"Invalid Character Entered on venue"]);
        }
        $data = $request->post();
        Program::create($data);
        activity('ACCOUNTS')
            ->log("Created a new Program")->causer(request()->user());
        return redirect()->route('programs.index')->with([
            'success-notification'=>"Successfully Created"
        ]);
    }
    public function create(MemberController $memberController)
    {
        $members = Member::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        if(request()->user()->designation=='church'){
            $members = $memberController->getHomeChurchMembers();
        }
        return view('programs.create')->with([
            'cpage'=>"programs",
            'church_id'=>$memberController->getHomeChurch(),
            'members'=>$members,
        ]);
    }
    public function index(MemberController $memberController)
    {
        if(Month::getActiveMonth()){
            $month =Month::getActiveMonth();
        }else{
            return redirect()->route('months.index')->with([
                'success-notification'=>"Please Create a new month"
            ]);
        }

        $programs = Program::where(['soft_delete'=>0])
                            ->whereBetween('created_at',[$month->start_date,$month->end_date])
                            ->orderBy('id','desc')->get();

        if(request()->user()->designation=='church' || request()->user()->designation=='member'){
            $programs = Program::where(['church_id'=>$memberController->getHomeChurch()])
                ->where(['soft_delete'=>0])
                ->whereBetween('created_at',[$month->start_date,$month->end_date])
                ->orderBy('id','desc')
                ->get();
        }

        activity('programs')
            ->log("Accessed programs listing")->causer(request()->user());
        return view('programs.index')->with([
            'cpage' => "programs",
            'months'=>Month::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'start_date'=>$month->start_date,
            'end_date'=>$month->end_date,
            'programs'=>$programs,
        ]);
    }
    public function generateAttendanceReport(Request $request, MemberController $memberController)

    {
        $request->validate([
            'month_id' => "required|numeric",
        ]);
        $month = Month::where(['id' => $request->post('month_id')])->first();

        $programs = Program::where(['soft_delete'=>0])
            ->whereBetween('created_at',[$month->start_date,$month->end_date])
            ->orderBy('id','desc')->get();

        if(request()->user()->designation=='church' || request()->user()->designation=='member'){
            $programs = Program::where(['church_id'=>$memberController->getHomeChurch()])
                ->where(['soft_delete'=>0])
                ->whereBetween('created_at',[$month->start_date,$month->end_date])
                ->orderBy('id','desc')
                ->get();
        }
        return view('programs.index')->with([
            'months' => Month::where(['soft_delete'=>0])->orderBy('id','desc')->get(),
            'programs'=>$programs,
            'start_date'=>$month->start_date,
            'end_date'=>$month->end_date,
            'cpage' => 'programs'
        ]);
    }
}
