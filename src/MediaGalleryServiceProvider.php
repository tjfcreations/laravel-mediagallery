<?php

namespace Tjall\MediaGallery;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tjall\MediaGallery\Http\Livewire\MediaUpload;
use Tjall\MediaGallery\Models\MediaItem;
use Livewire\Livewire;
use Tjall\MediaGallery\Http\Livewire\MediaGalleryEditorItems;

class MediaGalleryServiceProvider extends PackageServiceProvider {

    public function configurePackage(Package $package): void {
        $package
            ->name('laravel-mediagallery')
            ->hasViews('laravel-mediagallery')
            ->hasConfigFile('media-gallery')
            ->discoversMigrations();
    }

    public function boot() {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-mediagallery');
    }
}
