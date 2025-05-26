<?php

namespace Tjall\MediaGallery;

use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithSpatieMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tjall\MediaGallery\Models\MediaItem;

trait InteractsWithMedia {
    use InteractsWithSpatieMedia;

    public function getMediaModel(): string {
        return MediaItem::class;
    }

    public function registerMediaCollections(): void {
        $this->addMediaCollection('default')
            ->useDisk(config('media-gallery.disk_name'));
    }

    public function registerMediaConversions(?Media $media = null): void {
        $this->addMediaConversion('xs')
            ->fit(Fit::Contain, 180, 180)
            ->sharpen(10);

        $this->addMediaConversion('sm')
            ->fit(Fit::Contain, 360, 360)
            ->sharpen(10);

        $this->addMediaConversion('md')
            ->fit(Fit::Contain, 720, 720);

        $this->addMediaConversion('lg')
            ->fit(Fit::Contain, 1080, 1080);

        $this->addMediaConversion('xl')
            ->fit(Fit::Contain, 1440, 1440);
    }
}
