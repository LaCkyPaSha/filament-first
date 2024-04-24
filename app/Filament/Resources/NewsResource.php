<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\News;
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

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function form(Form $form): Form
    {

        $currentUser = Auth::user();

        $isModerOrAdmin = $currentUser->isAdmin() || $currentUser->isModer();

        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('title')->maxValue(255)
                        ->{($isModerOrAdmin ? 'required' : 'disabled')}(),
                    MarkdownEditor::make('description')
                        ->{($isModerOrAdmin ? 'required' : 'disabled')}()
                        ->columnSpanFull(),
                    Select::make('user_id')->options([Auth::user()->id => Auth::user()->name])
                        ->searchable()
                        ->preload()
                        ->label('Author')
                        ->default(Auth::user()->id)
                        ->{($isModerOrAdmin ? 'required' : 'disabled')}(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('updated_at')
                    ->label('Updated at')
                    ->sortable()
                    ->searchable()
                    ->dateTime()
                    ->toggleable()
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label("Author")
                    ->preload()
                    ->searchable()
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
