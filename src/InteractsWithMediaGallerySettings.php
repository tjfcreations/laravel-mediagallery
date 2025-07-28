<?php
namespace Tjall\MediaGallery;

use Tjall\MediaGallery\Forms\Components\MediaGalleryEditorRepeater;

trait InteractsWithMediaGallerySettings {
    public function saveMediaGallerySettings(): void
    {
        foreach ($this->form->getFlatComponents() as $component) {
            if ($component instanceof MediaGalleryEditorRepeater) {
                $component->handleSave();
            }
        }
    }
}
