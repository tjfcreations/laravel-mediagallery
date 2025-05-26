<?php

namespace Tjall\MediaGallery;

use Spatie\MediaLibrary\HasMedia as HasSpatieMedia;

interface HasMedia extends HasSpatieMedia {
    public function getMediaModel(): string;
}
