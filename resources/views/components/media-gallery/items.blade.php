@props([
    'model', 
    'truncate' => 0, 
    'videoIndicator' => null, 
    'itemSlot' => null, 
    'spotlightSlot' => null
])

@php
    $items = $model->media->sortBy('order_column')->values();
@endphp

@foreach ($items as $i => $item)
    @if ($i === 0)
        <x-media-gallery::merged-slot :merge="$spotlightSlot" class="col-span-full">
            <x-media-gallery::media-gallery.item :item="$item" :videoIndicator="$videoIndicator" :imgClass="$itemSlot->attributes->get('img-class') ?? $itemSlot->attributes->get('imgClass')" />
        </x-media-gallery::merged-slot>
    @else
        <x-media-gallery::merged-slot :merge="$itemSlot">
            <x-media-gallery::media-gallery.item :item="$item" :videoIndicator="$videoIndicator" :imgClass="$itemSlot->attributes->get('img-class') ?? $itemSlot->attributes->get('imgClass')" />
        </x-media-gallery::merged-slot>
    @endif
@endforeach
