<div id="toast" role="status" aria-live="polite" aria-atomic="true" :class="{ show: toast.show }">
    <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
    </svg>
    <span x-text="toast.msg">Done</span>
</div>
