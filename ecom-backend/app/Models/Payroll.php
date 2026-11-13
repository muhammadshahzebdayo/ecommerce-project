<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'deductions',
        'net_paid',
        'payment_status',
        'paid_at',
        'payment_method',
        'receipt_path',
        'purpose',
        'notes',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_paid' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function booted(): void
    {
        // 1. Calculate net paid dynamically before saving
        static::saving(function (Payroll $payroll) {
            $payroll->net_paid = $payroll->basic_salary + $payroll->allowances - $payroll->deductions;
        });

        // 2. Hook to log salary payouts as general expenses or physical store expenses
        static::saved(function (Payroll $payroll) {
            $employee = $payroll->employee;
            if (!$employee) return;
            
            $monthName = date("F", mktime(0, 0, 0, (int)$payroll->month, 10));
            $titleText = "Salary Payout: {$employee->name} ({$monthName} {$payroll->year})";

            if ($payroll->payment_status === 'Paid') {
                if ($employee->employee_type === 'Admin Office') {
                    // Log as HQ General Expense
                    Expense::updateOrCreate([
                        'title' => $titleText,
                        'category' => 'Staff',
                    ], [
                        'amount' => $payroll->net_paid,
                        'incurred_at' => $payroll->paid_at ?? now(),
                        'notes' => "Automatic payroll entry. Method: {$payroll->payment_method}. Notes: {$payroll->notes}",
                    ]);
                } elseif ($employee->employee_type === 'Physical Store' && $employee->physical_store_id) {
                    // Log as Physical Store Local Expense
                    PhysicalStoreLog::updateOrCreate([
                        'physical_store_id' => $employee->physical_store_id,
                        'type' => 'Expense',
                        'category' => 'Salaries',
                        'notes' => $titleText,
                    ], [
                        'amount' => $payroll->net_paid,
                        'date' => $payroll->paid_at ?? now(),
                    ]);
                }
            } else {
                // If payment status is Pending, clean up any existing expense logs
                if ($employee->employee_type === 'Admin Office') {
                    Expense::where('title', $titleText)->where('category', 'Staff')->delete();
                } elseif ($employee->employee_type === 'Physical Store' && $employee->physical_store_id) {
                    PhysicalStoreLog::where('physical_store_id', $employee->physical_store_id)
                        ->where('type', 'Expense')
                        ->where('category', 'Salaries')
                        ->where('notes', $titleText)
                        ->delete();
                }
            }
        });

        // 3. Hook to clean up if a payroll record is deleted
        static::deleted(function (Payroll $payroll) {
            $employee = $payroll->employee;
            if (!$employee) return;

            $monthName = date("F", mktime(0, 0, 0, (int)$payroll->month, 10));
            $titleText = "Salary Payout: {$employee->name} ({$monthName} {$payroll->year})";

            if ($employee->employee_type === 'Admin Office') {
                Expense::where('title', $titleText)->where('category', 'Staff')->delete();
            } elseif ($employee->employee_type === 'Physical Store' && $employee->physical_store_id) {
                PhysicalStoreLog::where('physical_store_id', $employee->physical_store_id)
                    ->where('type', 'Expense')
                    ->where('category', 'Salaries')
                    ->where('notes', $titleText)
                    ->delete();
            }
        });
    }
}
