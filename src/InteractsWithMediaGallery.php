<?php

namespace Tjall\MediaGallery;

use Plank\Mediable\Mediable;
use Tjall\MediaGallery\Models\Media;

trait InteractsWithMediaGallery {
    use Mediable;
    public function addMedia(Media $media) {
        return $this->attachMedia($media, ['media']);
    }
}
