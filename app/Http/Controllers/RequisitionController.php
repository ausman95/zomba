<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentRequisition;
use App\Models\DepartmentRequisitionItem;
use App\Models\Material;
use App\Models\Message;
use App\Models\Project;
use App\Models\order;
use App\Models\Requisition;
use App\Models\RequisitionApproval;
use App\Models\RequisitionDepartmentApproval;
use App\Models\RequisitionItem;
use App\Models\User;
use App\Models\Voucher;
use App\Notifications\ContractNofication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    public function sendSms($number, $message)
    {
        $endpoint = "https://api.smsdeliveryapi.xyz/send";

        $ch = curl_init();
        $array_post = http_build_query(array(
            'text' => $message,
            'numbers' => $number,
            'api_key' => 'eHTIUfunQ4UgDMQKtblY',
            'password' => '@asakala1',
            'from' => 'Sico Civils'
        ));

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array_post);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        return $server_output;
    }
    public function pending()
    {
        activity('REQUISITIONS')
            ->log("Accessed Requisitions")->causer(request()->user());
        return view('requisitions.pending')->with([
            'cpage' => "requisitions",
            'requisitions' => DepartmentRequisition::orderBy('updated_at', 'desc')->get(),
        ]);
    }
    public function archive()
    {
        activity('REQUISITIONS')
            ->log("Accessed Requisitions")->causer(request()->user());
        return view('requisitions.archive')->with([
            'cpage' => "requisitions",
            'requisitions' => DepartmentRequisition::orderBy('updated_at', 'desc')->get(),
        ]);
    }
//    public function show()
//    {
//
//    }

    public function index()
    {
        activity('REQUISITIONS')
            ->log("Accessed Requisitions")->causer(request()->user());
        if (request()->user()->designation === 'clerk') {
//            $request1 = Requisition::orderBy('created_at', 'desc')
//                ->get();
            $request2 = DepartmentRequisition::where(['department_id' => request()->user()->department_id])->orderBy('id', 'desc')->get();
        } else {
//            $request1 = Requisition::orderBy('created_at', 'desc')
//                ->get();
            $request2 = DepartmentRequisition::orderBy('updated_at', 'desc')->get();
        }
        return view('requisitions.index')->with([
            'cpage' => "requisitions",
            'dept_requisitions' => $request2,
        ]);
    }

    public function projectRequisitions(Project $project)
    {
        activity('REQUISITIONS')
            ->log("Accessed Requisitions")->causer(request()->user());

        return view('requisitions.project')->with([
            'cpage' => "projects",
            'project' => $project,
            'requisitions' => $project->requisitions
        ]);
    }

    public function determineNext(Request $request)
    {

        $data = $request->all();
        $request->validate([
            'department_id' => "required",
        ]);
        $department_name = 'Others';
        $department_id = $data['department_id'];
        return redirect(
            route('requisitions.create') . "?department={$department_name}&department_id={$department_id}");
    }

    public function create(Project $project)
    {

        $requisition_list = \request()->session()->get('requisition_items');


        if (empty($requisition_list)) {
            $requisition_list = [];
        }

        return view('requisitions.create')->with([
            'project' => $project,
            'materials' => Material::all(),
            'cpage' => "projects",
            'requisition_list' => $requisition_list
        ]);
    }

    public function update(Request $request, DepartmentRequisitionItem $account)
    {
        $data = $request->post();
        DB::table('department_requisition_items')
            ->where(['id' => $request->post('request_id')])
            ->update(['quantity' => $request->post('quantity')]);
        $account->update($data);

        return redirect()->route('requisitions.check', $request->post('id'))->with([
            'success-notification' => "Successfully Updated"
        ]);
    }

    public function approveRequest(Request $request, DepartmentRequisition $account)
    {
        $data = $request->post();
        $status = $request->post('status');
        $new_status = 'in-order';
        if ($status == 'in-order') {
            $new_status = 'closed';
            $message = 'REQUEST FOR REQUISITION' . PHP_EOL . 'There is a new Requisition that you need to Generate a Voucher and buy Items' . PHP_EOL . '
        Log in to check' . PHP_EOL . 'https://sicocivils.marcsystems.africa/login' . PHP_EOL . ' Sico Smart Digital App';
            $columns = User::where(['designation' => 'accountant'])->get();
            foreach ($columns as $column) {
                $data = [
                    'user_id' => $column->id,
                    'sms_date' => date('Y-m-d'),
                    'body' => $message
                ];
                $this->sendSms($column->phone_number, $message);
               $column->notify(new ContractNofication($message));
            }
            Message::create($data);
        }
        $requisition = [
            'requisition_id' => $request->post('id'),
        ];
        Voucher::create($requisition);
        DB::table('department_requisitions')
            ->where(['id' => $request->post('id')])
            ->update([
                'status' => $new_status,
                'updated_at'=>now()
            ]);
        $account->update($data);
        $notes = @$request->post('notes');
        if (empty($notes)) {
            $notes = ' Checked and Approved';
        }
        if ($account) {
            RequisitionDepartmentApproval::create([
                'user_id' => request()->user()->id,
                'status' => $new_status,
                'department_requisition_id' => $request->post('id'),
                'notes' => $notes
            ]);
        }
        if ($new_status == 'in-order') {
            $message = 'REQUEST FOR REQUISITION' . PHP_EOL . 'There is a new Requisition that needs Your attention' . PHP_EOL . '
        Log in to check' . PHP_EOL . 'https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'REF : ' . $request->post('id') . $new_status . PHP_EOL . ' Sico Smart Digital App';
            $columns = User::where(['level' => 3])->get();
            foreach ($columns as $column) {
                $data = [
                    'user_id' => $column->id,
                    'sms_date' => date('Y-m-d'),
                    'body' => $message
                ];
                $this->sendSms($column->phone_number, $message);
               $column->notify(new ContractNofication($message));
            }
            Message::create($data);
        }
        activity('REQUISITIONS')
            ->log("Approved a  Requisition")->causer(request()->user());

        return redirect()->route('requisitions.check', $request->post('id'))->with([
            'success-notification' => "Successfully Approved"
        ]);
    }

    public function approveProjectRequest(Request $request, Requisition $account)
    {
        $data = $request->post();
        $status = $request->post('status');
        $new_status = 'in-order';
        if ($status == 'in-order') {
            $new_status = 'closed';
            $message = 'REQUEST FOR REQUISITION' . PHP_EOL . 'There is a new Requisition that you need to Generate a Voucher and buy Items' . PHP_EOL . '
        Log in to check' . PHP_EOL . 'https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'REF : ' . $request->post('id') . $new_status . PHP_EOL . ' Sico Smart Digital App';
            $columns = User::where(['designation' => 'accountant'])->get();
            foreach ($columns as $column) {
                $data = [
                    'user_id' => $column->id,
                    'sms_date' => date('Y-m-d'),
                    'body' => $message
                ];
                $sms = $this->sendSms($column->phone_number, $message);
               $column->notify(new ContractNofication($message));
            }
        }
        DB::table('requisitions')
            ->where(['id' => $request->post('id')])
            ->update(['status' => $new_status]);
        $account->update($data);
        if ($account) {
            RequisitionApproval::create([
                'user_id' => request()->user()->id,
                'status' => $new_status,
                'requisition_id' => $request->post('id')
            ]);
        }
        if ($new_status == 'in-order') {
            $message = 'REQUEST FOR REQUISITION' . PHP_EOL . 'There is a new Requisition that needs Your attention' . PHP_EOL . '
        Log in to check' . PHP_EOL . 'https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'REF : ' . $request->post('id') . $new_status . PHP_EOL . ' Sico Smart Digital App';
            $columns = User::where(['designation' => 'administrator'])->get();
            foreach ($columns as $column) {
                $data = [
                    'user_id' => $column->id,
                    'sms_date' => date('Y-m-d'),
                    'body' => $message
                ];
                if (!Message::where($data)->first()) {
                    $sms = $this->sendSms($column->phone_number, $message);
                    $tuple = json_decode($sms);
                    if ($tuple->type == 'success') {
                        //The batch was received for sending
                        Message::create($data);
                    }
                    $column->notify(new ContractNofication($message));
                }
            }
        }

        activity('REQUISITIONS')
            ->log("Approved a  Requisition")->causer(request()->user());
        return redirect()->route('requisitions.show', $request->post('id'))->with([
            'success-notification' => "Successfully Approved"
        ]);
    }

    public function updateRequest(Request $request, RequisitionItem $account)
    {
        $data = $request->post();
        DB::table('requisition_items')
            ->where(['id' => $request->post('request_id')])
            ->update(['quantity' => $request->post('quantity')]);
        $account->update($data);
        activity('REQUISITIONS')
            ->log("Updated Quantity on a  Requisition")->causer(request()->user());
        return redirect()->route('requisitions.show', $request->post('id'))->with([
            'success-notification' => "Successfully Updated"
        ]);
    }

    public function requestRequest(Request $request, DepartmentRequisition $account)
    {
        $data = $request->post();
        $new_status = 'pending';
        $message = 'REQUEST FOR REQUISITION' . PHP_EOL . 'There is a new Requisition that you need to Generate a Voucher and buy Items' . PHP_EOL . '
        Log in to check' . PHP_EOL . 'https://sicocivils.marcsystems.africa/login' . PHP_EOL . ' Sico Smart Digital App';
        $columns = User::where(['level' => 2])->get();
        foreach ($columns as $column) {
            $data = [
                'user_id' => $column->id,
                'sms_date' => date('Y-m-d'),
                'body' => $message
            ];
            $this->sendSms($column->phone_number, $message);
            $column->notify(new ContractNofication($message));
        }
        Message::create($data);
        DB::table('department_requisitions')
            ->where(['id' => $request->post('id')])
            ->update([
                'status' => $new_status,
                'updated_at'=>now()]);
        $account->update($data);
        RequisitionDepartmentApproval::create([
            'user_id' => request()->user()->id,
            'status' => $new_status,
            'department_requisition_id' => $request->post('id'),
            'notes' => ' Requested this Requisition AGAIN'
        ]);

        activity('REQUISITIONS')
            ->log("Requested a  Requisition")->causer(request()->user());

        return redirect()->route('requisitions.check', $request->post('id'))->with([
            'success-notification' => "Successfully Approved"
        ]);
    }
    public function acknowledgeRequest(Request $request, DepartmentRequisition $account)
    {
        $data = $request->post();
        $new_status = 'acknowledged';

        DB::table('department_requisitions')
            ->where(['id' => $request->post('id')])
            ->update(['status' => $new_status]);
        $account->update($data);
        RequisitionDepartmentApproval::create([
            'user_id' => request()->user()->id,
            'status' => $new_status,
            'department_requisition_id' => $request->post('id'),
            'notes' => 'Acknowledged and Closed'
        ]);

        activity('REQUISITIONS')
            ->log("Acknowledged a  Requisition")->causer(request()->user());

        return redirect()->route('requisitions.check', $request->post('id'))->with([
            'success-notification' => "Successfully Approved"
        ]);
    }

    public function determine()
    {
        $project = Project::all();
        return view('requisitions.determine')->with([
            'projects' => $project,
            'departments' => Department::all(),
            'cpage' => "requisitions",
        ]);
    }


    public function addItemToList(Material $material, Request $request)
    {
        activity('REQUISITIONS')
            ->log("Adding items on a Requisition")->causer(request()->user());
        //die($request->post('type'));
        if ($request->post('type') == 1) {
            $request->validate([
                'material_id' => "required|numeric",
                'reason' => "required|string",
                'personale' => "required|string",
                'quantity' => "required|numeric|min:1"
            ]);
        } else {
            $request->validate([
                'description' => "required|string",
                'reason' => "required|string",
                'personale' => "required|string",
                'amount' => "required|min:1"
            ]);
        }

        $items = $request->session()->get('requisition_items');


        if (empty($items)) {
            $items = [];
        }


        if ($request->post('type') == 1) {
            $materials = Material::find($request->post('material_id'));

            $item_exists = collect($items)->firstWhere('material_id', $materials->id);

            if ($item_exists) {
                return back()->with(['error-notification' => "Item is already in list"]);
            }
            $material_name = $materials->name . ' ' . $request->post('quantity') . ' ' . $materials->units . ' of ' . $materials->specifications;
            $amount = $request->post('quantity') * $material->getPrice($request->post('material_id'));
        } else {
            $material_name = $request->post('description');
            $amount = $request->post('amount');
        }
        $items[] = [
            'material_name' => $material_name,
            'amount' => $amount,
            'reason' => $request->post('reason'),
            'personale' => $request->post('personale'),
        ];

        $request->session()->put('requisition_items', $items);

        return back()->with(['success-notification' => "Item added to list"]);
    }

    public function removeItemFromList($item_id)
    {
        activity('REQUISITIONS')
            ->log("Removing items on a  Requisition")->causer(request()->user());
        $items = request()->session()->get('requisition_items');


        if (empty($items)) {
            $items = [];
        }


        $items_filtered = [];

        foreach ($items as $item) {
            if ($item['material_name'] === $item_id) {
                continue;
            }

            $items_filtered[] = $item;
        }

        request()->session()->put('requisition_items', $items_filtered);

        return back()->with(['success-notification' => "Item removed from list"]);
    }

    public function store(Project $project)
    {
        //dd('We are here');
        $items = request()->session()->get('requisition_items');

        if (empty($items)) {
            $items = [];
        }

        if (count($items) === 0) {
            return back()->with(['error-notification' => "Can not save a requisition with an empty list"]);
        }

        $requisition = DepartmentRequisition::create([
            'user_id' => request()->user()->id,
            'department_id' => $_GET['department_id']
        ]);
        //dd(request()->session()->get('requisition_items'));
        foreach ($items as $item) {
            DepartmentRequisitionItem::create([
                'description' => $item['material_name'],
                'amount' => $item['amount'],
                'reason' => $item['reason'],
                'personale' => $item['personale'],
                'department_requisition_id' => $requisition->id
            ]);
        }
        $message = 'REQUEST FOR REQUISITION' . PHP_EOL . 'There is a new Requisition that needs Your attention' . PHP_EOL . '
        Log in to check' . PHP_EOL . 'https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'REF : ' . $requisition->id . 'Pending' . PHP_EOL . ' Sico Smart Digital App';
        $columns = User::where(['level' => 2])->get();
        foreach ($columns as $column) {
            $data = [
                'user_id' => $column->id,
                'sms_date' => date('Y-m-d'),
                'body' => $message
            ];
            $this->sendSms($column->phone_number, $message);
           $column->notify(new ContractNofication($message));
        }
        Message::create($data);
        activity('REQUISITIONS')
            ->log("Created a  Requisition")->causer(request()->user());
        request()->session()->put("requisition_items", []);

        return redirect()->route('requisitions.index', $project->id)->with(['success-notification' => "Requisition successfully sent"]);
    }

    public function showRequisition(Requisition $requisition)
    {

        return view('requisitions.show')->with([
            'cpage' => "projects",
            'project' => $requisition->project,
            'requisition' => $requisition
        ]);
    }

    public function showDepartmentRequisition(DepartmentRequisition $requisition)
    {

        return view('requisitions.department')->with([
            'cpage' => "departments",
            'department' => $requisition->department,
            'requisition' => $requisition,
            'notes' => $requisition->notes
        ]);
    }

    public function removeRequisition(Request $request, Requisition $account)
    {
        $data = $request->post();
        DB::table('requisitions')
            ->where(['id' => $request->post('id')])
            ->update(['status' => 'Cancelled']);
        $account->update($data);
        activity('REQUISITIONS')
            ->log("Cancelled a  Requisition")->causer(request()->user());
        return redirect()->route('requisitions.show', $request->post('id'))->with([
            'success-notification' => "Successfully Cancelled"
        ]);
    }

    public function removeDepartmentRequisition(Request $request, DepartmentRequisition $account)
    {
        $data = $request->post();
        DB::table('department_requisitions')
            ->where(['id' => $request->post('id')])
            ->update([
                'status' => 'Cancelled',
                'updated_at'=>now()
            ]);
        $account->update($data);
        $notes = @$request->post('notes');
        if (empty($notes)) {
            $notes = ' Checked and Cancelled';
        }
        RequisitionDepartmentApproval::create([
            'user_id' => request()->user()->id,
            'status' => 'Cancelled',
            'department_requisition_id' => $request->post('id'),
            'notes' => $notes
        ]);

        activity('REQUISITIONS')
            ->log("Cancelled a  Requisition")->causer(request()->user());
        return redirect()->route('requisitions.check', $request->post('id'))->with([
            'success-notification' => "Successfully Cancelled"
        ]);
    }

    public function removeRequisitionItem(RequisitionItem $requisitionItem)
    {
        $requisitionItem->delete();
        activity('REQUISITIONS')
            ->log("Deleted a  Requisition")->causer(request()->user());
        return back()->with(['success-notification' => "Item successfully removed"]);

    }

    public function removeDepartmentRequisitionItem(DepartmentRequisitionItem $requisitionItem)
    {
        $requisitionItem->delete();
        activity('REQUISITIONS')
            ->log("Deleted a  Requisition")->causer(request()->user());
        return back()->with(['success-notification' => "Item successfully removed"]);

    }
}
