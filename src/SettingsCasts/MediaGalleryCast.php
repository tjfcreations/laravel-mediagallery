<?php
    namespace Tjall\MediaGallery\SettingsCasts;

    use Illuminate\Support\Arr;
    use Tjall\MediaGallery\Models\MediaItem;
    use Tjall\MediaGallery\Support\MediaGallery;
    use Illuminate\Support\Collection;

    class MediaGalleryCast implements \Spatie\LaravelSettings\SettingsCasts\SettingsCast
    {
        /**
         * @param array|int[]|null $payload
         */
        public function get($payload): MediaGallery
        {
            $gallery = new MediaGallery();

            $ids = Arr::wrap($payload);
            if($ids) {
                foreach($ids as $id) {
                    $mediaItem = \Tjall\MediaGallery\Models\MediaItem::find($id);
                    if(!$mediaItem) continue;
                    
                    $gallery->add($mediaItem);
                }
            }

            $gallery = $gallery->sortBy('order_column');

            return $gallery;
        }

        /**
         * @param MediaGallery|null $gallery
         */
        public function set($payload): array
        {
            if($payload instanceof Collection) {
                $ids = [];

                foreach($payload->all() as $item) {
                    if($item instanceof MediaItem) {
                        $ids[] = $item->id;
                    }
                }

                return $ids;
            }

            return [];
        }
    }