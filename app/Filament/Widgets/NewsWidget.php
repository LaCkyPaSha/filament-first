<?php

namespace App\Filament\Widgets;

use App\Models\News;
use Filament\Forms\Components\Section;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class NewsWidget extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {

        return $table
            ->query(
                News::query()
            )
            ->columns([
                TextColumn::make('title')->badge()->color('warning'),
                TextColumn::make('description')->wrap(true)->words(80),
//                TextColumn::make('user_id')->label('Author'),
                TextColumn::make('updated_at')->dateTime(),
            ])
            ->description('The news');
    }
}
