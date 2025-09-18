<button {{ $attributes->merge(['type' => 'button', 'class' => 'py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none']) }}>
    {{ $slot }}
</button>
