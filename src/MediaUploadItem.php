<?php
namespace Tjall\MediaGallery;

use Tjall\MediaGallery\Models\MediaItem;
use Illuminate\Contracts\Support\Arrayable;

class MediaUploadItem implements Arrayable {
    public MediaItem $mediaItem;

    public function __construct(MediaItem $media_item) {
        $this->mediaItem = $media_item;
    }

    public function toArray(): array {
        return [
            'id' => $this->mediaItem->id,
            'exists' => $this->mediaItem->exists
        ];
    }
}