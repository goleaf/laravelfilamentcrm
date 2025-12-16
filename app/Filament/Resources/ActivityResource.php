<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Deal;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string | \UnitEnum | null $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->preload()
                    ->required(fn (): bool => blank(Filament::getTenant()))
                    ->visible(fn (): bool => blank(Filament::getTenant())),
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name', function (Builder $query): Builder {
                        $tenant = Filament::getTenant();
                        if (! $tenant) {
                            return $query;
                        }

                        return $query->whereIn('id', $tenant->allUsers()->pluck('id'));
                    })
                    ->searchable()
                    ->preload()
                    ->default(fn (): ?int => auth()->id()),
                MorphToSelect::make('subject')
                    ->required()
                    ->types([
                        Type::make(Company::class)
                            ->titleAttribute('name'),
                        Type::make(Contact::class)
                            ->titleAttribute('first_name')
                            ->searchColumns(['first_name', 'last_name', 'email'])
                            ->getOptionLabelFromRecordUsing(fn (Contact $record): string => $record->full_name),
                        Type::make(Deal::class)
                            ->titleAttribute('name'),
                    ])
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Select::make('type')
                    ->options([
                        'note' => 'Note',
                        'call' => 'Call',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                        'task' => 'Task',
                    ])
                    ->required(),
                DateTimePicker::make('due_at')
                    ->label('Due at'),
                DateTimePicker::make('completed_at')
                    ->label('Completed at'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Team')
                    ->visible(fn (): bool => blank(Filament::getTenant()))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->getStateUsing(function (Activity $record): ?string {
                        $subject = $record->subject;

                        return match (true) {
                            $subject instanceof Company => $subject->name,
                            $subject instanceof Contact => $subject->full_name,
                            $subject instanceof Deal => $subject->name,
                            default => null,
                        };
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name')
                    ->visible(fn (): bool => blank(Filament::getTenant())),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'note' => 'Note',
                        'call' => 'Call',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                        'task' => 'Task',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}

