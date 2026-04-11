@extends('template.master')

@section('home_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">

        {{-- HERO --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="d-flex align-items-center row h-100">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h4 class="card-title text-primary mb-3">Hallo {{ auth()->user()->name }} 🎉</h4>
                            <p class="mb-4 fs-5" id="hero-text">Loading...</p>

                            <a href="{{ route('order.index') }}" class="btn btn-primary">
                                <i class="bx bx-cart"></i> Lihat Order
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="/fms/public/sneat/assets/img/illustrations/man-with-laptop-light.png" height="150"/>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI RIGHT --}}
        <div class="col-lg-4">
            <div class="row">

                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="text-muted">Omzet Bulan Ini</span>
                            <h3 class="mt-2 mb-1" id="omzet">Rp 0</h3>
                            <small class="text-success" id="growth">-</small>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="text-muted">Order Belum Lunas</span>
                            <h3 class="mt-2 mb-1" id="total_order">0</h3>
                            <small class="text-muted" id="today_order">-</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ROW 2 --}}
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Piutang</span>
                    <h4 class="mt-2" id="piutang">Rp 0</h4>
                    <small class="text-danger" id="piutang_count"></small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Cash Masuk</span>
                    <h4 class="mt-2" id="payment">Rp 0</h4>
                    <small class="text-success" id="payment_growth"></small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Order Belum Diproses</span>
                    <h4 class="mt-2" id="top_product">0</h4>
                    <small class="text-muted" id="top_product_qty"></small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <span class="text-muted">Sedang Dikerjakan</span>
                    <h4 class="mt-2" id="reminder">0</h4>
                    <small class="text-primary">Aktivitas produksi</small>
                </div>
            </div>
        </div>

        {{-- ROW 3 (INSIGHT TAMBAHAN BIAR PADAT) --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Ringkasan Keuangan</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sales Bulan Ini</span>
                        <strong id="sales_month">Rp 0</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Cash Masuk</span>
                        <strong id="cash_month">Rp 0</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Profit</span>
                        <strong id="profit_month">Rp 0</strong>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Status Produksi</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Diproses</span>
                        <strong id="in_production">0</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Belum Diproses</span>
                        <strong id="pending_order">0</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Conversion Rate</span>
                        <strong id="conversion_rate">0%</strong>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection


@section('script')
<script>
$(function () {

    function rupiah(val) {
        return new Intl.NumberFormat('id-ID').format(val || 0);
    }

    $.get("{{ route('home_api') }}")
    .done(function(res) {

        let data = res || {};

        // HERO
        $('#hero-text').html(`
            Hari ini ada <b>${data.today?.order ?? 0}</b> order
            dengan omzet <b>Rp ${rupiah(data.today?.sales)}</b>
        `);

        // KPI
        $('#omzet').text('Rp ' + rupiah(data.monthly?.sales));
        $('#growth').text('Cash Rp ' + rupiah(data.monthly?.payment));

        $('#total_order').text(data.receivable?.unpaid_order ?? 0);
        $('#today_order').text(`${data.today?.order ?? 0} order hari ini`);

        // PIUTANG
        $('#piutang').text('Rp ' + rupiah(data.receivable?.total));
        $('#piutang_count').text(
            (data.receivable?.unpaid_order ?? 0) + ' belum lunas'
        );

        // CASH
        $('#payment').text('Rp ' + rupiah(data.monthly?.payment));
        $('#payment_growth').text('Cash bulan ini');

        // WORKFLOW
        $('#top_product').text(data.production?.pending_order ?? 0);
        $('#top_product_qty').text(
            'Rp ' + rupiah(data.production?.pending_value)
        );

        // PRODUKSI
        $('#reminder').text(data.production?.in_production ?? 0);

        // SUMMARY
        $('#sales_month').text('Rp ' + rupiah(data.monthly?.sales));
        $('#cash_month').text('Rp ' + rupiah(data.monthly?.payment));
        $('#profit_month').text('Rp ' + rupiah(data.monthly?.profit));

        $('#in_production').text(data.production?.in_production ?? 0);
        $('#pending_order').text(data.production?.pending_order ?? 0);
        $('#conversion_rate').text((data.production?.conversion_rate ?? 0) + '%');

    })
    .fail(function(err){
        console.error(err);
    });

});
</script>
@endsection
