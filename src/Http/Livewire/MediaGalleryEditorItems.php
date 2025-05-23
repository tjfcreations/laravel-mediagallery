<?php

namespace Tjall\MediaGallery\Http\Livewire;

use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Filament\Forms\Components\Actions\Action;

class MediaGalleryEditorItems extends Component {
    public MediaCollection $media;
    public Action $deleteAction;
    public Action $expandAction;

    protected $previews = [];

    public function render() {
        return view('laravel-mediagallery::livewire.media-gallery-editor-items', [
            'items' => $this->getItems(),
            'deleteAction' => $this->deleteAction,
            'expandAction' => $this->expandAction
        ]);
    }



}