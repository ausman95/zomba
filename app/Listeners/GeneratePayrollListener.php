<?php

namespace App\Listeners;

use App\Events\GeneratePayroll;
use App\Models\Labourer;
use App\Models\Payroll;

class GeneratePayrollListener
{
    public function handle(GeneratePayroll $event)
    {
        $monthId = $event->monthId;
        $labourers = Labourer::all();

        foreach ($labourers as $labourer) {
            // Check if payroll already exists for this laborer and month
            $payrollExists = Payroll::where('labourer_id', $labourer->id)
                ->where('month_id', $monthId)
                ->exists();

            if (!$payrollExists) {
                // Create payroll for the laborer and month
                Payroll::create([
                    'labourer_id' => $labourer->id,
                    'month_id' => $monthId,
                    'total_amount' => 0, // Calculate total amount based on your logic
                    'status' => 'Pending',
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                    'payroll_date' => now(), // Or set a specific date
                ]);
            }
        }
    }
}
