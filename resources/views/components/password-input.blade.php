<div x-data="{ visible: false }" class="relative">
    <input
        x-bind:type="visible ? 'text' : 'password'"
        {{ $attributes->merge(['class' => 'pr-12']) }}
    >

    <button
        type="button"
        class="absolute inset-y-0 right-3 my-auto inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-900/10 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-400/40 dark:hover:bg-white/10 dark:hover:text-white"
        x-bind:aria-label="visible ? 'Ocultar contrasena' : 'Mostrar contrasena'"
        x-bind:title="visible ? 'Ocultar contrasena' : 'Mostrar contrasena'"
        x-on:click="visible = ! visible"
    >
        <svg x-show="!visible" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2.1 12s3.6-6.5 9.9-6.5S21.9 12 21.9 12s-3.6 6.5-9.9 6.5S2.1 12 2.1 12Z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
        <svg x-show="visible" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M3 3l18 18" />
            <path d="M10.6 10.6A3 3 0 0 0 12 15a3 3 0 0 0 2.4-1.2" />
            <path d="M9.9 5.8A9.7 9.7 0 0 1 12 5.5c6.3 0 9.9 6.5 9.9 6.5a17.8 17.8 0 0 1-2.5 3.2" />
            <path d="M6.6 6.9A17.4 17.4 0 0 0 2.1 12s3.6 6.5 9.9 6.5a9.7 9.7 0 0 0 4.1-.9" />
        </svg>
    </button>
</div>
