<script src="{{ asset('js/tjall/media-gallery/modal/MediaModal.js') }}" defer></script>
<script src="{{ asset('js/tjall/media-gallery/modal/MediaModalItem.js') }}" defer></script>
<script src="{{ asset('js/tjall/media-gallery/modal/MediaModalContent.js') }}" defer></script>

<div x-data="{ modal: new MediaModal() }">
    {{ $slot }}
</div>