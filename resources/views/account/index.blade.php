@extends('template.master')

@section('master_active', 'active')
@section('account_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h1 class="h3 mb-3"><strong>Daftar akun</strong></h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('account.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="accountTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kelompok</th>
                            <th>Sub</th>
                            <th>Nama</th>
                            <th>Saldo Normal</th>
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
        $('#accountTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('account.index') }}",
            order: [[0, 'asc']],
            columns: [
                { data: 'code', name: 'code' },
                { data: 'group', name: 'group' },
                { data: 'sub', name: 'sub' },
                { data: 'name', name: 'name' },
                { data: 'normal_balance', name: 'normal_balance' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
