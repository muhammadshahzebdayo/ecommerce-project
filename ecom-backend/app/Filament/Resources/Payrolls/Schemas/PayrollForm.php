<?php

namespace App\Filament\Resources\Payrolls\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class PayrollForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('employee_id')
                    ->label('Employee')
                    ->relationship('employee', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $employee = \App\Models\Employee::find($state);
                            if ($employee) {
                                $set('basic_salary', $employee->salary);
                            }
                        }
                    }),
                Select::make('month')
                    ->options([
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    ])
                    ->default(now()->month)
                    ->required(),
                TextInput::make('year')
                    ->numeric()
                    ->default(now()->year)
                    ->required(),
                TextInput::make('basic_salary')
                    ->required()
                    ->numeric()
                    ->prefix('Rs. ')
                    ->minValue(0)
                    ->live(),
                TextInput::make('allowances')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('Rs. ')
                    ->minValue(0)
                    ->live(),
                TextInput::make('deductions')
                    ->numeric()
                    ->default(0.00)
                    ->prefix('Rs. ')
                    ->minValue(0)
                    ->live(),
                Placeholder::make('net_paid')
                    ->label('Net Payout Amount')
                    ->content(function ($get) {
                        $basic = (float) $get('basic_salary');
                        $allowances = (float) $get('allowances');
                        $deductions = (float) $get('deductions');
                        return 'Rs. ' . number_format($basic + $allowances - $deductions, 2);
                    }),
                TextInput::make('purpose')
                    ->label('Purpose / Reason for Salary Slip')
                    ->default('Monthly Salary Payout')
                    ->placeholder('e.g., Bank Account Verification, Visa application, Personal Records')
                    ->maxLength(255)
                    ->required(),
                Select::make('payment_status')
                    ->options([
                        'Pending' => 'Pending (Not Released)',
                        'Paid' => 'Paid (Released)',
                    ])
                    ->default('Pending')
                    ->required()
                    ->live(),
                DatePicker::make('paid_at')
                    ->label('Paid On')
                    ->visible(fn ($get) => $get('payment_status') === 'Paid')
                    ->required(fn ($get) => $get('payment_status') === 'Paid')
                    ->default(now()),
                FileUpload::make('receipt_path')
                    ->label('Payment Receipt Screenshot / Voucher Image')
                    ->image()
                    ->directory('payroll-receipts')
                    ->visibility('public')
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        if ($state) {
                            $set('payment_status', 'Paid');
                            $set('paid_at', now()->format('Y-m-d'));

                            $name = strtolower(is_array($state) ? reset($state) : $state);
                            if (str_contains($name, 'easypaisa') || str_contains($name, 'ep')) {
                                $set('payment_method', 'EasyPaisa');
                            } elseif (str_contains($name, 'jazzcash') || str_contains($name, 'jc')) {
                                $set('payment_method', 'JazzCash');
                            } elseif (str_contains($name, 'bank') || str_contains($name, 'transfer') || str_contains($name, 'hbl') || str_contains($name, 'alfalah') || str_contains($name, 'receipt')) {
                                $set('payment_method', 'Bank Transfer');
                            } elseif (str_contains($name, 'cash') || str_contains($name, 'hand')) {
                                $set('payment_method', 'Cash');
                            } else {
                                $set('payment_method', 'Bank Transfer');
                            }
                        } else {
                            $set('payment_status', 'Pending');
                            $set('paid_at', null);
                            $set('payment_method', null);
                        }
                    }),
                Select::make('payment_method')
                    ->options([
                        'Bank Transfer' => 'Direct Bank Transfer',
                        'EasyPaisa' => 'EasyPaisa Wallet',
                        'JazzCash' => 'JazzCash Wallet',
                        'Cash' => 'Cash in Hand',
                    ])
                    ->visible(fn ($get) => $get('payment_status') === 'Paid')
                    ->required(fn ($get) => $get('payment_status') === 'Paid'),
                Textarea::make('notes')
                    ->columnSpanFull()
                    ->placeholder('Add payout details or special bonus comments...'),
            ]);
    }
}
