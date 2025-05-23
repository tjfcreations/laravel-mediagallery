<?php

namespace Tjall\MediaGallery\Filament\Managers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;

class MediaGalleryManager extends RelationManager {
    protected static string $relationship = 'media';

    public function form(Form $form): Form {
        return $form
            ->schema([
                Forms\Components\TextInput::make('author')->required(),
                Forms\Components\MarkdownEditor::make('content'),
                // ...
            ]);
    }

    public function table(Table $table): Table {
        return $table
            ->reorderable('order_column')
            ->reorderRecordsTriggerAction(
                fn (Action $action, bool $isReordering) => $action
                    ->button()
                    ->label('Sorteren'),
            )
            ->paginated(false)
            ->contentGrid([
                'default' => 2,
                'md' => 3,
                'lg' => 4,
                'xl' => 5,
                '2xl' => 6,
            ])
            ->columns([
                Stack::make([
                    Tables\Columns\ImageColumn::make('thumbnail')
                        ->square()
                        ->label('Foto')
                        ->width('100%')
                        ->height('100%')
                        ->defaultImageUrl(function ($record) {
                            return $record->getUrl();
                        }),
                    // Tables\Columns\TextColumn::make('author')
                    //     ->label('Auteur')
                    //     ->sortable(),
                    // Tables\Columns\TextColumn::make('date')
                    //     ->label('Datum')
                    //     ->sortable()
                ])
            ])
            ->actions([
                $this->getQuickEditAction(),
            ]);
    }

    public function getQuickEditAction() {
        return Tables\Actions\EditAction::make()
            ->form(function ($record) {
                $is_multi_locale = false;
                return [
                    Grid::make()
                        ->schema([
                            Grid::make(2)->schema([
                                TextInput::make('author')
                                    ->label('Auteur')
                                    ->placeholder('Aauteur'),
                                TextInput::make('date')
                                    ->label('Datum')
                                    ->type('date'),
                            ]),
                            $is_multi_locale
                                ? null
                                : Textarea::make('description')
                                ->label('Beschrijving')
                                ->rows(4)
                                ->columnSpan('full'),
                        ])
                        ->columns(1)
                ];
            })
            ->fillForm(fn($record) => $record->getMeta())
            ->action(function ($record, $data) {
                $record->setMeta($data)->save();
            });
    }
}
