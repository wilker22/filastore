<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Password;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('change_password')
            // ->icon('heroicon-m-user')
            // ->color('info')
            // ->requiresConfirmation()

            ->form([

                TextInput::make('password')
                    ->password()
                    ->required()
                    ->rule(Password::default()),

                TextInput::make('password_confirmation')
                    ->password()
                    ->same('password')
                    ->required()
                    ->rule(Password::default()),
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
        ];
    }
}
