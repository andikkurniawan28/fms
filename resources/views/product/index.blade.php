@extends('template.master')

@section('master_active', 'active')
@section('product_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h1 class="h3 mb-3"><strong>Daftar produk</strong></h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('product.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="productTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Nama</th>
                            <th>Packaging</th>
                            <th>Harga</th>
                            <th>Min Order</th>
                            {{-- <th>Biaya</th> --}}
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
        $('#productTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('product.index') }}",
            order: [[0, 'asc']],
            columns: [
                { data: 'category', name: 'category' },
                { data: 'name', name: 'name' },
                { data: 'packaging', name: 'packaging' },
                { data: 'price', name: 'price', render: function(data) { return formatRupiah(data); } },
                // { data: 'cost', name: 'cost', render: function(data) { return formatRupiah(data); } },
                { data: 'minimum_order', name: 'minimum_order' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
