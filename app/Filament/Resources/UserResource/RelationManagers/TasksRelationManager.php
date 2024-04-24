<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('title')->required(),
                    MarkdownEditor::make('description')
                        ->columnSpanFull(),
                ])->columns(3)
                    ->columnSpan(2),
                Group::make()->schema([
                    Section::make()->schema([
                        FileUpload::make('attachment')
                            ->disk('public')
                            ->directory('attachments')
                            ->columnSpanFull(),
                    ]),
                    Select::make('status_id')
                        ->label('Status')
                        ->relationship('status', 'name')
                        ->required(),
                    DateTimePicker::make('deadline')
                        ->required()
                ])
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('deadline')
                    ->sortable()
                    ->searchable()
                    ->dateTime()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->sortable()
                    ->searchable()
                    ->dateTime()
                    ->toggleable()
            ])
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
