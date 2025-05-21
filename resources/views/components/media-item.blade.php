@props([
    'item',
    'class' => '',
    'size' => null
])

<img 
    {{ $attributes->class(['object-cover', $class]) }}
    @if(!isset($size))
        srcset="
            {{ $item->getUrl('xs') }} 150w,
            {{ $item->getUrl('sm') }} 300w,
            {{ $item->getUrl('md') }} 600w,
            {{ $item->getUrl('lg') }} 1200w,
            {{ $item->getUrl('xl') }} 1920w
        "
        sizes="(max-width: 1200px) 50vw, 33vw"
    @endif
    src="{{ $item->getUrl(isset($size) ? $size : 'md') }}" 
    height="1920" 
    width="1920" 
    alt="{{ $item->getDescription() }}" />