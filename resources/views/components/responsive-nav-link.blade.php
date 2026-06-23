@props(['active'])

@php
$classes = ($active ?? false) ? 'nav-link active' : 'nav-link text-secondary';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
