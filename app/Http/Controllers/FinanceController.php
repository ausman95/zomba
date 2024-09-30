<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Banks;
use App\Models\Finance;
use App\Models\Incomes;
use App\Models\Material;
use App\Models\Month;
use App\Models\Supplier;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{

    public function getAccountBalance($start_date,$end_date)
    {
        return $value = Incomes::where(['account_id'=>$this->id])
            ->whereBetween('created_at',[$start_date,$end_date])
            ->groupBy('account_id')->sum("amount");
    }
    public function index()
    {
        activity('FINANCES')
            ->log("Accessed Finances")->causer(request()->user());
        $banks = Banks::where(['soft_delete'=>0])->orderBy('id','desc')->get();
        return view('finances.index')->with([
            'cpage' => "finances",
            'banks' => $banks
        ]);
    }
    public function financialStatement()
    {
        return view('finances.reports')->with([
            'cpage' => "finances",
            'months'=>Month::where(['soft_delete'=>0])->orderBy('id','desc')->get()
        ]);
    }
    public function generateFinancialStatement(Accounts $accounts, Request $request)
    {
        $this->validateRequest($request);

        if ($request->post('end_date') < $request->post('start_date')) {
            return back()->with(['error-notification' => "Please specify the date correctly!"]);
        }

        $startAndEndDates = $this->getStartAndEndDates($request);
        $this->logActivity();
        $statement = $request->post('statement');

        // Fetch financial data
        $start = $startAndEndDates['start'];
        $end = $startAndEndDates['end'];
        // Pass the calculated values to the view
        return view('finances.reports')->with([
            'cpage' => "finances",
            'statement' => $statement,
            'fixedAssets' => $this->fetchFixedAssets(),
            'start_date' => $startAndEndDates['start'],
            'end_date' => $startAndEndDates['end'],
            'profit' => $this->calculateProfit(),
            'totalLatestRevaluations' => $this->calculateTotalLatestRevaluations(),
            'totalFixedAssets' => $this->calculateTotalFixedAssets(),
            'currentAssets' => $this->fetchCurrentAssets(),
            'suppliers' => $this->fetchSuppliers(),
            'currentPayments' => $this->fetchCurrentPayments(),
            'liabilities' => $this->fetchLiabilities(),
            'all' => Accounts::allCrAccounts($start, $end),
            'catCredits' => Accounts::getAccountCatDebits($start, $end),
            'catDebits' => Accounts::getAccountCateExpenses($start, $end),
            'accounts' => Accounts::allAccounts($start, $end),
            'credits' => Accounts::getAccountBalanceDebits($statement, $start, $end),
            'debits' => Incomes::accountAll($start, $end),
            'expenses' => Incomes::accountExpensesAll($statement, $start, $end),
            'admins' => Accounts::getAccountBalanceAdmin($statement, $start, $end),
            'months' => Month::where(['soft_delete' => 0])->orderBy('id', 'desc')->get()]);
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'statement' => "required|numeric",
            'start_date' => "required|numeric",
            'end_date' => "required|numeric",
        ]);
    }

    private function getStartAndEndDates(Request $request)
    {
        $from_month = Month::where(['id' => $request->post('start_date')])->first();
        $to_month = Month::where(['id' => $request->post('end_date')])->first();

        return [
            'start' => $from_month->start_date,
            'end' => $to_month->end_date,
        ];
    }

    private function logActivity()
    {
        activity('ANALYTICS')->log("Accessed Financial Reports")->causer(request()->user());
    }


    private function calculateProfit()
    {
        return \App\Models\Payment::join('accounts', 'accounts.id', '=', 'payments.account_id')
            ->join('categories', 'categories.id', '=', 'accounts.category_id')
            ->select(\Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN payments.type = 1 THEN payments.amount ELSE 0 END) - SUM(CASE WHEN payments.type = 2 THEN payments.amount ELSE 0 END) as profit'))
            ->where('account_id', '!=', 134)
            ->where('categories.status', '=', 2)
            ->where('accounts.soft_delete', '=', 0)
            ->first()->profit;
    }

    private function calculateTotalLatestRevaluations()
    {
        $assets = \App\Models\Assets::all();

        return $assets->map(function ($asset) {
            $cost = $asset->cost; // Get the initial cost of the asset
            $quantity = $asset->quantity; // Get the initial Quantity of the asset
            $cost = $cost*$quantity;
            $depreciation = $asset->depreciation; // Get the depreciation value per year
            $expectedLife = $asset->life; // Expected life in years

            // Calculate total depreciation based on the expected life
            $totalDepreciation = ($expectedLife > 0) ? ($depreciation * (now()->year - $asset->t_date->year)) : 0;

            // Calculate net book value
            return $cost - $totalDepreciation; // Return the calculated net book value
        })->sum(); // Sum all net book values
    }

    private function fetchFixedAssets()
    {
        return \App\Models\AssetRevaluation::join('assets', 'assets.id', '=', 'asset_revaluations.asset_id')
            ->join('categories', 'assets.category_id', '=', 'categories.id')
            ->whereIn('asset_revaluations.id', function ($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw('MAX(id)'))
                    ->from('asset_revaluations')
                    ->groupBy('asset_id');
            })
            ->groupBy('categories.id')
            ->select('categories.id as category_id', 'categories.name', 'asset_revaluations.created_at',
                'assets.life', 'assets.quantity',
                \Illuminate\Support\Facades\DB::raw('SUM(asset_revaluations.amount) as cost'))
            ->get();
    }

    private function calculateTotalFixedAssets()
    {
        $fixedAssets = $this->fetchFixedAssets();

        $totalFixedAssets = 0;
        $currentDate = new DateTime(); // Get the current date

        foreach ($fixedAssets as $asset) {
            $expectedLife = $asset->life; // Expected life in years
            $cost = $asset->cost; // Initial cost of the asset
            $createdAt = new DateTime($asset->created_at); // Date when the asset was created or purchased

            // Calculate the difference between the current date and the creation date in days
            $daysInUse = $currentDate->diff($createdAt)->days; // Number of days the asset has been in use

            // Convert expected life to days (assuming 365 days per year)
            $expectedLifeInDays = $expectedLife * 365;

            // Ensure that the days in use do not exceed the expected life in days
            if ($daysInUse > $expectedLifeInDays) {
                $daysInUse = $expectedLifeInDays; // Asset is fully depreciated
            }

            // Calculate daily depreciation
            $dailyDepreciation = ($expectedLife > 0) ? $cost / $expectedLifeInDays : 0;

            // Calculate total depreciation for the time the asset has been in use
            $cumulativeDepreciation = $dailyDepreciation * $daysInUse;

            // Calculate the net book value after depreciation
            $netValue = $cost - $cumulativeDepreciation;

            // Add the net value of this asset to the total fixed assets
            $totalFixedAssets += $netValue;
        }

        return $totalFixedAssets;
    }

    private function fetchCurrentAssets()
    {
        return \App\Models\Payment::join('accounts', 'payments.account_id', '=', 'accounts.id')
            ->join('banks', 'banks.id', '=', 'payments.bank_id')
            ->select('banks.*', \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN payments.type = 1 THEN payments.amount ELSE 0 END) - SUM(CASE WHEN payments.type = 2 THEN payments.amount ELSE 0 END) as net_amount'))
            ->first();
    }

    private function fetchSuppliers()
    {
        return \App\Models\SupplierPayments::select(\Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN supplier_payments.transaction_type = 1 THEN supplier_payments.amount ELSE 0 END) - SUM(CASE WHEN supplier_payments.transaction_type = 2 THEN supplier_payments.amount ELSE 0 END) as net_amount'))
            ->first();
    }

    private function fetchCurrentPayments()
    {
        return \App\Models\Payment::join('accounts', 'payments.account_id', '=', 'accounts.id')
            ->join('categories', 'accounts.category_id', '=', 'categories.id')
            ->select('accounts.*', \Illuminate\Support\Facades\DB::raw('SUM(payments.amount) as total_amount'))
            ->where('categories.status', 1)
            ->where('accounts.id', '!=', 134) // Exclude specific account
            ->where('accounts.id', '!=', 2)   // Exclude another specific account
            ->groupBy('accounts.id')
            ->get();
    }

    private function fetchLiabilities()
    {
        return \App\Models\Payment::join('accounts', 'payments.account_id', '=', 'accounts.id')
            ->join('categories', 'accounts.category_id', '=', 'categories.id')
            ->select('accounts.*', \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN payments.type = 1 THEN payments.amount ELSE 0 END) - SUM(CASE WHEN payments.type = 2 THEN payments.amount ELSE 0 END) as net_amount'))
            ->where('categories.status', 1)
            ->where('accounts.id', '!=', 134) // Exclude specific account
            ->groupBy('accounts.id')
            ->having('net_amount', '<', 0) // Only include accounts with a net amount less than 0
            ->get();
    }
}
