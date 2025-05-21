@props([
    'item' => null,
    'field' => null
])

<div class="w-full flex flex-col gap-4">
    <div class="w-full flex flex-row gap-4">
        <x-filament::input.wrapper class="w-full">
            <x-filament::input wire:model.defer="{{ $field->getStatePath() }}.meta.{{ $item->id }}.author"
                placeholder="Auteur" type="text" />
        </x-filament::input.wrapper>
        <x-filament::input.wrapper>
            <x-filament::input wire:model.defer="{{ $field->getStatePath() }}.meta.{{ $item->id }}.date"
                placeholder="Datum" type="date" />
        </x-filament::input.wrapper>

    </div>
    <div class="w-full">
        @if (Locale::isMultiLocale())
        @else
            <x-filament::input.wrapper class="fi-fo-textarea w-full">
                <textarea
                    wire:model.defer="{{ $field->getStatePath() }}.meta.{{ $item->id }}.messages.{{ App::currentLocale() }}.description"
                    class="block h-full w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] sm:text-sm sm:leading-6"></textarea>
            </x-filament::input.wrapper>
        @endif
    </div>
</div>
