@extends('template.master')

@section('transaksi_active', 'active')
@section('expense_journal_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Tambah jurnal pengeluaran</strong></h4>

    <div class="card">
        <div class="card-body">
            <form id="form-expense" action="{{ route('expense_journal.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" autofocus required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis</label>
                    <select name="account_id" class="form-control">
                        @foreach($accounts as $a)
                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nominal</label>
                    <input type="text" name="total" class="form-control rupiah" value="{{ old('total') }}" required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('expense_journal.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(function(){

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka || 0);
    }

    function parseRupiah(str) {
        return (str || '').replace(/\./g, '');
    }

    // format saat ketik
    $(document).on('keyup', '.rupiah', function(){
        let val = parseRupiah($(this).val());
        $(this).val(formatRupiah(val));
    });

    // bersihin sebelum submit
    $('#form-expense').on('submit', function(){
        $('.rupiah').each(function(){
            let clean = parseRupiah($(this).val());
            $(this).val(clean);
        });
    });

});
</script>
@endsection
