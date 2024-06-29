<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Auth\PasswordBroker as AuthPasswordBroker;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Validation\Rules\Password as RulesPassword;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Cliente';
  
    protected static ?string $pluralModelLabel = 'Clientes';
  
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->email()->required(),
                TextInput::make('password')
                    ->password()
                    
                    ->rule(Password::default()),
                TextInput::make('password_confirmation')
                    ->password()
                    ->same('password')
                    ->required()
                    ->rule(Password::default()),
                Select::make('role')->relationship('roles', 'name')->multiple()
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('change_password')
                    // ->icon('heroicon-m-user')
                    // ->color('info')
                    // ->requiresConfirmation()

                    ->form([

                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->rule(RulesPassword::default()),

                        TextInput::make('password_confirmation')
                            ->password()
                            ->same('password')
                            ->required()
                            ->rule(RulesPassword::default()),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'password' => bcrypt($data['password'])
                        ]);

                        Notification::make()
                            ->title('Senha atualizada!')
                            ->success()
                            ->send();
                    })
                // ->steps([
                //     Forms\Components\Wizard\Step::make('Passo 1')->schema([
                //             TextInput::make('name')->required(),
                //     ]),
                //     Forms\Components\Wizard\Step::make('Passo 2')->schema([
                //         TextInput::make('email')->required(),


                // ])

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
           // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return Filament::getTenant()->members->count();
    }
}
