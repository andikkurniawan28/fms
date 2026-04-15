@extends('template.invoice')

@section('transaksi_active', 'active')
@section('order_active', 'active')

@section('content')
<div class="container-xxl container-p-y">

    <div class="card">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <h3 class="mb-1"><strong>Fathania Souvenir</strong></h3>
                    <p class="mb-0">Rekap Pelunasan</p>
                </div>
                <div class="text-end">
                    <h5 class="mb-1">#{{ $order->code }}</h5>
                    <p class="mb-0">Tanggal: {{ $order->date }}</p>
                    <p class="mb-0">Status:
                        <span class="badge bg-label-primary">{{ $order->status }}</span>
                    </p>
                </div>
            </div>

            <hr>

            {{-- CUSTOMER --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Customer:</strong></h6>
                    <p class="mb-0">{{ $order->customer->name }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6><strong>Admin:</strong></h6>
                    <p class="mb-0">{{ $order->user->name }}</p>
                </div>
            </div>

            {{-- ITEMS --}}
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Diterima oleh</th>
                            <th>Total</th>
                            <th>Via</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->payment as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            {{-- <td>
                                {{ $item->product->productCategory->name }} -
                                {{ $item->product->name }}
                                <br>
                                <small class="text-muted">
                                    {{ $item->product->packaging->name ?? '-' }}
                                </small>
                            </td> --}}
                            <td>{{ $item->date }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ number_format($item->total,0,',','.') }}</td>
                            <td>{{ $item->via }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- SUMMARY --}}
            <div class="row">
                <div class="col-md-6">
                    {{-- <p class="mb-1"><strong>Catatan:</strong></p>
                    <p class="text-muted">Terima kasih telah berbelanja 🙏</p> --}}
                </div>

                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>Tagihan</th>
                            <td class="text-end">{{ number_format($order->grand_total,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Dibayar</th>
                            <td class="text-end">{{ number_format($order->paid,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Sisa (Piutang)</th>
                            <td class="text-end">{{ number_format($order->left,0,',','.') }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Pajak</th>
                            <td class="text-end">{{ number_format($order->taxes,0,',','.') }}</td>
                        </tr>
                        <tr class="table-light">
                            <th>Grand Total</th>
                            <td class="text-end"><strong>{{ number_format($order->grand_total,0,',','.') }}</strong></td>
                        </tr> --}}
                        {{-- <tr>
                            <th>Dibayar</th>
                            <td class="text-end">{{ number_format($order->paid,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Sisa</th>
                            <td class="text-end">{{ number_format($order->left,0,',','.') }}</td>
                        </tr> --}}
                    </table>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="text-end mt-4">
                <button onclick="window.print()" class="btn btn-primary">
                    Print
                </button>
                <a href="{{ route('order.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
