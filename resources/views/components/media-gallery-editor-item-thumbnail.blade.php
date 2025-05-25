@props([
    'component',
    'conversion',
    'item' => null,
    'progress' => false
])

@php
    use Filament\Filament;

    $item ??= $component->getContainer()->getRawState();

    $is_upload = isset($item['upload_id']);
    $alpine_store = $is_upload ? '$store.uploads[' . $item['upload_id'] . ']' : '{}';

    $colors = filament()->getPanel()->getColors();
@endphp

<div style="position: relative; overflow: hidden;" class="rounded-md">

    <img 
        alt=""  
        @if($is_upload)
            x-bind:src="{{ $alpine_store }}.dataUrl"
        @elseif(isset($item['media_item']))
            src="{{ $item['media_item']->getUrl($conversion) }}"
        @endif

        x-bind:style="'width: 100%; object-fit: cover; aspect-ratio: 1 / 1; transition: 300ms filter ease-out;' + ({{ $alpine_store }}.isUploading ? 'filter: blur(8px);' : '')"
        />

    @if($is_upload)
        @if($progress)
            <div 
                x-bind:style="'position: absolute; z-index: 10; height: 4px; bottom: 0; left: 0; right: 0; background-color: rgba(255, 255, 255, 0.5); transition: 300ms opacity ease-out;' + ({{ $alpine_store }}.isUploading ? '' : 'opacity: 0;')">
                <div x-bind:style="'background: rgb({{ $colors['primary']['500'] }}); height: 100%; width: '+{{ $alpine_store }}.progress+'%;'"></div>
            </div>
        @endif

        <x-filament::loading-indicator 
            x-bind:style="'position: absolute; width: 30%; top: 0; right: 0; bottom: 0; left: 0; margin: auto; z-index: 10; transition: 300ms opacity ease-out;' + ({{ $alpine_store }}.isUploading ? '' : 'opacity: 0;')" />
    @endif
</div>