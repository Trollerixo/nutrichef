@props(['align' => 'right', 'width' => '48', 'contentClasses' => ''])

<div class="dropdown" {{ $attributes }}>
    <span data-bs-toggle="dropdown" aria-expanded="false" style="cursor:pointer;">
        {{ $trigger }}
    </span>
    <ul class="dropdown-menu {{ $align === 'left' ? '' : 'dropdown-menu-end' }} {{ $contentClasses }}">
        {{ $content }}
    </ul>
</div>
