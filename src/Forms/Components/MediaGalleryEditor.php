<?php

namespace Tjall\MediaGallery\Forms\Components;

use Closure;
use Tjall\ModelTranslations\Helpers\ModelTranslations;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Collection;
use Tjall\MediaGallery\Models\MediaItem;

class MediaGalleryEditor extends Field {
    protected string $view = 'laravel-mediagallery::forms.components.media-gallery-editor';

    protected ?Closure $modifyDeleteActionUsing = null;
    protected ?Closure $modifyExpandActionUsing = null;

    public array $previews = [];

    protected function setUp(): void {
        parent::setUp();

        $this->columnSpanFull();

        $this->registerActions([
            fn(MediaGalleryEditor $component): Action => $component->getDeleteAction(),
            fn(MediaGalleryEditor $component): Action => $component->getExpandAction()
        ]);

        $this->afterStateHydrated(function (MediaGalleryEditor $component, ?array $state) {
            $media = $component->getRecord()->getMedia();

            $meta = [];

            foreach ($media as $item) {
                $meta[$item->id] = $item->getMeta();
            }

            $component->state([
                'meta' => $meta,
            ]);
        });

        $this->dehydrateStateUsing(function (MediaGalleryEditor $component) {
            $state = $component->getState();
            $meta = $state['meta'] ?? [];

            dd($state['meta']);
            foreach ($state['meta'] as $media_item_id => $meta) {
                $item = MediaItem::find($media_item_id);
                $item->setMeta($meta);
                $item->save();
            }

            return $state;
        });
    }

    public function getItems(): array {
        $items = [];

        foreach ($this->getRecord()->getMedia() as $media_item) {
            $items[] = (object) ['media' => $media_item, 'id' => $media_item->id, 'isPreview' => false];
        }

        // foreach ($this->previews as $file) {
        //     $items[] = (object) ['file' => $file, 'id' => $file->getFilename(), 'isPreview' => true];
        // }

        return $items;
    }

    protected function getExpandModalForm(Form $form, array $arguments) {
        $is_multi_locale = ModelTranslations::isMultiLocale();

        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make("author")
                                ->label('Auteur')
                                ->placeholder('Auteur'),
                            TextInput::make("date")
                                ->label('Datum')
                                ->type('date'),
                        ]),
                        $is_multi_locale
                            ? null
                            : Textarea::make("description")
                            ->label('Beschrijving')
                            ->rows(4)
                            ->columnSpan('full'),
                    ])
                    ->columns(1)
            ]);
    }

    public function getDeleteAction(): Action {
        $action = Action::make($this->getDeleteActionName())
            ->label(__('filament-forms::components.repeater.actions.delete.label'))
            ->icon(FilamentIcon::resolve('forms::components.repeater.actions.delete') ?? 'heroicon-m-trash')
            ->color('primary')
            ->action(function (array $arguments, MediaGalleryEditor $component): void {
                $items = $component->getState();
                unset($items[$arguments['item']]);

                $component->state($items);

                $component->callAfterStateUpdated();
            })
            ->iconButton()
            ->size(ActionSize::Small);

        if ($this->modifyDeleteActionUsing) {
            $action = $this->evaluate($this->modifyDeleteActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function deleteAction(?Closure $callback): static {
        $this->modifyDeleteActionUsing = $callback;

        return $this;
    }

    public function getDeleteActionName(): string {
        return 'delete';
    }

    public function getExpandAction(): Action {
        $action = Action::make($this->getExpandActionName())
            ->label(__('filament-forms::components.builder.actions.edit.label'))
            ->icon(FilamentIcon::resolve('forms::components.repeater.actions.edit') ?? 'heroicon-m-pencil-square')
            ->color('primary')
            ->iconButton()
            ->size(ActionSize::Small)
            ->form(function (Form $form, array $arguments, MediaGalleryEditor $component) {
                return $this->getExpandModalForm($form, $arguments, $component);
            })
            ->mountUsing(function (Form $form, array $arguments, MediaGalleryEditor $component) {
                $item_id = $arguments['item'];
                $meta = $component->getState()['meta'][$item_id];

                $form->fill($meta);
            })
            ->action(function (array $arguments, MediaGalleryEditor $component, array $data): void {
                $state = $component->getState();
                $item_id = $arguments['item'];

                // Merge new meta data from the modal form
                $state['meta'][$item_id] = $data;

                $component->state($state);
                $component->callAfterStateUpdated();
            });

        if ($this->modifyExpandActionUsing) {
            $action = $this->evaluate($this->modifyExpandActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        return $action;
    }

    public function expandAction(?Closure $callback): static {
        $this->modifyExpandActionUsing = $callback;

        return $this;
    }

    public function getExpandActionName(): string {
        return 'expand';
    }

    // public function getReorderAction(): Action {
    //     $action = Action::make($this->getReorderActionName())
    //         ->label(__('filament-forms::components.repeater.actions.reorder.label'))
    //         ->icon(FilamentIcon::resolve('forms::components.repeater.actions.reorder') ?? 'heroicon-m-arrows-up-down')
    //         ->color('gray')
    //         ->action(function (array $arguments, MediaGalleryEditor $component): void {
    //             $items = [
    //                 ...array_flip($arguments['items']),
    //                 ...$component->getState(),
    //             ];

    //             $component->state($items);

    //             $component->callAfterStateUpdated();
    //         })
    //         ->livewireClickHandlerEnabled(false)
    //         ->iconButton()
    //         ->size(ActionSize::Small);

    //     return $action;
    // }

    // public function getReorderActionName(): string {
    //     return 'reorder';
    // }
}
