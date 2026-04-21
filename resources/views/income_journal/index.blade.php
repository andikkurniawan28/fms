@extends('template.master')

@section('transaksi_active', 'active')
@section('income_journal_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h1 class="h3 mb-3"><strong>Daftar jurnal pemasukan</strong></h1>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('income_journal.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="income_journalTable" class="table table-bordered table-hover table-striped table-sm w-100 text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Jenis</th>
                            <th>Keterangan</th>
                            <th>Admin</th>
                            <th>Nominal</th>
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
        $('#income_journalTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('income_journal.index') }}",
            order: [[0, 'desc']],
            columns: [
                { data: 'id', name: 'id' },
                { data: 'date', name: 'date' },
                { data: 'code', name: 'code' },
                { data: 'account', name: 'account' },
                { data: 'description', name: 'description' },
                { data: 'user', name: 'user' },
                { data: 'total', name: 'total', render: function(data) { return formatRupiah(data); } },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
