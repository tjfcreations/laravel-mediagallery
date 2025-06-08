<?php

namespace Tjall\MediaGallery\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;

class Media extends Model {
    /** @use HasFactory<\Database\Factories\MediaFactory> */
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
        return '';
    }

    public static function fromPath(string $path) {
        dd($path);
    }

    public function isVideo() {
        return false;
    }

    public function serialize() {
        $sources = [];
        foreach (Media::CONVERSIONS as $name => $opts) {
            $sources[$name] = [ 
                'url' => $this->getUrl($name),
                'width' => $opts['width'],
                'height' => $opts['height']
            ];
        }

        return [
            'id' => $this->id,
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
