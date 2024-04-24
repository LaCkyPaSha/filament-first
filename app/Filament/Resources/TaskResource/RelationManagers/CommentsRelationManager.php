<?php

namespace App\Filament\Resources\TaskResource\RelationManagers;

use App\Filament\Resources\TaskResource;
use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    public function form(Form $form): Form
    {

        $currentUser = Auth::user();//'user_id'

        return $form
            ->schema([
                Select::make('user_id')->options([Auth::user()->id => Auth::user()->name])
                    ->searchable()
                    ->preload()
                    ->label('User')
                    ->default(Auth::user()->id)
                    ->disabled(false),
                TextInput::make('comment')
            ]);
    }

    public function table(Table $table): Table
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser->isAdmin();

        $table->defaultSort('created_at', 'desc');

        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('comment')
                    ->color(function (string $state, Comment $comment) use ($currentUser) : string {

                        $userId = Comment::all()->where('comment', $state)->pluck('user_id')[0];

                        if($currentUser->id === $userId){
                            return 'success';
                        }else{
                            return 'warning';
                        }

                    })
                    ->wrap(true),
                Tables\Columns\TextColumn::make('user.name')->badge()
                ->color(function (string $state, Comment $comment) use ($currentUser) : string {

                    $userRoleId = $comment->user()->get('role_id')[0]['role_id'];

                    return match($userRoleId){
                        1 => 'danger',
                        2 => 'success',
                        3 => 'info',
                        4 => 'gray',
                        5 => 'warning'
                    };
                }
                ),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label('Time'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions($isAdmin?[
//                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]:[])
            ->bulkActions($isAdmin?[
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]:[]);
    }
}
