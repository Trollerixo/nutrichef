@if (session('success'))
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast nc-toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header border-0 bg-transparent">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <strong class="me-auto">NutriChef</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body pt-0">
                {{ session('success') }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toastElement = document.getElementById('successToast');
            if (toastElement) {
                setTimeout(() => {
                    toastElement.classList.remove('show');
                }, 4000);
            }
        });
    </script>
@endif
