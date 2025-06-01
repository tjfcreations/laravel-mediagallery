@props([
    'item' => null,
    'slot' => null,
    'imgClass' => '',
    'videoIndicator' => null
])

<button {{ $attributes->class('overflow-hidden group relative block') }} 
    x-init="modal.registerItem(@js($item->serialize()), $el.querySelector('img'))"
    x-on:click="modal.openByUuid('{{ $item->uuid }}')">
    
    @if(!blank($slot))
        {{ $slot }}
    @else
        <x-media-gallery::media-item 
            :item="$item" 
            class="{{ $imgClass }}"
            size="xs" />

        @if($item->isVideo())
            <x-media-gallery::merged-slot 
                :merge="$videoIndicator"
                class="absolute right-2 bottom-2 bg-white rounded-md px-2 py-1">
                Video
            </x-media-gallery::merged-slot>
        @endif
    @endif

</button>