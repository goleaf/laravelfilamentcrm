<?php

namespace App\Filament\App\Resources\ProfileResource\Pages;

use App\Filament\App\Resources\ProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfile extends EditRecord
{
    protected static string $resource = ProfileResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Ensure user can only edit their own profile
        if ($this->record->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this profile.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
