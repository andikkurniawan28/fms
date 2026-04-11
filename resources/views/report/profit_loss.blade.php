@extends('template.master')

@section('laporan_active', 'active')
@section('profit_loss_active', 'active')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="mb-4"><strong>Laporan Laba Rugi</strong></h4>

        <div class="card mb-3">
            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">
                        <label class="form-label">Dari</label>
                        <input type="date" id="date_from" class="form-control" value="{{ date('Y-m-01') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sampai</label>
                        <input type="date" id="date_to" class="form-control" value="{{ date('Y-m-t') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="btn-process">
                            Proses
                        </button>
                    </div>

                </div>

            </div>
        </div>


        <div class="card">
            <div class="card-body">

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Akun</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>

                    <tbody id="table-body"></tbody>

                    <tfoot>

                        <tr>
                            <th>Total Pendapatan</th>
                            <th class="text-end" id="total_pendapatan"></th>
                        </tr>

                        <tr>
                            <th>Total Beban</th>
                            <th class="text-end" id="total_beban"></th>
                        </tr>

                        <tr>
                            <th>Laba Bersih</th>
                            <th class="text-end" id="laba"></th>
                        </tr>

                    </tfoot>

                </table>

            </div>
        </div>

    </div>
@endsection


@section('script')

    <script>
        $('#btn-process').click(function() {

    $.post("{{ route('report_profit_loss.process') }}", {

        _token: "{{ csrf_token() }}",
        date_from: $('#date_from').val(),
        date_to: $('#date_to').val()

    })

    .done(function(res){

        console.log(res); // debug response

        let html = '';

        res.data.forEach(function(row) {

            html += `
<tr>
<td>${row.code} - ${row.name}</td>
<td class="text-end">${format(row.balance)}</td>
</tr>
`;

        });

        $('#table-body').html(html);

        $('#total_pendapatan').html(format(res.pendapatan));
        $('#total_beban').html(format(res.beban));
        $('#laba').html(format(res.laba));

    })

    .fail(function(xhr){

        console.log(xhr.responseText); // ERROR MUNCUL DISINI

        alert('Terjadi error, cek console');

    });

});

        function format(num) {
            return new Intl.NumberFormat('id-ID').format(num || 0);
        }
    </script>

@endsection
