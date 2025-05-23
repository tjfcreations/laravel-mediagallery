@props(['id', 'field', 'item' => null, 'expandAction' => null, 'deleteAction' => null])

<div {{ $attributes }}
    @if (isset($item)) 
        wire:key="{{ $id }}.{{ $field->getStatePath() }}.{{ $field::class }}.items.{{ $item->id }}"
    @else
        wire:key="{{ $id }}.{{ $field->getStatePath() }}.{{ $field::class }}.items.new"
    @endif

    x-data="{ isDragging: false }"
    @if (isset($item)) 
        x-sortable-item="{{ $item->id }}"
        x-sortable-handle x-on:dragstart="isDragging = true;" 
        x-on:dragend="isDragging = false;" 
    @endif>
    <div>
        <div class="fi-fo-repeater-item-header flex items-center gap-x-3 overflow-hidden px-4 py-3">
            <ul class="flex items-center gap-x-3">
                <li>{{ $expandAction }}</li>
            </ul>
            <ul class="ms-auto flex items-center gap-x-3">
                <li>{{ $deleteAction }}</li>
            </ul>
        </div>
        <div class="fi-fo-repeater-item-content p-4 relative">
            <div class="rounded-md overflow-hidden">
                @if (isset($item))
                    <x-laravel-mediagallery::media-item class="w-full aspect-square" :item="$item->media" size="sm" />
                @else
                    <x-laravel-mediagallery::media-item class="w-full aspect-square blur-lg" 
                        x-bind:src="upload.dataURL"
                        size="sm" />
                    <x-filament::loading-indicator class="absolute top-0 left-0 right-0 bottom-0 m-auto h-12" />
                @endif
            </div>
        </div>
    </div>
</div>
