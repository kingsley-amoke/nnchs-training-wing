<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Filament\Resources\AssessmentResource\RelationManagers;
use App\Models\Assessment;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Student Information')
                ->schema([
                    Select::make('student_id')
                    ->label(label:'Student')
                    ->relationship(name:'student', titleAttribute:'last_name')
                    ->getOptionLabelFromRecordUsing(fn (Model $student) => "{$student->last_name} {$student->first_name}")
                    ->required()
                    ->preload()
                    ->native(false),
                   
                Select::make('course_id')
                ->label(label:'Course')
                ->relationship(name:'course', titleAttribute:'title')
                    ->required()
                    ->preload()
                    ->native(false),
                   
                ])->columns(2),
                Section::make('Scores')
                ->schema([
                     
              TextInput::make('first')
              ->label(label:'CA 1')
                ->numeric()
                ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total', ($get('exam') ?: 0) + ($get('second') ?: 0) + ($get('third') ?: 0) + ($state ?: 0) ))
                ->afterStateUpdated(function(Set $set, Get $get){
                   if($get('total') < 50){
                        $set('grade', 'F');
                   }else if($get('total') < 60){
                    $set('grade', 'C');
                   }else if($get('total') < 65){
                    $set('grade', 'B');
                   }else{
                    $set('grade', 'A');
                   }

                } )
                ->reactive()
                ->default(0)
                ->minValue(0)
                ->maxValue(10),
                
           TextInput::make('second')
           ->label(label:'CA 2')
                ->numeric()
                ->default(0)
                ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total', ($get('exam') ?: 0) + ($get('first') ?: 0) + ($get('third') ?: 0) + ($state ?: 0) ))
                ->afterStateUpdated(function(Set $set, Get $get){
                    if($get('total') < 50){
                         $set('grade', 'F');
                    }else if($get('total') < 60){
                     $set('grade', 'C');
                    }else if($get('total') < 65){
                     $set('grade', 'B');
                    }else{
                     $set('grade', 'A');
                    }
 
                 } )
                ->reactive()
                ->maxValue(10)
                ->minValue(0),
            TextInput::make('third')
            ->label(label:'CA 3')
                ->numeric()
                ->default(0)
                ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total', ($get('exam') ?: 0) + ($get('second') ?: 0) + ($get('first') ?: 0) + ($state ?: 0) ))
                ->afterStateUpdated(function(Set $set, Get $get){
                    if($get('total') < 50){
                         $set('grade', 'F');
                    }else if($get('total') < 60){
                     $set('grade', 'C');
                    }else if($get('total') < 65){
                     $set('grade', 'B');
                    }else{
                     $set('grade', 'A');
                    }
 
                 } )
                ->reactive()
                ->maxValue(10)
                ->minValue(0),
            TextInput::make('exam')
                ->numeric()
                ->default(0)
                ->afterStateUpdated(fn($state, Set $set, Get $get) => $set('total', ($get('second') ?: 0) + ($get('second') ?: 0) + ($get('third') ?: 0) + ($state ?: 0) ))
                ->afterStateUpdated(function(Set $set, Get $get){
                    if($get('total') < 50){
                         $set('grade', 'F');
                    }else if($get('total') < 60){
                     $set('grade', 'C');
                    }else if($get('total') < 65){
                     $set('grade', 'B');
                    }else{
                     $set('grade', 'A');
                    }
 
                 } )
                ->reactive()
                ->maxValue(70)
                ->minValue(0),
          TextInput::make('total')
                ->numeric()
                ->default(0)
                ->dehydrated()
                ->reactive()
                ->maxValue(100)
                ->minValue(0)
                ->extraInputAttributes(['readonly'=>true]),
            TextInput::make('grade')
                ->maxLength(255)
                ->extraInputAttributes(['readonly'=>true])
                ,
                ])
                ->columns(6),
              
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.service_number')
                ->label(label:'Student')
                ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('course.code')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('first')
                ->label(label:'CA 1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('second')
                ->label(label:'CA 2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('third')
                ->label(label:'CA 3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exam')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grade')
                    ->searchable(),
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
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'view' => Pages\ViewAssessment::route('/{record}'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
        ];
    }
}
