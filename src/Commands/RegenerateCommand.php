<?php
    namespace Tjall\MediaGallery\Commands;

    use Spatie\MediaLibrary\Conversions\Commands\RegenerateCommand as SpatieRegenerateCommand;

    class RegenerateCommand extends SpatieRegenerateCommand {
        protected $signature = 'media-gallery:regenerate';
    }