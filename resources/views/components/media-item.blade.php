@props([
    'item' => null,
    'class' => '',
    'size' => null,
    'thumbnail' => false
])

<img 
    {{ $attributes->class(['object-cover', $class]) }}
    @if(isset($item))
        src="{{ $item->getUrl(isset($size) ? $size : 'md') }}" 
        alt="{{ $item->getDescription() }}"
        
        @if(!isset($size))
            srcset="
                {{ $item->getUrl('xs') }} 180w,
                {{ $item->getUrl('sm') }} 360w,
                {{ $item->getUrl('md') }} 720w,
                {{ $item->getUrl('lg') }} 1080w,
                {{ $item->getUrl('xl') }} 1440w
            "
            sizes="(max-width: 800px) 90vw, (max-width: 1200px) 50vw, 33vw"
        @endif
    @endif
    height="1920" 
    width="1920"  />