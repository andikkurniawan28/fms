@extends('template.master')

@section('laporan_active', 'active')
@section('cash_flow_active', 'active')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="mb-4"><strong>Laporan Arus Kas</strong></h4>

    {{-- Filter --}}
    <div class="card mb-3">
        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <label class="form-label">Dari</label>
                    <input type="date"
                        id="date_from"
                        class="form-control"
                        value="{{ date('Y-m-01') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sampai</label>
                    <input type="date"
                        id="date_to"
                        class="form-control"
                        value="{{ date('Y-m-t') }}">
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
                        <th>Tanggal</th>
                        {{-- <th>No Jurnal</th> --}}
                        <th>Keterangan</th>
                        <th class="text-end">Masuk</th>
                        <th class="text-end">Keluar</th>
                        <th class="text-end">Saldo</th>
                    </tr>
                </thead>

                <tbody id="table-body"></tbody>

            </table>

        </div>
    </div>

</div>

@endsection


@section('script')

<script>

$('#btn-process').click(function(){

    $.post("{{ route('report_cash_flow.process') }}",{

        _token : "{{ csrf_token() }}",
        date_from : $('#date_from').val(),
        date_to : $('#date_to').val()

    })

    .done(function(res){

        let html = ''

        res.forEach(function(row){

            html += `
<tr>
<td>${row.date}</td>
<td>${row.description ?? ''}</td>
<td class="text-end">${format(row.debit)}</td>
<td class="text-end">${format(row.credit)}</td>
<td class="text-end">${format(row.balance)}</td>
</tr>
`

        })

        $('#table-body').html(html)

    })

    .fail(function(xhr){

        console.log(xhr.responseText)
        alert('Terjadi error')

    })

})


function format(num){
    return new Intl.NumberFormat('id-ID').format(num || 0)
}

</script>

@endsection
