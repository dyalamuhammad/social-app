<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<style>
    .swal2-x-mark-line-left {
        background-color: #fb3838 !important;
    }

    .swal2-x-mark-line-right {
        background-color: #fb3838 !important;
    }

    .swal2-error {
        border-color: #fb3838 !important;
    }

    .swal2-success-line-tip {
        background-color: #0D9276 !important;
    }


    .swal2-success-line-long {
        background-color: #0D9276 !important;
        color: #0D9276 !important;
    }

    .swal2-success-ring {
        border-color: #0d9275ab !important;
    }

    .swal2-title {
        color: white !important;
    }

    .swal2-confirm {
        color: #ffffff !important;
        background-color: rgba(240, 248, 255, 0) !important;
        border: 1px solid #ffffff !important;
        padding: .5rem 2rem;
    }

    .swal2-confirm:hover {
        color: rgb(0, 0, 0) !important;
        background-color: #ffffff !important;
        border: 1px solid #ffffff !important;
    }
</style>
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'SUCCESS',
            text: "{{ session('success') }}"
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ERROR',
            text: "{{ session('error') }}"
        });
    </script>
@endif
