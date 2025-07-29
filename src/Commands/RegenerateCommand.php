<?php
    namespace Tjall\MediaGallery\Commands;

    use Spatie\MediaLibrary\Conversions\Commands\RegenerateCommand as SpatieRegenerateCommand;

    class RegenerateCommand extends SpatieRegenerateCommand {
        protected $signature = 'media-gallery:regenerate {modelType?} {--ids=*}
        {--only=* : Regenerate specific conversions}
        {--starting-from-id= : Regenerate media with an id equal to or higher than the provided value}
        {--X|exclude-starting-id : Exclude the provided id when regenerating from a specific id}
        {--only-missing : Regenerate only missing conversions}
        {--with-responsive-images : Regenerate responsive images}
        {--force : Force the operation to run when in production}
        {--queue-all : Queue all conversions, even non-queued ones}';
    }