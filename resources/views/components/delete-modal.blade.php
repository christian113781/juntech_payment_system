@props([
    'name',
    'title' => 'Delete item?',
    'message' => 'This action cannot be undone.',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel',
    'show' => false,
    'itemName' => null,
])

<div
    x-data="{ show: @js($show) }"
    x-show="show"
    x-cloak
    x-init="if (show) { document.body.classList.add('overflow-y-hidden'); } else { document.body.classList.remove('overflow-y-hidden'); }"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    <div class="absolute inset-0 bg-slate-900/55" @click="close()"></div>

    <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-card dark:bg-gray-800" role="alertdialog" aria-modal="true" aria-labelledby="delete-confirm-title-{{ $name }}">
        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-500 dark:bg-red-500/10" aria-hidden="true">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 3h5a1 1 0 011 1v3h-7V4a1 1 0 011-1z"/>
            </svg>
        </div>

        <h2 id="delete-confirm-title-{{ $name }}" class="text-base font-bold text-gray-900 dark:text-white">{{ $title }}</h2>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">
            {!! $message !!}
            @if ($itemName)
                <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $itemName }}</span>
            @endif
        </p>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" wire:click="closeDeleteModal()" class="min-h-[44px] rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition-colors hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                {{ $cancelText }}
            </button>
            <button type="button" wire:click="confirmDelete()" wire:loading.attr="disabled" wire:target="confirmDelete"
                class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-600 disabled:opacity-70">
                <svg wire:loading wire:target="confirmDelete" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="confirmDelete">{{ $confirmText }}</span>
                <span wire:loading wire:target="confirmDelete">Deleting...</span>
            </button>
        </div>
    </div>
</div>
