<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Categorias';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->columns(1)
            ->schema([
                Forms\Components\Tabs::make('Abas')->schema([
                    Forms\Components\Tabs\Tab::make('Tab 1')->schema([
                        Select::make('store_id')
                            ->relationship(
                                'store',
                                'name',
                                fn (Builder $query) =>
                                $query->whereRelation(
                                    'tenant',
                                    'tenant_id',
                                    '=',
                                    Filament::getTenant()->id
                                )
                            ),
                        TextInput::make('name')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $state = str()->of($state)->slug;

                                $set('slug', $state);
                            })
                            ->debounce(600)
                            ->required(),
                    
                ]),
                Forms\Components\Tabs\Tab::make('Tab 2')->schema([

                    TextInput::make('description'),
                    TextInput::make('slug')->required()
                  
                  ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()
                    ->searchable()
                    ->label('Categoria'),
                TextColumn::make('created_at')->date('d/m/Y H:i:s'),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }
}
