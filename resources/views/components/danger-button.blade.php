<button {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none']) }}>
    {{ $slot }}
</button>
