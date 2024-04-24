<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        $currentUser = Auth::user();
        $isAdmin = $currentUser->isAdmin();

        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('name')
                        ->{($isAdmin ? 'required' : 'disabled')}(),
                    TextInput::make('email')->email()
                        ->{($isAdmin ? 'required' : 'disabled')}(),
                    TextInput::make('password')
                        ->{($isAdmin ? 'password' : 'disabled')}(),
                    Select::make('role_id')
                        ->label('Role')
                        ->{($isAdmin ? 'required' : 'disabled')}()
                        ->relationship('role', 'name')
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(true,true),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('email')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('role.name')
                    ->badge()
                    ->color(function (string $state) : string {
                        return match($state){
                            'ADMIN' => 'danger',
                            'USER' => 'success',
                            'MODER' => 'info'
                        };
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                //
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
            RelationManagers\TasksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
