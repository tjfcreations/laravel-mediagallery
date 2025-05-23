@props([
    
])

<div x-data="{
    isDropping: false,
    isUploading: false,
    uploadIndex: 0,
    uploadedFiles: [],
    uploadingFiles: [],
    uploadingPreviews: [],
    progress: 0,
    handleFileSelect(event) {
        if (!event.target.files.length) return;
        this.uploadFiles(event.target.files);
    },
    handleFileDrop(event) {
        if (!event.dataTransfer.files.length) return;
        this.uploadFiles(event.dataTransfer.files);
    },
    handleFilesUpdate(event) {
        console.log({ event });
        // this.uploadedFiles = event.detail[0].files;
    },
    uploadFiles(inputFiles) {
        const files = Object.values(inputFiles).map(inputFile => ({ inputFile, id: this.uploadIndex++ }));

        $wire.dispatch('media-upload-start', { files });

        $wire.uploadMultiple('files', inputFiles,
            success => {
                console.log('success', success);
                $wire.dispatch('media-upload-success', { files });
            },
            error => { //an error occured
                $wire.dispatch('media-upload-error', { files });
                console.log('error', error);
            },
            event => { //upload progress was made
                this.progress = event.detail.progress;
            }
        )
    },
    getAllFiles() {
        const files = [];

        this.uploadedFiles.forEach(file => {
            files.push({
                clientName: file.name,
                size: file.size,
                isUploaded: true
            });
        })

        this.uploadingFiles.forEach(file => {
            files.push({
                clientName: file.name,
                size: file.size,
                isUploaded: false
            });
        })
        console.log(files);

        return files;
    }
}" x-on:files:update="handleFilesUpdate" class="w-full">
    <div class="flex flex-col items-center justify-center w-full" x-on:drop="isDroppingFile = false"
        x-on:drop.prevent="handleFileDrop($event)" x-on:dragover.prevent="isDroppingFile = true"
        x-on:dragleave.prevent="isDroppingFile = false">
        <template x-for="file in $wire.get('filenames')">
            <span x-text="file"></span>
        </template>
        <x-filament::section class="w-full">
            <div class="absolute top-0 bottom-0 left-0 right-0 z-30 flex items-center justify-center bg-blue-500 opacity-90"
                x-show="isDropping">
                <span class="text-3xl text-white">Release file to upload!</span>
            </div>
            <label class="flex flex-col items-center justify-center w-full cursor-pointer" for="file-upload"
                id="file-upload-label">
                <p class="text-lg max-w-none">Sleep hier je bestanden naartoe</p>
                <p class="max-w-none">of</p>
                <x-filament::button tag="div" class="cursor-pointer mt-1">Bestanden selecteren</x-filament::button>
            </label>
            <input type="file" id="file-upload" multiple x-on:change="handleFileSelect" class="hidden"
                wire:model="files" />
            {{-- <ul class="mt-5 rounded- ring-1 ring-gray-950/10 dark:ring-white/20 divide-y divide-gray-100 dark:divide-white/10 w-full">
            <template x-for="file in getAllFiles()">
                <li class="p-4 flex flex-row">
                    <div>
                        <div class="prose max-w-none">
                            <span x-text="file.clientName"></span>
                            <span x-text="`(${filesize(file.size, { spacer: '' })})`"></span>
                        </div>
                        <div class="bg-gray-200 h-[4px] w-full mt-3 rounded" x-show="!file.isUploaded"> 
                            <div
                                class="bg-secondary-700 h-[4px] rounded"
                                style="transition: width 250ms"
                                :style="`width: ${progress}%;`">
                            </div>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <button x-on:click="@this.removeUpload('files', file.name)" x-show="file.isUploaded">XX</button>
                    </div>
                </li>
            </template>
        </ul> --}}
        </x-filament::section>
    </div>
</div>
