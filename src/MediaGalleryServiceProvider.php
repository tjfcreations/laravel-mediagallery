<?php

namespace Tjall\MediaGallery;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tjall\MediaGallery\Http\Livewire\MediaUpload;
use Tjall\MediaGallery\Commands\RegenerateCommand;
use Illuminate\Support\Facades\Config;
use Tjall\MediaGallery\Support\HashedPathGenerator;
use Tjall\MediaGallery\Support\HashedFileNamer;
use Tjall\MediaGallery\Models\MediaItem;

class MediaGalleryServiceProvider extends PackageServiceProvider {
    public function configurePackage(Package $package): void {
        $package
            ->name('laravel-mediagallery')
            ->hasViews('media-gallery')
            ->hasConfigFile('media-gallery')
            ->discoversMigrations()
            ->hasCommands([
                RegenerateCommand::class
            ]);
    }

    public function bootingPackage() {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'media-gallery');
        
        $this->publishes([
            __DIR__.'/../resources/js' => public_path('vendor/tjall/laravel-mediagallery/js'),
        ], 'laravel-assets');
        
        Config::set('media-library.media_model', MediaItem::class);
        Config::set('media-library.disk_name', config('media-gallery.disk_name', 'public'));
        Config::set('media-library.path_generator', HashedPathGenerator::class);
        Config::set('media-library.file_namer', HashedFileNamer::class);
    }
}
