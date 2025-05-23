@php
    use App\Helpers\Locale;
    use Illuminate\Support\Facades\App;

    $expandAction = $getAction($getExpandActionName());
    $deleteAction = $getAction($getDeleteActionName());
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="mb-4" 
        x-on:media-upload-start.window="onMediaUploadStart($event.detail.files)",
        x-on:media-upload-success.window="onMediaUploadSuccess($event.detail.files)" 
        x-on:media-upload-error.window="onMediaUploadError($event.detail.files)" 
        x-data="{ 
            items: @js($field->getItems()), 
            uploads: {},
            onMediaUploadStart(files) {
                for(const file of files) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.uploads[file.id] = {
                            name: file.inputFile.name,
                            size: file.inputFile.size,
                            dataURL: e.target.result,
                            isPreview: true
                        };
                    }
                    reader.readAsDataURL(file.inputFile);
                }
            },
            onMediaUploadSuccess(files) {
                this.removeUploads(files);
            },
            onMediaUploadError(files) {
                this.removeUploads(files);
            },
            removeUploads(files) {
                return;
                files.forEach(file => {
                    if (!this.uploads[file.id]) return;
                    delete this.uploads[file.id];
                });
            }
        }">
        {{-- @livewire('media-gallery-editor-items', [
            'media' => $field->getRecord()->getMedia(),
            'deleteAction' => $deleteAction,
            'expandAction' => $expandAction
        ]) --}}
        <x-filament::grid x-sortable class="gap-4 mb-4" default="2" md="3" lg="4" xl="5" x-ref="mediaItemsContainer">
            @foreach ($field->getItems() as $item)
                @php
                    if (!$item->isPreview) {
                        $expandActionWithArgs = $expandAction(['item' => $item->id]);
                        $deleteActionWithArgs = $deleteAction(['item' => $item->id]);
                    }
                @endphp
                <x-laravel-mediagallery::media-gallery-editor-item
                    :id="$this->getId()" 
                    :field="$field"
                    :item="$item"
                    :expandAction="$expandActionWithArgs" 
                    :deleteAction="$deleteActionWithArgs" />
            @endforeach
            <template x-for="(upload, id) in uploads" :key="id" x-ref="uploads">
                <x-laravel-mediagallery::media-gallery-editor-item
                    x-bind:data-upload-id="id"
                    x-on:wire:click="mountFormComponentAction('data.gallery', 'expand', { item: 3 })"
                    {{-- class="opacity-50 pointer-events-none" --}}
                    :id="$this->getId()" 
                    :field="$field"
                    :expandAction="$expandAction"
                    :deleteAction="$deleteAction" />
            </template>
        </x-filament::grid>
        <div class="flex flex-row justify-center w-full">
            <livewire:file-upload />

            {{-- @livewire('x-laravel-mediagallery::media-item') --}}

            {{-- <x-filament::modal width="5xl">
        <x-slot:trigger>
            <x-filament::button>Media toevoegen</x-filament::button>
        </x-slot>

        <x-slot:heading>
            Media toevoegen
        </x-slot:heading>

        <livewire:file-upload />
    </x-filament::modal> --}}
        </div>
    </ul>
</x-dynamic-component>
