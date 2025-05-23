<?php

namespace Tjall\MediaGallery;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tjall\MediaGallery\Http\Livewire\FileUpload;
use Tjall\MediaGallery\Models\MediaItem;
use Livewire\Livewire;
use Tjall\MediaGallery\Http\Livewire\MediaGalleryEditorItems;

class MediaGalleryServiceProvider extends PackageServiceProvider {

    public function configurePackage(Package $package): void {
        $package
            ->name('laravel-mediagallery')
            ->hasViews('laravel-mediagallery')
            ->discoversMigrations();
    }

    public function boot() {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-mediagallery');

        Livewire::component('file-upload', FileUpload::class);
        Livewire::component('media-gallery-editor-items', MediaGalleryEditorItems::class);
    }
}
