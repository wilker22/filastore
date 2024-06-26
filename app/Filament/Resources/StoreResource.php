<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $pluralModelLabel = 'Lojas';

    protected static ?string $modelLabel = 'Loja';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Admin';

   // 

    protected static ?string $navigationLabel = 'Lojas';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                        ->reactive()
                        ->afterStateUpdated(function($state, $set){
                            $state = str()->of($state)->slug();
                            $set('slug', $state);
                        })
                        ->debounce(600)
                        ->required(),
                Forms\Components\TextInput::make('phone')->required(),
                Forms\Components\RichEditor::make('about')->required(),
                Forms\Components\FileUpload::make('logo')
                                    ->directory('stores')
                                    ->disk('public')
                                    ->image(),
                Forms\Components\TextInput::make('slug')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')
                                ->sortable()
                                ->searchable()   
                                ->label('Loja'),

                Tables\Columns\TextColumn::make('created_at')
                                                ->sortable()
                                                ->date('d/m/Y H:i')
                                                ->label('Data Cadastro'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::loadWithTenant()->count();
    }
}
