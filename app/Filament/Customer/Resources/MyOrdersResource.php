<?php

namespace App\Filament\Customer\Resources;

use App\Filament\Customer\Resources\MyOrdersResource\Pages;
use App\Filament\Customer\Resources\MyOrdersResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\Builder as QueryBuilder;

class MyOrdersResource extends Resource
{
    protected static ?string $model = Order::class;


    protected static ?string $pluralModelLabel = 'Meus Pedidos';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $myOrders = "Meus Pedidos";

    protected static ?string $navigationLabel = 'Meus Pedidos';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->modifyQueryUsing(fn (Builder $query) => $query->whereUserId(auth()->id()))
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('items_count')->label('Produtos')->searchable(),
                TextColumn::make('order_total')->label('Total Pedidos')->money('BRL'),
                TextColumn::make('created_at')->label('Pedido em:')->sortable()->date('d/m/Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }


    public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
    {
        return $infolist->schema([
            Split::make([
                Section::make([
                    TextEntry::make('code')->label('Código do Pedido'),
                    TextEntry::make('orderTotal')->label('Total Pedido')->money('BRL'),
                    TextEntry::make('items_count')->label('Produtos'),

                ])->columns('3'),

                Section::make([
                    TextEntry::make('user.name')->label('Pedido Por:'),
                    TextEntry::make('created_at')->label('Pedido em:')->date('d/m/Y H:i:s')

                ])->grow(false),
            ])->columnSpanFull()->from('md'),

            Section::make([
                RepeatableEntry::make('items')->schema([
                    TextEntry::make('product.name')->label('Produto'),
                    TextEntry::make('amount')->label('Total Produtos'),
                    TextEntry::make('order_value')->label('Subtotal')->money('BRL'),
                ]),
                
            ])->columns('1')
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
            'index' => Pages\ListMyOrders::route('/'),
            //  'create' => Pages\CreateMyOrders::route('/create'),
            'view' => Pages\ViewMyOrders::route('/{record}'),
            // 'edit' => Pages\EditMyOrders::route('/{record}/edit'),
        ];
    }
}
