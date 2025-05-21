<?php

namespace Tjall\MediaGallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaItem extends Media {
    /** @use HasFactory<\Database\Factories\MediaItemFactory> */
    use HasFactory;

    protected $table = 'media_items';

    public function getUrl(string $conversionName = ''): string {
        if($this->size <= 1) {
            return "https://picsum.photos/seed/{$this->id}/900/900";
        }

        return parent::getUrl($conversionName);
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

    /**
     * Set the filepath.
     */
    public function setResourcePath(string $path) {
        $this->addMedia($path)
            ->preservingOriginal()
            ->withResponsiveImages()
            ->toMediaCollection('images');

        return $this;
    }

    public function isVideo() {
        return $this->type == 'video' || $this->type == 'embed';
    }

    public function getMeta() {
        return [
            'author' => $this->getCustomProperty('meta.author'),
            'date' => $this->getCustomProperty('meta.date'),
            'description' => $this->getCustomProperty('meta.description')
        ];
    }

    public function setMeta(array $meta) {
        return $this->setCustomProperty('meta', $meta);
    }

    public function getDescription() {
        return $this->getMeta()['description'];
    }
}
