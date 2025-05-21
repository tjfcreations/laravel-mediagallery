<?php

namespace Tjall\MediaGallery;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tjall\MediaGallery\Models\MediaItem;

class MediaGalleryServiceProvider extends PackageServiceProvider {

    public function configurePackage(Package $package): void {
        $package
            ->name('laravel-mediagallery')
            ->hasViews('laravel-mediagallery')
            ->discoversMigrations();
    }
}
