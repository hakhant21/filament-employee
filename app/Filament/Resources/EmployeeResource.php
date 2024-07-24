<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Get;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use Filament\Forms\Set;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationLabel = 'Employee';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fullname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ]),
                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->relationship('country', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function(Set $set) {
                        $set('state_id', null);
                    })
                    ->required(),
                Forms\Components\Select::make('state_id')
                    ->label('State')
                    ->options(function(Get $get) {
                        return State::query()
                            ->where('country_id', $get('country_id'))
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function(Set $set) {
                        $set('city_id', null);
                    })
                    ->required(),
                Forms\Components\Select::make('city_id')
                    ->label('City')
                    ->options(function(Get $get) {
                        return City::query()
                            ->where('state_id', $get('state_id'))
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\DatePicker::make('date_of_hired')
                    ->required(),
                Forms\Components\TextInput::make('job_title')
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->required(),
                Forms\Components\RichEditor::make('address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('photo')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo'),
                Tables\Columns\TextColumn::make('fullname')
                    ->sortable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->sortable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->sortable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->html(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_hired')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
