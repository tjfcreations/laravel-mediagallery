<?php
    namespace Tjall\MediaGallery\Support;

    use Tjall\MediaGallery\InteractsWithMedia;
    use Tjall\MediaGallery\HasMedia;
    use Illuminate\Database\Eloquent\Model;

    class SettingsPropertyMediaContainer extends Model implements HasMedia {
        use InteractsWithMedia;
    }