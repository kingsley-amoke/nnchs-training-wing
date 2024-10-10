<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assessments';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('course.code'),
            Tables\Columns\TextColumn::make('course.unit')
            ->label(label:'Unit'),
            Tables\Columns\TextColumn::make('first')
            ->label(label:'CA 1'),
            Tables\Columns\TextColumn::make('second')
            ->label(label:'CA 2'),
            Tables\Columns\TextColumn::make('third')
            ->label(label:'CA 3'),
            Tables\Columns\TextColumn::make('exam'),
            Tables\Columns\TextColumn::make('total'),
            Tables\Columns\TextColumn::make('grade'),
            
        ])
            // ->recordTitleAttribute('exam')
            // ->columns([
            //     Tables\Columns\TextColumn::make('exam'),
            // ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
