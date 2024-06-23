<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LastCustomers extends BaseWidget
{

    protected  static ?string $heading = 'Vendas';
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn() => User::latest()->take(10)
            )
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email'),
                TextColumn::make('create_at')->date('d/m/Y'),
                
            ]);
    }
}
