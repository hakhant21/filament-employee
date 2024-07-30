<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Payroll;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Services\PayrollService;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\PayrollResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PayrollResource\RelationManagers;

class PayrollResource extends Resource
{
    protected static ?string $model = Payroll::class;

    protected static ?string $navigationLabel = 'Payroll';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Select::make('employee_id')
                        ->relationship('employee', 'fullname')
                        ->preload()
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, $set) {
                            if ($state) {
                                $employee = Employee::find($state);
                                $set('salary', $employee ? $employee->salary : 0);
                            }
                        }),
                    Forms\Components\TextInput::make('bonus')
                        ->label('Bonus Percentage')
                        ->numeric()
                        ->default(10)
                        ->required(),
                    Forms\Components\TextInput::make('salary')
                        ->numeric()
                        ->readOnly(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('employee.fullname')->label('Employee'),
                    Tables\Columns\TextColumn::make('salary')->label('Salary'),
                    Tables\Columns\TextColumn::make('bonus')->label('Bonus Percentage'),
                    Tables\Columns\TextColumn::make('deductions')->label('Deductions'),
                    Tables\Columns\TextColumn::make('pay_date')->label('Pay Date'),
                    Tables\Columns\TextColumn::make('net_pay')->label('Net Pay'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('singleGenerate')
                    ->label('Generate')
                    ->icon('heroicon-s-cog')
                    ->color('success')
                    ->action(function ($record) {

                        $employee = Employee::find($record['employee_id']);

                        $payrollData = PayrollService::make($employee, $record['bonus']);

                        Payroll::updateOrCreate([
                            'employee_id' => $record['employee_id'],
                        ], $payrollData);
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('multiGenerate')
                        ->label('Generate All')
                        ->icon('heroicon-s-queue-list')
                        ->color('success')
                        ->action(function (Collection $records) {
                            foreach($records as $record) {
                                $employee = Employee::find($record['employee_id']);

                                $payrollData = PayrollService::make($employee, $record['bonus']);

                                Payroll::updateOrCreate([
                                    'employee_id' => $record['employee_id'],
                                ], $payrollData);
                            }
                        }),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayrolls::route('/'),
            'create' => Pages\CreatePayroll::route('/create'),
            'edit' => Pages\EditPayroll::route('/{record}/edit'),
        ];
    }
}
