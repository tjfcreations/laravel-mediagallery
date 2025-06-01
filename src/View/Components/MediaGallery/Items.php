<?php
    namespace Tjall\MediaGallery\View\Components\MediaGallery;

    use Illuminate\View\Component;
    use Illuminate\View\View;

    class Items extends Component {
        public function getItem() {
            dd($this);
        }

        public function render(): View
        {
            return view('laravel-mediagallery::media-gallery.items');
        }
    }