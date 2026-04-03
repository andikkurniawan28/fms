@extends('template.master')

@section('master_active', 'active')
@section('customer_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h1 class="h3 mb-3"><strong>Daftar customer</strong></h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('customer.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="customerTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>WhatsApp</th>
                            <th>Keterangan</th>
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
    $(function() {
        $('#customerTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('customer.index') }}",
            order: [[0, 'asc']],
            columns: [
                { data: 'name', name: 'name' },
                { data: 'whatsapp', name: 'whatsapp' },
                { data: 'description', name: 'description' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
