<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Expense;
use BackedEnum;
use Filament\Pages\Page;

class BrandLedger extends Page
{
    protected string $view = 'filament.pages.brand-ledger';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Brand Ledger P&L';
    protected static ?string $title = 'Brand Ledger';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance & Accounts';
    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'finance']);
    }

    public ?string $startDate = null;
    public ?string $endDate = null;

    public array $summary = [];
    public array $ledgerEntries = [];

    public function mount(): void
    {
        // Default to current month range
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->calculateLedger();
    }

    public function updatedStartDate(): void
    {
        $this->calculateLedger();
    }

    public function updatedEndDate(): void
    {
        $this->calculateLedger();
    }

    public function resetFilters(): void
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->calculateLedger();
    }

    public function calculateLedger(): void
    {
        $salesQuery = Order::where('status', 'delivered');
        $expensesQuery = Expense::query();

        if ($this->startDate) {
            $salesQuery->whereDate('created_at', '>=', $this->startDate);
            $expensesQuery->whereDate('incurred_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $salesQuery->whereDate('created_at', '<=', $this->endDate);
            $expensesQuery->whereDate('incurred_at', '<=', $this->endDate);
        }

        // Fetch sales
        $sales = $salesQuery->get()->map(function ($order) {
            return [
                'date' => $order->created_at->format('Y-m-d'),
                'type' => 'Online Sale',
                'description' => "Order #{$order->id} (Delivered)",
                'debit' => 0.00,
                'credit' => (float) $order->total_amount,
            ];
        });

        // Fetch expenses
        $expenses = $expensesQuery->get()->map(function ($expense) {
            return [
                'date' => $expense->incurred_at->format('Y-m-d'),
                'type' => 'HQ Expense',
                'description' => "{$expense->category}: {$expense->title}" . ($expense->notes ? " ({$expense->notes})" : ""),
                'debit' => (float) $expense->amount,
                'credit' => 0.00,
            ];
        });

        // Fetch physical store logs
        $storeLogsQuery = \App\Models\PhysicalStoreLog::query();
        if ($this->startDate) {
            $storeLogsQuery->whereDate('date', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $storeLogsQuery->whereDate('date', '<=', $this->endDate);
        }
        $storeLogs = $storeLogsQuery->with('store')->get()->map(function ($log) {
            return [
                'date' => $log->date->format('Y-m-d'),
                'type' => $log->type === 'Sale' ? 'Store Sale' : 'Store Expense',
                'description' => "[{$log->store->name}] {$log->category}: " . ($log->notes ? $log->notes : "Local Store Entry"),
                'debit' => $log->type === 'Expense' ? (float) $log->amount : 0.00,
                'credit' => $log->type === 'Sale' ? (float) $log->amount : 0.00,
            ];
        });

        // Merge and sort chronologically
        $ledger = $sales->concat($expenses)->concat($storeLogs)->sortBy(function ($entry) {
            return $entry['date'] . $entry['type'];
        })->values();

        // Calculate running balance and summaries
        $totalSales = 0.00;
        $totalExpenses = 0.00;
        $runningBalance = 0.00;
        $this->ledgerEntries = [];

        foreach ($ledger as $entry) {
            $totalSales += $entry['credit'];
            $totalExpenses += $entry['debit'];
            $runningBalance += ($entry['credit'] - $entry['debit']);
            
            $entry['balance'] = $runningBalance;
            $this->ledgerEntries[] = $entry;
        }

        $this->summary = [
            'total_sales' => $totalSales,
            'total_expenses' => $totalExpenses,
            'net_profit' => $totalSales - $totalExpenses,
        ];
    }
}
