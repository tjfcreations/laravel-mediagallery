<?php

namespace Tjall\MediaGallery;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Tjall\MediaGallery\Models\MediaItem;

interface HasMediaGallery extends HasMedia {
    public function getMediaModel(): string;
}
