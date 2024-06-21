<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LastCustomers extends BaseWidget
{

    protected static ?int $sort = 3;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn() => User::latest()
            )
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
            ]);
    }
}
