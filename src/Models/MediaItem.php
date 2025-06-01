<?php

namespace Tjall\MediaGallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Image\Enums\Fit;

class MediaItem extends Media {
    /** @use HasFactory<\Database\Factories\MediaItemFactory> */
    use HasFactory;

    const CONVERSIONS = [
        'xs' => ['width' => 180, 'height' => 180, 'fit' => Fit::Contain, 'sharpen' => 10 ],
        'sm' => ['width' => 360, 'height' => 360, 'fit' => Fit::Contain, 'sharpen' => 10 ],
        'md' => ['width' => 720, 'height' => 720, 'fit' => Fit::Contain ],
        'lg' => ['width' => 1080, 'height' => 1080, 'fit' => Fit::Contain ],
        'xl' => ['width' => 1440, 'height' => 1440, 'fit' => Fit::Contain ],
    ];

    protected $table = 'media_items';

    public function getUrl(string $conversionName = ''): string {
        if ($this->size <= 1) {
            $opts = MediaItem::CONVERSIONS[$conversionName] ?? MediaItem::CONVERSIONS['md'];
            return "https://picsum.photos/seed/{$this->id}/{$opts['width']}/{$opts['height']}";
        }

        if($this->hasGeneratedConversion($conversionName)) {
            return parent::getUrl($conversionName);
        }

        return parent::getUrl();
    }

    public function isVideo() {
        return 1;
    }

    public function serialize() {
        $sources = [];
        foreach (MediaItem::CONVERSIONS as $name => $opts) {
            $sources[$name] = [ 
                'url' => $this->getUrl($name),
                'width' => $opts['width'],
                'height' => $opts['height']
            ];
        }

        return [
            'uuid' => $this->uuid,
            'sources' => $sources,
            'meta' => $this->getMeta()
        ];
    }

    public function getDescription() {
        return @$this->getMeta()['description'];
    }

    public function getMeta() {
        return $this->getCustomProperty('meta', []);
    }

    public function setMeta(array $meta) {
        $this->setCustomProperty('meta', $meta);

        return $this;
    }
}
