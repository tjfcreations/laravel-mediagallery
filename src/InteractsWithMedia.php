<?php

namespace Tjall\MediaGallery;

use Spatie\MediaLibrary\InteractsWithMedia as InteractsWithSpatieMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tjall\MediaGallery\Models\MediaItem;

trait InteractsWithMedia {
    use InteractsWithSpatieMedia;

    public function getMediaModel(): string {
        return MediaItem::class;
    }

    public function getFirstMediaUrl(string $conversion): string {
        $media = $this->getFirstMedia();
        return $media->getUrl($conversion);
    }

    public function registerMediaCollections(): void {
        $this->addMediaCollection('default')
            ->useDisk(config('media-gallery.disk_name'));
    }

    public function registerMediaConversions(?Media $media = null): void {
        foreach(MediaItem::CONVERSIONS as $name => $opts) {
            $this->addMediaConversion($name)
                ->fit($opts['fit'])
                ->width($opts['width'])
                ->height($opts['height'])
                ->sharpen($opts['sharpen']);
        }
    }
}
