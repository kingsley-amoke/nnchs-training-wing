<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Level;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Personal Details')
                    ->schema([
                        TextInput::make('first_name')
                            ->label(label: 'First Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->label(label: 'Last Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('other_names')
                            ->label(label: 'Other Names (optional)')
                            ->maxLength(255),
                        TextInput::make('phone_number')
                            ->label(label: 'Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('service_number')
                            ->label(label: 'Service Number')
                            ->required()
                            ->maxLength(255),
                        Select::make('gender')
                            ->label(label: 'Gender')
                            ->required()
                            ->native(false)
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female'
                            ])
                            ->default('male'),

                        DatePicker::make('date_of_birth')
                            ->label(label: 'Date of Birth')
                            ->native(false)

                            ->required(),
                        TextInput::make('state_of_origin')
                            ->label(label: 'State of Origin')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('local_government_area')
                            ->label(label: 'Local Government')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                Section::make('Others')
                    ->schema([
                        Select::make('department_id')
                            ->relationship(name: 'department', titleAttribute: 'name')
                            ->required()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('level_id', null);
                            })
                            ->native(false),

                        Select::make('level_id')
                            ->relationship(name: 'level', titleAttribute: 'name')
                            ->required()
                            ->native(false)
                            ->options(
                                fn(Get $get): Collection => Level::query()
                                    ->where('department_id', $get('department_id'))
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                    ])->columns(2)


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')

                    ->searchable(),
                Tables\Columns\TextColumn::make('service_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level.name')
                    ->numeric()
                    ->sortable(),




                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
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
            RelationManagers\AssessmentsRelationManager::class
        ];
    }

    public static function getNavigationBadge(): ?string

    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string

    {
        return 'success';
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
