<?php
    namespace Tjall\MediaGallery\Helpers;

    use Tjall\MediaGallery\InteractsWithMedia;
    use Tjall\MediaGallery\HasMedia;
    use Illuminate\Database\Eloquent\Model;

    class RecordWithMedia extends Model implements HasMedia {
        use InteractsWithMedia;
    }