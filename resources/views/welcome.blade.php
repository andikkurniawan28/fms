@extends('template.master')

@section('home_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">

        {{-- HERO --}}
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Dashboard 🎉</h5>
                            <p class="mb-4" id="hero-text">
                                Loading...
                            </p>

                            <a href="{{ route('order.index') }}" class="btn btn-sm btn-outline-primary">
                                Lihat Order
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="/fms/public/sneat/assets/img/illustrations/man-with-laptop-light.png" height="140"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KPI --}}
        <div class="col-lg-6">
            <div class="row">

                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">Omzet Bulan Ini</span>
                            <h3 class="card-title mb-2" id="omzet">Rp 0</h3>
                            <small class="text-success fw-semibold" id="growth">
                                -
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span>Total Order</span>
                            <h3 class="card-title mb-1" id="total_order">0</h3>
                            <small class="text-success fw-semibold" id="today_order">
                                -
                            </small>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- CHART --}}
        <div class="col-12 col-lg-6 mb-4">
            <div class="card">
                <h5 class="card-header">Omzet Bulanan</h5>
                <div id="totalRevenueChart"></div>
            </div>
        </div>

        {{-- SIDE --}}
        <div class="col-12 col-lg-6">
            <div class="row">

                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span>Piutang</span>
                            <h3 class="mb-2" id="piutang">Rp 0</h3>
                            <small class="text-danger" id="piutang_count"></small>
                        </div>
                    </div>
                </div>

                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span>Pembayaran Masuk</span>
                            <h3 class="mb-2" id="payment">Rp 0</h3>
                            <small class="text-success" id="payment_growth"></small>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">Produk Terlaris</span>
                            <h6 class="mb-0" id="top_product">-</h6>
                            <small class="text-muted" id="top_product_qty"></small>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">Reminder</span>
                            <small class="text-danger" id="reminder"></small>
                        </div>
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

        // fallback biar gak undefined
        let data = res || {};

        $('#hero-text').html(`
            Hari ini ada <span class="fw-bold">${data.today_order ?? 0}</span> transaksi
            dengan total omzet <span class="fw-bold">Rp ${rupiah(data.today_revenue)}</span>
        `);

        $('#omzet').text('Rp ' + rupiah(data.month_revenue));
        $('#growth').html(`<i class="bx bx-up-arrow-alt"></i> ${data.growth ?? 0}%`);

        $('#total_order').text(data.total_order ?? 0);
        $('#today_order').html(`<i class="bx bx-up-arrow-alt"></i> +${data.today_order ?? 0} hari ini`);

        $('#piutang').text('Rp ' + rupiah(data.piutang));
        $('#piutang_count').text((data.piutang_count ?? 0) + ' invoice belum lunas');

        $('#payment').text('Rp ' + rupiah(data.payment));
        $('#payment_growth').text(data.payment_growth ?? '-');

        $('#top_product').text(
            (data.top_product?.name ?? '-') +
            ' (' + (data.top_product?.packaging ?? '-') + ')'
        );
        $('#top_product_qty').text('Terjual ' + (data.top_product_qty ?? 0) + ' pcs');

        $('#reminder').text(data.reminder ?? '-');

        // chart safe
        let chartData = data.chart ?? [];
        let months = data.months ?? [];

        var options = {
            chart: { type: 'line', height: 300 },
            series: [{
                name: 'Omzet',
                data: chartData
            }],
            xaxis: {
                categories: months
            }
        };

        new ApexCharts(document.querySelector("#totalRevenueChart"), options).render();

    })
    .fail(function(err){
        console.error('API ERROR:', err);
    });

});
</script>
@endsection
