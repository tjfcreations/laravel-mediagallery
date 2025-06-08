<?php
    namespace Tjall\MediaGallery;

    use Plank\Mediable\MediaUploader as PlankMediaUploader;
    use Tjall\MediaGallery\Models\Media;

    class MediaUploader {
        protected static PlankMediaUploader $uploader;

        protected static function getUploader() {
            if(!isset(static::$uploader)) {
                static::$uploader = app(PlankMediaUploader::class);
            }

            return static::$uploader;
        }

        public static function importPath(string $disk, string $path) {
            return static::getUploader()
                ->importPath($disk, $path);
        }

        public static function fromSource(mixed $source) {
            return static::getUploader()
                ->fromSource($source)
                ->toDisk(config('media-gallery.disk_name'))
                ->useHashForFilename('md5');
        }
    }