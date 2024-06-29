<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\PhotosRelationManager;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $pluralModelLabel = 'Produtos';

    protected static ?string $modelLabel = 'Produto';

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static ?string $navigationGroup = 'Admin';

  //  

    protected static ?string $navigationLabel = 'Produtos';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                Forms\Components\Fieldset::make('Dados')->schema([
                    TextInput::make('name')
                    ->reactive()
                            ->afterStateUpdated(function($state, Forms\Set $set){
                                $state = str()->of($state)->slug();
                                $set('slug', $state);
                            })
                    ->debounce(600)
                    ->required(),
            Select::make('store_id')
                    ->relationship(
                        'store', 'name',
                        fn(Builder $query) => 
                        $query->whereRelation(
                            'tenant', 
                            'tenant_id',
                            '=',
                            Filament::getTenant()->id
                            )
                    ),

                    TextInput::make('description'),
                    RichEditor::make('body')->required(),
                    
                    //seção para divisão do form
                    Section::make('Dados Complementares')->columns(2)->schema([
                        TextInput::make('price')->required(),
                        Toggle::make('status')->required(),
                        TextInput::make('stock')->required(),
                        TextInput::make('slug')                                         
                                    ->required(),
                        Select::make('categories')
                                ->multiple()
                                ->relationship(
                                    'categories', 'name',
                                    fn(Builder $query, Forms\Get $get) => $query->whereRelation(
                                        'tenant', 
                                        'tenant_id',
                                        '=',
                                        Filament::getTenant()->id
                                        )->whereRelation(
                                            'store',
                                            'store_id',
                                            '=',
                                            $get('store_id')
                                        )
                                ),
                    ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                        ->searchable()
                        ->label('Produto'),
                        Tables\Columns\TextColumn::make('price')->money('BRL'),
                        Tables\Columns\TextColumn::make('created_at')->date('d/m/Y H:i:s')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\PhotosRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::loadWithTenant()->count();
    }
}
