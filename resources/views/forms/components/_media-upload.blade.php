@php
    $accept = implode(', ', $getAcceptedFileTypes());
@endphp

<div x-data="{ uploadedFile: null }"
    x-on:media-upload-start="$wire.mountFormComponentAction('data._media-gallery-editor', 'onUploadStart', { uploadIds: $event.detail.uploadIds })"
    x-on:media-upload-success="$wire.mountFormComponentAction('data._media-gallery-editor', 'onUploadSuccess', { uploadIds: $event.detail.uploadIds })">
    @props([
        'accept' => null,
    ])

    <div x-data="{
        isDropping: false,
        isUploading: false,
        uploadId: 0,
        progress: 0,
        init() {
            $store.uploads = {};
        },
        handleFileSelect(event) {
            if (!event.target.files.length) return;
            this.uploadFiles(event.target.files);
        },
        handleFileDrop(event) {
            if (!event.dataTransfer.files.length) return;
            this.uploadFiles(event.dataTransfer.files);
        },
        uploadFiles(files) {
            const uploadIds = Array.from(files).map(file => {
                return this.registerUpload({ file, dataUrl: URL.createObjectURL(file) });
            });
    
            this.dispatchMediaUploadEvent('start', uploadIds);
    
            $wire.uploadMultiple('files', files,
                success => {
                    this.dispatchMediaUploadEvent('success', uploadIds);
                    uploadIds.forEach(uploadId => this.updateUpload(uploadId, { isUploading: false }));
                },
                error => { //an error occured
                    this.dispatchMediaUploadEvent('error', uploadIds);
                    uploadIds.forEach(uploadId => this.updateUpload(uploadId, { isUploading: false }));
                },
                event => { //upload progress was made
                    uploadIds.forEach(uploadId => this.updateUpload(uploadId, { progress: event.detail.progress }));
                }
            )
        },
        registerUpload(data) {
            const uploadId = this.uploadId++;
            $store.uploads[uploadId] = { ...data, isUploading: true, progress: 0 };
    
            console.log($store);
    
            return uploadId;
        },
        updateUpload(uploadId, data) {
            $store.uploads[uploadId] = { ...$store.uploads[uploadId], ...data };
        },
        dispatchMediaUploadEvent(name, uploadIds, detail = {}) {
            const uploads = uploadIds.map(id => $store.uploads[id]);
            return $dispatch(`media-upload-${name}`, { uploads, uploadIds, ...detail });
        }
    }" class="w-full">
        <div class="flex flex-col items-center justify-center w-full relative" x-on:drop="isDroppingFile = false"
            x-on:drop.prevent="handleFileDrop($event)" x-on:dragover.prevent="isDroppingFile = true"
            x-on:dragleave.prevent="isDroppingFile = false">
            <x-filament::section class="w-full">
                <div class="absolute top-0 bottom-0 left-0 right-0 z-30 flex items-center justify-center bg-blue-500 opacity-90"
                    x-show="isDropping">
                    <span class="text-3xl text-white">Release file to upload!</span>
                </div>
                <label class="flex flex-col items-center justify-center w-full cursor-pointer" for="file-upload"
                    id="file-upload-label">
                    <p class="text-lg max-w-none">Sleep hier je bestanden naartoe</p>
                    <p class="max-w-none">of</p>
                    <x-filament::button tag="div" class="cursor-pointer mt-1">Bestanden
                        selecteren</x-filament::button>
                </label>
                <input type="file" id="file-upload" multiple x-on:change="handleFileSelect" class="hidden"
                    accept="{{ $accept }}" />
            </x-filament::section>
        </div>
    </div>

</div>
