@extends('template.master')

@section('transaksi_active', 'active')
@section('payment_active', 'active')

@section('content')
<div class="container-xxl container-p-y">

    <div class="card">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <h3 class="mb-1"><strong>Fathania Souvenir</strong></h3>
                    <p class="mb-0">Bukti Pembayaran</p>
                </div>
                <div class="text-end">
                    <h5 class="mb-1">#{{ $payment->code }}</h5>
                    <p class="mb-0">Tanggal: {{ $payment->date }}</p>
                </div>
            </div>

            <hr>

            {{-- INFO --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Dari:</strong></h6>
                    <p class="mb-0">{{ $payment->customer->name }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6><strong>Diterima oleh:</strong></h6>
                    <p class="mb-0">{{ $payment->user->name }}</p>
                </div>
            </div>

            {{-- DETAIL --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Kode Order</th>
                        <td>{{ $payment->order->code ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Order</th>
                        <td>{{ $payment->order->date ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Total Order</th>
                        <td>{{ number_format($payment->order->grand_total ?? 0,0,',','.') }}</td>
                    </tr>
                    <tr>
                        <th>Total Dibayar</th>
                        <td><strong>{{ number_format($payment->total,0,',','.') }}</strong></td>
                    </tr>
                    <tr>
                        <th>Sisa Tagihan Saat Ini</th>
                        <td>{{ number_format($payment->order->left ?? 0,0,',','.') }}</td>
                    </tr>
                </table>
            </div>

            {{-- TERBILANG --}}
            <div class="mb-4">
                <p class="mb-1"><strong>Terbilang:</strong></p>
                <p class="text-muted" id="terbilang-text"></p>
            </div>

            {{-- FOOTER --}}
            <div class="row mt-5">
                <div class="col-md-6">
                    <p class="text-muted">Terima kasih atas pembayaran Anda.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>{{ $payment->date }}</p>
                    <br><br>
                    <p><strong>{{ $payment->user->name }}</strong></p>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="text-end mt-4">
                <button onclick="window.print()" class="btn btn-primary">
                    Print
                </button>
                <a href="{{ route('payment.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/terbilang@1.0.0/terbilang.min.js"></script>
@endsection
