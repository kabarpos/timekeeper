@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 mt-2 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-x-2">
                <i class="fas fa-exclamation-circle flex-shrink-0 size-4"></i>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
