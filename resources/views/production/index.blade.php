@extends('template.master')

@section('transaksi_active', 'active')
@section('production_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h1 class="h3 mb-3"><strong>Daftar SPK</strong></h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('production.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="productionTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center" width="100%">
                    <thead>
                        <tr>
                            <th>Tanggal Terbit</th>
                            <th>Tanggal Acara</th>
                            <th>Kode</th>
                            {{-- <th>Order</th> --}}
                            <th>Customer</th>
                            <th>Admin</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka || 0);
    }

    $(function() {
        $('#productionTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('production.index') }}",
            order: [[0, 'asc']],
            columns: [
                { data: 'date', name: 'date' },
                { data: 'due_date', name: 'due_date' },
                { data: 'code', name: 'code' },
                // { data: 'order', name: 'order' },
                { data: 'customer', name: 'customer' },
                { data: 'user', name: 'user' },
                { data: 'subtotal', name: 'subtotal', render: function(data) { return formatRupiah(data); } },
                { data: 'action', name: 'action', productionable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
