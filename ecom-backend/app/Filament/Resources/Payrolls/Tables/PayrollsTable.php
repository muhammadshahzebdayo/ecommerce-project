<?php

namespace App\Filament\Resources\Payrolls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('employee.employee_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Admin Office' => 'info',
                        'Physical Store' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('month')
                    ->formatStateUsing(fn (int $state): string => date('F', mktime(0, 0, 0, $state, 10)))
                    ->sortable(),
                TextColumn::make('year')
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->prefix('Rs. ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('allowances')
                    ->prefix('Rs. ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('deductions')
                    ->prefix('Rs. ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('net_paid')
                    ->label('Net Paid')
                    ->prefix('Rs. ')
                    ->numeric()
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Paid' => 'success',
                        'Pending' => 'danger',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->date()
                    ->sortable()
                    ->placeholder('Not Paid yet'),
                TextColumn::make('payment_method')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'Pending' => 'Pending',
                        'Paid' => 'Paid',
                    ]),
                \Filament\Tables\Filters\SelectFilter::make('month')
                    ->options([
                        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\Action::make('download_slip')
                    ->label('Slip')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn ($record) => route('payroll.slip', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
