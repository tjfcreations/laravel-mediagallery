<?php

namespace Tjall\MediaGallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;

class MediaItem extends Media {
    /** @use HasFactory<\Database\Factories\MediaItemFactory> */
    use HasFactory;

    protected $table = 'media_items';

    public function getUrl(string $conversionName = ''): string {
        if ($this->size <= 1) {
            return "https://picsum.photos/seed/{$this->id}/900/900";
        }

        if($this->hasGeneratedConversion($conversionName)) {
            return parent::getUrl($conversionName);
        }

        return parent::getUrl();
    }

    /**
     * Get the resource URL.
     */
    public function getSource(string $size) {
        if ($this->embed()->exists()) {
            return $this->embed()->first()->getThumbnailUrl($size);
        }

        return $this->getFirstMediaUrl('images', $size);
    }

    public function isVideo() {
        return $this->type == 'video' || $this->type == 'embed';
    }

    public function getMeta() {
        return $this->getCustomProperty('meta', []);
    }

    public function setMeta(array $meta) {
        $this->setCustomProperty('meta', $meta);

        return $this;
    }
}
