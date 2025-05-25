<?php

namespace Tjall\MediaGallery\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;
use Filament\Support\Facades\FilamentIcon;
use Tjall\MediaGallery\Models\MediaItem;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaUpload extends FileUpload {
    protected string $view = 'laravel-mediagallery::forms.components.media-upload';

    public static function make(string $name = ''): static {
        return parent::make('_media-upload');
    }

    protected function setUp(): void {
        parent::setUp();

        $this
            ->multiple()
            ->hiddenLabel()
            ->columnSpanFull()
            ->image();
    }
}
