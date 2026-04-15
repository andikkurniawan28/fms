@extends('template.master')

@section('transaksi_active', 'active')
@section('payment_active', 'active')

@section('content')
    <div class="container-xxl container-p-y">
        <h4 class="mb-4"><strong>Edit pelunasan</strong></h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('payment.update', $payment->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="date" value="{{ $payment->date }}" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Order</label>
                        <input type="text" class="form-control"
                            value="{{ $payment->order->code }} - {{ $payment->order->customer->name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Total Bayar</label>
                        <input type="text" name="total" id="total" class="form-control"
                            value="{{ number_format($payment->total, 0, ',', '.') }}">
                    </div>

                    <div class="mb-3">
                        <label>Pembayaran lewat</label>
                        <select name="via" class="form-control select2" required>
                            <option value="">-- Pilih --</option>
                            <option value="Cash" {{ $payment->via == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Transfer" {{ $payment->via == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Update</button>
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

        $(document).on('keyup', '#total', function() {
            let value = parseRupiah($(this).val());
            $(this).val(formatRupiah(value));
        });
    </script>
@endsection
