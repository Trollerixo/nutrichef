@props(['name', 'show' => false, 'maxWidth' => '2xl'])

<div
    x-data="{ show: @js($show) }"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-show="show"
    x-cloak
    class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
    style="z-index:1055;background:rgba(0,0,0,.5);"
    @click.self="show = false"
>
    <div class="modal-dialog modal-dialog-centered" @click.stop>
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
