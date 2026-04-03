@extends('template.master')

@section('transaksi_active', 'active')
@section('payment_active', 'active')

@section('content')
<div class="container-xxl container-p-y">
    <h4 class="mb-4"><strong>Tambah Payment</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('payment.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <div class="mb-3">
                    <label>Order</label>
                    <select name="order_id" class="form-select select2" required>
                        <option value="">-- Pilih --</option>
                        @foreach($orders as $o)
                            <option value="{{ $o->id }}">
                                {{ $o->code }} - {{ $o->customer->name }}
                                (Sisa: {{ number_format($o->left,0,',','.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>Total Bayar</label>
                    <input type="text" name="total" id="total" class="form-control">
                </div>

                <button class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID').format(angka || 0);
}

function parseRupiah(str) {
    return parseFloat((str || '').replace(/\./g, '')) || 0;
}

$(document).on('keyup', '#total', function () {
    let value = parseRupiah($(this).val());
    $(this).val(formatRupiah(value));
});
</script>
@endsection
