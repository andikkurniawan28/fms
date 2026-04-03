<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="/fms/public/sneat/assets/vendor/libs/jquery/jquery.js"></script>
<script src="/fms/public/sneat/assets/vendor/libs/popper/popper.js"></script>
<script src="/fms/public/sneat/assets/vendor/js/bootstrap.js"></script>
<script src="/fms/public/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="/fms/public/sneat/assets/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="/fms/public/sneat/assets/vendor/libs/apex-charts/apexcharts.js"></script>

<!-- Main JS -->
<script src="/fms/public/sneat/assets/js/main.js"></script>

<!-- Page JS -->
<script src="/fms/public/sneat/assets/js/dashboards-analytics.js"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

{{-- DataTables --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            timer: 1200,
            showConfirmButton: false
        });
    @endif

    @if (session('failed'))
        Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: "{{ session('failed') }}",
            timer: 1200,
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: "{{ session('error') }}",
            timer: 6000,
            showConfirmButton: false
        });
    @endif

    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `
                <ul style="text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `
        });
    @endif
</script>

{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: '-- Pilih --',
            allowClear: true,
            width: '100%' // pastikan full width
        });
    });
</script>

