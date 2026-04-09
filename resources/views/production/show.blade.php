@extends('template.invoice')

@section('transaksi_active', 'active')
@section('production_active', 'active')

@section('content')
<div class="container-xxl container-p-y">

    <div class="card">
        <div class="card-body">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <h3 class="mb-1"><strong>Fathania Souvenir</strong></h3>
                    <p class="mb-0">SPK</p>
                </div>
                <div class="text-end">
                    <h5 class="mb-1">#{{ $production->code }}</h5>
                    <p class="mb-0">Tanggal Terbit: {{ $production->date }}</p>
                    <p class="mb-0">Tanggal Acara: {{ $production->due_date }}</p>
                    {{-- <p class="mb-0">Status:
                        <span class="badge bg-label-primary">{{ $production->status }}</span>
                    </p> --}}
                </div>
            </div>

            <hr>

            {{-- CUSTOMER --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6><strong>Nama Pengantin:</strong></h6>
                    <p class="mb-0">{{ $production->customer->name }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h6><strong>Admin:</strong></h6>
                    <p class="mb-0">{{ $production->user->name }}</p>
                </div>
            </div>

            {{-- ITEMS --}}
            <div class="table-responsive mb-4">
                <table class="table table-bproductioned">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Spesifikasi</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($production->items as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            {{-- <td>
                                {{ $item->product->productCategory->name }} -
                                {{ $item->product->name }}
                                <br>
                                <small class="text-muted">
                                    {{ $item->product->packaging->name ?? '-' }}
                                </small>
                            </td> --}}
                            <td>{{ $item->product }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ number_format($item->price,0,',','.') }}</td>
                            <td>{{ number_format($item->amount,0,',','.') }}</td>
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
                            <th>Subtotal</th>
                            <td class="text-end">{{ number_format($production->subtotal,0,',','.') }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Diskon</th>
                            <td class="text-end">{{ number_format($production->discount,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Biaya Lain</th>
                            <td class="text-end">{{ number_format($production->expenses,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Pajak</th>
                            <td class="text-end">{{ number_format($production->taxes,0,',','.') }}</td>
                        </tr>
                        <tr class="table-light">
                            <th>Grand Total</th>
                            <td class="text-end"><strong>{{ number_format($production->grand_total,0,',','.') }}</strong></td>
                        </tr> --}}
                        {{-- <tr>
                            <th>Dibayar</th>
                            <td class="text-end">{{ number_format($production->paid,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Sisa</th>
                            <td class="text-end">{{ number_format($production->left,0,',','.') }}</td>
                        </tr> --}}
                    </table>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="text-end mt-4">
                <button onclick="window.print()" class="btn btn-primary">
                    Print
                </button>
                <a href="{{ route('production.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>
@endsection
