<?php

namespace App\Filament\App\Resources\PostResource\Pages;

use App\Filament\App\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Ensure user can only edit their own posts
        if ($this->record->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this post.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
