@extends('template.master')

@section('transaksi_active', 'active')
@section('payment_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h1 class="h3 mb-3"><strong>Daftar pelunasan</strong></h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('payment.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="paymentTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Admin</th>
                            <th>Total</th>
                            <th>Via</th>
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
        $('#paymentTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payment.index') }}",
            payment: [[0, 'desc']],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'date', name: 'date' },
                { data: 'code', name: 'code' },
                { data: 'order', name: 'order' },
                { data: 'customer', name: 'customer' },
                { data: 'user', name: 'user' },
                // { data: 'total', name: 'total', render: function(data) { return formatRupiah(data); } },
                { data: 'total', name: 'total', render: function(data) { return formatRupiah(data); } },
                { data: 'via', name: 'via' },
                { data: 'action', name: 'action', paymentable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
