@php
    /** @var string $whatsappText */
@endphp

<div
    x-data="{ copied: false }"
    class="flex flex-col gap-3"
>
    <textarea
        x-ref="text"
        readonly
        rows="10"
        class="block w-full rounded-lg border border-gray-200 bg-gray-50 font-mono text-sm text-gray-800 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
    >{{ $whatsappText }}</textarea>

    <div class="flex items-center gap-3">
        <x-filament::button
            type="button"
            color="success"
            icon="heroicon-o-clipboard-document"
            x-on:click="navigator.clipboard.writeText($refs.text.value).then(() => { copied = true; setTimeout(() => copied = false, 1500) })"
        >
            <span x-text="copied ? 'Copied!' : 'Copy WhatsApp text'"></span>
        </x-filament::button>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            Paste into a WhatsApp broadcast or group message.
        </p>
    </div>
</div>
