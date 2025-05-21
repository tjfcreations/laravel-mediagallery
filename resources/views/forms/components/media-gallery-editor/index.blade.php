@php
    use App\Helpers\Locale;
    use Illuminate\Support\Facades\App;

    $expandAction = $getAction($getExpandActionName());
    $deleteAction = $getAction($getDeleteActionName());
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <ul>
        <x-filament::grid x-sortable class="gap-4" default="2" md="3" lg="4" xl="5">
            @foreach($field->getItems() as $item)
                @php
                    $expandAction = $expandAction(['item' => $item->id]);
                    $deleteAction = $deleteAction(['item' => $item->id]);
                @endphp
                <li 
                    wire:key="{{ $this->getId() }}.{{ $field->getStatePath() }}.{{ $field::class }}.item"
                    x-data="{ isDragging: false }"
                    x-sortable-item="{{ $item->id }}"
                    x-sortable-handle
                    x-on:dragstart="isDragging = true;"
                    x-on:dragend="isDragging = false;"
                    x-bind:class="`${isDragging ? '!cursor-grabbing' : '!cursor-grab'} fi-fo-repeater-item divide-y divide-gray-100 rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-white/5 dark:ring-white/10 select-none`">
                    <div class="fi-fo-repeater-item-header flex items-center gap-x-3 overflow-hidden px-4 py-3">
                        <ul class="flex items-center gap-x-3">
                            <li>{{ $expandAction }}</li>
                        </ul>
                        <ul class="ms-auto flex items-center gap-x-3">
                            <li>{{ $deleteAction }}</li>
                        </ul>
                    </div>
                    <div class="fi-fo-repeater-item-content p-4">
                        <div class="flex flex-row gap-4">
                            <x-laravel-mediagallery::media-item
                                {{ $attributes->class([
                                    'rounded-md',
                                    'w-full h-full',
                                ]) }} 
                                :item="$item" 
                                size="sm" />
                        </>
                    </div>
                </li>
            @endforeach
            <li class="w-full">
                <div class="flex flex-row justify-center w-full">
                    {{-- <livewire:file-upload /> --}}

                    {{-- <x-filament::modal width="5xl">
                        <x-slot:trigger>
                            <x-filament::button>Media toevoegen</x-filament::button>
                        </x-slot>

                        <x-slot:heading>
                            Media toevoegen
                        </x-slot:heading>

                        <livewire:file-upload />
                    </x-filament::modal> --}}
                </div>
            </li>
        </x-filament::grid>
    </ul>
</x-dynamic-component>
