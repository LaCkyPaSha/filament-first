<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {

        $currentUser = Auth::user();

        $isModerOrAdmin = $currentUser->isAdmin() || $currentUser->isModer();

        return $form
            ->schema([
                Section::make()->schema([
                TextInput::make('title')->required()->maxValue(255),
                MarkdownEditor::make('description')->columnSpanFull(),
                ])->columns(3)
                    ->columnSpan(2),
                Group::make()->schema([
                    Section::make()->schema([
                        FileUpload::make('attachment')
                            ->disk('public')
                            ->directory('attachments')
                            ->columnSpanFull()
                            ->openable(),
                        ]),
                        DateTimePicker::make('deadline')
                            ->required(),
                        Group::make()->schema(

                            $isModerOrAdmin?
                                [
                    Select::make('status_id')
                        ->label('Status')
                        ->relationship('status', 'name', function ($query) {
                            $query->orderBy('id');
                        }, false)
                        ->required(),
                    Select::make('users')
                        ->label('Assigned to')
                        ->relationship('users', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->multiple(),
                            ]:[
                                Select::make('status_id')
                                    ->label('Status')
                                    ->relationship('status', 'name', function ($query) {
                                        $query->orderBy('id');
                                    }, false)
                                    ->required(),
                            ])
                    ])
                ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        $currentUser = Auth::user();

        $isModerOrAdmin = $currentUser->isAdmin() || $currentUser->isModer();

        if(!$isModerOrAdmin) {
            $table->query(
                Task::whereHas('users', function ($query) use ($currentUser) {
                    $query->where('users.id', $currentUser->id);
                })
            );
        }

        return $table
            ->columns(
                $isModerOrAdmin ? [
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('users.name')
                    ->label('Assigned to')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status.name')
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(function (string $state) : string {
                        return match($state){
                            'Assigned' => 'info',
                            'Approved' => 'gray',
                            'In Process' => 'warning',
                            'Is Waiting a review' => 'danger',
                            'Completed' => 'success',
                        };
                    }),
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
            ]:[
                        TextColumn::make('title')
                            ->sortable()
                            ->searchable()
                            ->toggleable(),
                        TextColumn::make('status.name')
                            ->label('Status')
                            ->sortable()
                            ->searchable()
                            ->toggleable()
                            ->badge()
                            ->color(function (string $state) : string {
                                return match($state){
                                    'Assigned' => 'info',
                                    'Approved' => 'gray',
                                    'In Process' => 'warning',
                                    'Is Waiting a review' => 'danger',
                                    'Completed' => 'success',
                                };
                            }),
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
            ->filters($isModerOrAdmin ? [
                SelectFilter::make('status_id')
                    ->relationship('status', 'name')
                    ->label("Status"),
                SelectFilter::make('users')
                    ->relationship('users', 'name')
                    ->label("Assigned to")
                    ->preload()
                    ->searchable()->multiple()
            ]
            :
            [
                SelectFilter::make('status_id')
                ->relationship('status', 'name')
                ->label("Status"),
                ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\CommentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
