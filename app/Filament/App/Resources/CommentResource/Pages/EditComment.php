<?php

namespace App\Filament\App\Resources\CommentResource\Pages;

use App\Filament\App\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        
        // Ensure user can only edit their own comments
        if ($this->record->user_id !== auth()->id()) {
            abort(403, 'You do not have permission to edit this comment.');
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
