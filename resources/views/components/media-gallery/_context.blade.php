<script src="{{ asset('vendor/tjall/media-gallery/js/modal/MediaModal.js') }}" defer></script>
<script src="{{ asset('vendor/tjall/media-gallery/js/modal/MediaModalItem.js') }}" defer></script>
<script src="{{ asset('vendor/tjall/media-gallery/js/modal/MediaModalContent.js') }}" defer></script>

<div x-data="{ modal: new MediaModal() }">
    {{ $slot }}
</div>