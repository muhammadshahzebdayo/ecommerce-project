<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Response;

class PayrollSlipController extends Controller
{
    public function view(Payroll $payroll)
    {
        $payroll->load('employee.store');
        
        return view('payroll.slip', compact('payroll'));
    }

    public function downloadWord(Payroll $payroll)
    {
        $payroll->load('employee.store');
        
        $htmlContent = view('payroll.slip', compact('payroll'))->render();

        // Strip the action bar for Word download
        $htmlContent = preg_replace('/<div class="action-bar no-print">.*?<\/div>/s', '', $htmlContent);

        $employeeName = str_replace(' ', '_', $payroll->employee->name);
        $fileName = "Salary_Slip_{$employeeName}_{$payroll->month}_{$payroll->year}.doc";

        return response($htmlContent)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
