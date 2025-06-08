<?php

namespace Tjall\MediaGallery\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Component;
use Illuminate\Support\Facades\Blade;
use Tjall\MediaGallery\Models\MediaItem;

class MediaGalleryEditorRepeater extends Repeater {
    protected function setUp(): void {
        parent::setUp();

        $this
            ->schema([
                $this->renderItemThumbnail('sm', null, true)
            ])
            ->extraItemActions([
                $this->getEditMetaAction(),
            ])
            ->reorderable('order_column')
            ->reorderableWithDragAndDrop(true)
            ->columnSpanFull()
            ->grid([
                'default' => 2,
                'md' => 3,
                'lg' => 4,
                'xl' => 5,
                '2xl' => 6,
            ])
            ->addAction(function (Action $action) {
                return $action->hidden();
            })
            ->registerActions([
                fn(MediaGalleryEditorRepeater $component): Action => $component->getUploadStartAction(),
                fn(MediaGalleryEditorRepeater $component): Action => $component->getUploadSuccessAction(),
                fn(MediaGalleryEditorRepeater $component): Action => $component->getDeleteAction()
            ])
            ->relationship('media')
            ->afterStateHydrated(static function (MediaGalleryEditorRepeater $component, ?array $state): void {
                $items = [];

                $media_items = $component->getMediaItemsFromState($state);
                foreach ($media_items as $media_item) {
                    $items["item-{$media_item->id}"] = [
                        'media_item' => $media_item,
                        'is_uploading' => false,
                        'meta' => $media_item->getMeta(),
                        'order_column' => $media_item->order_column
                    ];
                }

                $component->state($items);
                $component->callAfterStateUpdated();
            })
            ->saveRelationshipsUsing(function(MediaGalleryEditorRepeater $component) {
                return $component->handleSave();
            });
    }

    public function getMediaItemsFromState(array $state) {
        return collect($state)
            ->filter(fn($data) => isset($data['id']))
            ->map(fn($data) => MediaItem::find($data['id']))
            ->sortBy('order_column');
    }

    public function getUploadStartAction() {
        return Action::make('onUploadStart')
            ->action(function (array $arguments, MediaGalleryEditorRepeater $component) {
                $items = $component->getState();

                foreach ($arguments['uploadIds'] as $upload_id) {
                    $items["upload-{$upload_id}"] = [
                        'upload_id' => $upload_id,
                        'is_uploading' => true,
                        'meta' => []
                    ];
                }

                $component->state($items);

                $component->callAfterStateUpdated();
            });
    }

    public function getUploadSuccessAction() {
        return Action::make('onUploadSuccess')
            ->action(function (array $arguments, MediaGalleryEditorRepeater $component) {
                $items = $component->getState();

                foreach ($arguments['uploadIds'] as $upload_id) {
                    $items["upload-{$upload_id}"]['is_uploading'] = false;
                }

                $component->state($items);

                $component->callAfterStateUpdated();
            });
    }

    protected function handleSave() {
        $record = $this->getRecord();
        $items = $this->getState();
        $data = $this->getLivewire()->data;
        
        // merge all uploaded files into a single array
        $files = [];
        foreach($data as $key => $file) {
            if(!str_starts_with($key, '_media-gallery-editor-file-upload#')) continue;

            $item_id = explode('#', $key)[1];
            $files[$item_id] = $file;
        }
        
        // array for storing media items that should not be deleted
        $keep_media_items = [];
        foreach($files as $item_id => $file) {
            // skip if the item was uploaded, but then removed
            if(!isset($items[$item_id])) continue;

            // check if the media item already exists
            if(!isset($items[$item_id]['media_item'])) {
                // save the media item
                $items[$item_id]['media_item'] = $record
                    ->addMedia($file->getPathname())
                    ->toMediaCollection();
            }

            $keep_media_items[$items[$item_id]['media_item']->id] = true;
        }

        // update media item meta and order
        $item_order = 1;
        foreach($items as $item_id => $data) {
            if(!isset($data['media_item'])) continue;

            $media_item = $data['media_item'];
            $media_item->setMeta($data['meta']);
            $media_item->order_column = $item_order++;
            $media_item->save();

            $keep_media_items[$media_item->id] = true;
        }

        // delete media items that the user deleted
        foreach($record->media as $media_item) {
            if(isset($keep_media_items[$media_item->id])) continue;
            $media_item->delete();
        }

        $this->state($items);

        $this->callAfterStateUpdated();
    }

    protected function renderItemThumbnail(string $conversion = 'sm', ?array $item = null, ?bool $progress = false): Placeholder {
        $placeholder = Placeholder::make('thumbnail')
            ->content(function (Component $component) use ($conversion, $item, $progress) {
                $html = Blade::render('<x-media-gallery::filament.media-gallery-editor-repeater-item-thumbnail :component="$component" :conversion="$conversion" :item="$item" :progress="$progress" />', [
                    'conversion' => $conversion,
                    'component' => $component,
                    'item' => $item,
                    'progress' => $progress
                ]);
                return new HtmlString($html);     
            })
            ->hiddenLabel()
            ->columnSpanFull();

        return $placeholder;
    }

    protected function getItemMeta(string $item_id) {
        return $this->getState()[$item_id]['meta'];
    }

    protected function setItemMeta(string $item_id, array $meta) {
        $items = $this->getState();
        $items[$item_id]['meta'] = $meta;
        $this->state($items);
    }

    public function getEditMetaAction() {
        return Action::make('editMeta')
            ->label(__('filament-actions::edit.single.label'))
            ->icon('heroicon-m-pencil-square')
            ->form(function (array $arguments, MediaGalleryEditorRepeater $component) {
                $item = $this->getState()[$arguments['item']];

                $is_multi_locale = false;

                return [
                    $component->renderItemThumbnail('md', $item)
                        ->extraAttributes([
                            'class' => 'w-full aspect-square object-cover',
                            'style' => 'max-width: 10rem;'
                        ]),
                    Grid::make(2)
                        ->schema([
                            TextInput::make('author')
                                ->label('Auteur')
                                ->placeholder('Jan de Vries'),
                            TextInput::make('date')
                                ->label('Gemaakt op')
                                ->type('date'),
                            $is_multi_locale
                                ? null
                                : Textarea::make('description')
                                ->label('Beschrijving')
                                ->rows(4)
                                ->columnSpanFull(),
                        ])
                ];
            })
            ->modalHeading('Media bewerken')
            ->modalSubmitActionLabel('Sluiten')
            ->fillForm(function (array $arguments, MediaGalleryEditorRepeater $component) {
                return $this->getItemMeta($arguments['item']);
            })
            ->action(function (array $arguments, MediaGalleryEditorRepeater $component, array $data) {
                return $this->setItemMeta($arguments['item'], $data);
            });
    }
}
