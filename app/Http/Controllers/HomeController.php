<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AssetService;
use App\Models\Banks;
use App\Models\Church;
use App\Models\Contract;
use App\Models\DepartmentRequisition;
use App\Models\Labourer;
use App\Models\LogActivity;
use App\Models\Material;
use App\Models\Member;
use App\Models\Message;
use App\Models\Requisition;
use App\Models\StockFlow;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Article;
use App\Models\Project;

use App\Notifications\ContractNofication;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\Helper;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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

    public function getContractDue()
    {
        $contracts = Contract::all();

        if ($contracts->count() == 0) {
            return False;
        }
        foreach ($contracts as $contract) {
            $start_date = now();
            $end_date = $contract->end_date;
            $labour_name = $contract->labourer->name;
            $labour_number = $contract->labourer->phone_number;

            $start_date = Carbon::parse($start_date);
            $end_date = Carbon::parse($end_date);
            $due = $end_date->diffInDays($start_date);

            $messages = 'EXPIRE OF CONTRACTS' . PHP_EOL . 'Your Contract, Mr ' . $labour_name . " will expire in " . $due . ' Days and on ' . $end_date . PHP_EOL . 'From Sico Smart Digital App' . PHP_EOL;
            $message = 'EXPIRE OF CONTRACTS' . PHP_EOL . 'The Contract of Mr ' . $labour_name . " will expire in " . $due . ' Days and on ' . $end_date . PHP_EOL . 'Log in to take action https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'From Sico Smart Digital App' . PHP_EOL;

            $users = User::where(['designation' => 'hr'])->get();

            $period = 0;

            if ($due > 30 && $due <= 60) {
                $period = 1;
            }
            if (($due > 15) && ($due <= 30)) {
                $period = 2;
            }
            if (($due > 0) && ($due <= 15)) {
                $period = 3;
            }
            if (($due <= 0)) {
                $period = 4;
                $messages = 'EXPIRE OF CONTRACTS' . PHP_EOL . 'Your Contract, Mr ' . $labour_name . " has Expired on " . $end_date . PHP_EOL . 'From Sico Smart Digital App' . PHP_EOL;
                $message = 'EXPIRE OF CONTRACTS' . PHP_EOL . 'The Contract of Mr ' . $labour_name . ' has Expired on ' . $end_date . PHP_EOL . 'Log in to take action https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'From Sico Smart Digital App' . PHP_EOL;
            }
            @$this->sendSms($labour_number, $messages);
            foreach ($users as $user) {
                $data = [
                    'user_id' => $user->id,
                    'sms_date' => date('Y-m-d'),
                    'body' => $message,
                    'period' => $period
                ];
                $data2 = [
                    'user_id' => $user->id,
                    'sms_date' => date('Y-m-d'),
                    'period' => $period
                ];

                if (Message::where($data2)->first()) {
                    // labourer is already part of this project
                    return false;
                } else {
                    @$sms = $this->sendSms($user->phone_number, $message); // sending email
                    @$tuple = json_decode($sms);
                    if (@$tuple->type == 'success') {
                        //The batch was received for sending
                        Message::create($data);
                    }

                    $user->notify(new ContractNofication($message));
                }
            }
            return true;
        }

    }

    public function endContractDue()
    {

        $contracts = Contract::all();

        if ($contracts->count() == 0) {
            return False;
        }
        foreach ($contracts as $contract) {
            $start_date = date('Y-m-d');
            $end_date = $contract->end_date;

            if (($start_date >= $end_date)) {
                DB::table('contracts')
                    ->where(['id' => $contract->id])
                    ->update(['status' => 'ENDED']);
            }
        }
        return true;

    }

    public function getAssetDue()
    {
        $assets = AssetService::all();
        if ($assets->count() == 0) {
            return False;
        }
        foreach ($assets as $contract) {
            $start_date = now();
            $end_date = $contract->service_due;
            $labour_name = $contract->asset->name . ' ' . $contract->asset->serial_number . ' ' . $contract->asset->description;

            $start_date = Carbon::parse($start_date);
            $end_date = Carbon::parse($end_date);
            $due = $end_date->diffInDays($start_date);
            $due = $due . ' Days and will expire on ' . $end_date;
            $message = 'ASSETS SERVICE DUE' . PHP_EOL . 'The Asset ' . $labour_name . " will expire in " . $due . ' Days' . PHP_EOL . 'Log in to take action https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'From Sico Smart Digital App' . PHP_EOL;
            $users = User::where(['designation' => 'hr'])->get();
            if ($due > 60) {
                return false;
            }
            if ($due > 30 && $due <= 60) {
                $period = 11;
            }
            if (($due > 15) && ($due <= 30)) {
                $period = 21;
            }
            if (($due > 0) && ($due <= 15)) {
                $period = 31;
            }
            if ($due <= 0) {
                $period = 41;
                $message = 'ASSETS SERVICE DUE' . PHP_EOL . 'The Asset ' . $labour_name . " has expired on " . $end_date . PHP_EOL . 'Log in to take action https://sicocivils.marcsystems.africa/login' . PHP_EOL . 'From Sico Smart Digital App' . PHP_EOL;
            }
            foreach ($users as $user) {
                $data = [
                    'user_id' => $user->id,
                    'sms_date' => date('Y-m-d'),
                    'body' => $message,
                    'period' => $period
                ];
                $data2 = [
                    'user_id' => $user->id,
                    'sms_date' => date('Y-m-d'),
                    'period' => $period
                ];
                if (Message::where($data2)->first()) {
                    return false;
                } else {

                    @$sms = $this->sendSms($user->phone_number, $message);
                    @$tuple = json_decode($sms);
                    if (@$tuple->type == 'success') {
                        Message::create($data);
                    }
                    $user->notify(new ContractNofication($message));
                }
            }
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMaterialInStores()
    {
        $stores = StockFlow::where(['flow' => 1])
            ->groupBy('material_id', 'department_id')
            ->orderBy('id', 'desc')
            ->get();
        if (request()->user()->designation === 'clerk') {
            $stores = StockFlow::where(['department_id' => request()->user()->department_id])
                ->Where(['flow' => 1])
                ->groupBy('material_id')
                ->get();
        }
        return $stores;
    }

    public function index()
    {
//        if (request()->user()->level == 0) {
//            $this->getContractDue();
//            $this->getAssetDue();
//        }
       // $this->endContractDue();
        activity('DASHBOARD')
            ->log("Accessed the Home page")->causer(request()->user());
        $analytics = [
            'new_messages' => 0,
            'users' => 0,
            'projects' => 0,
            'published' => 0,
            'drafts' => 0,
            'reception_data' => [],
            'total' => [],
        ];

        return view('dashboard')->with([
            'cpage' => "dashboard",
            'analytics' => $analytics,
            'churches' => Church::where(['soft_delete'=>0])->get(),
            'members' => Member::where(['soft_delete'=>0])->get(),
            'announcements' => Announcement::where(['soft_delete'=>0])->get(),
            'materials' => 0,
            'stocks' => 0,
            'banks' => 0,
            'projects' => 0,
            'workers' => 0,
            'progress' => 0,
            'request' => 0,
            'closed' => 0
        ]);
    }

    public function analytics()
    {
        activity('ANALYTICS')
            ->log("Accessed the Report page")->causer(request()->user());
        return view('analytics')->with([
            'cpage' => "finances",
            'suppliers' => Supplier::all(),
            'materials' => Material::all()
        ]);
    }

    public function myTestAddToLog()
    {
        \LogActivity::addToLog('My Testing Add To Log.');
        dd('log insert successfully.');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function logActivity()
    {
        $logs = \LogActivity::logActivityLists();
        return view('logActivity', compact('logs'));
    }
}
