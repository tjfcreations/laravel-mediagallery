<?php

namespace Tjall\MediaGallery\Forms\Components;

use Filament\Forms\Components\Grid;

class MediaGalleryEditor extends Grid {
    protected function setUp(): void {
        parent::setUp();

        $this
            ->columns(1)
            ->schema([
                MediaGalleryEditorRepeater::make('_media-gallery-editor-repeater')
                    ->label('Galerij')
                    ->extraFieldWrapperAttributes(function (array $state) {
                        return [
                            'style' => count($state) ? 'margin-bottom: -0.5rem;' : 'margin-bottom: -1.5rem;'
                        ];
                    }),
                MediaGalleryEditorFileUpload::make('_media-gallery-editor-file-upload')
                    ->label('Media uploaden')
                    ->hiddenLabel()
            ]);
    }
}
