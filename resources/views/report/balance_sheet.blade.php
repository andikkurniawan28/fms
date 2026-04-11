@extends('template.master')

@section('laporan_active', 'active')
@section('balance_sheet_active', 'active')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="mb-4"><strong>Laporan Neraca</strong></h4>

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


    <div class="row">

        {{-- ASET --}}
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Aset</strong>
                </div>

                <div class="card-body">

                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody id="aset-body"></tbody>

                        <tfoot>
                            <tr>
                                <th>Total Aset</th>
                                <th class="text-end" id="total_aset"></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Total Aktiva</strong>
                </div>

                <div class="card-body">

                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Total Aktiva</th>
                                <th class="text-end" id="total_aktiva"></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>

        </div>


        <div class="col-md-6">

            {{-- KEWAJIBAN --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Kewajiban</strong>
                </div>

                <div class="card-body">

                    <table class="table table-sm table-bordered">

                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody id="kewajiban-body"></tbody>

                        <tfoot>
                            <tr>
                                <th>Total Kewajiban</th>
                                <th class="text-end" id="total_kewajiban"></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>


            {{-- EKUITAS --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Modal</strong>
                </div>

                <div class="card-body">

                    <table class="table table-sm table-bordered">

                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody id="ekuitas-body"></tbody>

                        <tfoot>
                            <tr>
                                <th>Total Modal</th>
                                <th class="text-end" id="total_ekuitas"></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>


            {{-- PENDAPATAN --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Pendapatan</strong>
                </div>

                <div class="card-body">

                    <table class="table table-sm table-bordered">

                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody id="pendapatan-body"></tbody>

                        <tfoot>
                            <tr>
                                <th>Total Pendapatan</th>
                                <th class="text-end" id="total_pendapatan"></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>


            {{-- BEBAN --}}
            <div class="card mb-3">
                <div class="card-header">
                    <strong>Beban</strong>
                </div>

                <div class="card-body">

                    <table class="table table-sm table-bordered">

                        <thead>
                            <tr>
                                <th>Akun</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody id="beban-body"></tbody>

                        <tfoot>

                            <tr>
                                <th>Total Beban</th>
                                <th class="text-end" id="total_beban"></th>
                            </tr>

                        </tfoot>

                    </table>

                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong>Total Pasiva</strong>
                </div>

                <div class="card-body">

                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Total Laba</th>
                                <th class="text-end" id="total_laba"></th>
                            </tr>

                            <tr>
                                <th>Total Pasiva</th>
                                <th class="text-end" id="total_pasiva"></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script>

$('#btn-process').click(function(){

    $.post("{{ route('report_balance_sheet.process') }}",{

        _token : "{{ csrf_token() }}",
        date_from : $('#date_from').val(),
        date_to : $('#date_to').val()

    })

    .done(function(res){

        let total_laba = res.total_pendapatan - res.total_beban;
        renderTable('aset-body', res.aset)
        renderTable('kewajiban-body', res.kewajiban)
        renderTable('ekuitas-body', res.ekuitas)
        renderTable('pendapatan-body', res.pendapatan)
        renderTable('beban-body', res.beban)

        $('#total_aset').html(format(res.total_aset))
        $('#total_aktiva').html(format(res.total_aset))
        $('#total_kewajiban').html(format(res.total_kewajiban))
        $('#total_ekuitas').html(format(res.total_ekuitas))
        $('#total_pendapatan').html(format(res.total_pendapatan))
        $('#total_beban').html(format(res.total_beban))
        $('#total_pasiva').html(format(res.total_pasiva))
        $('#total_laba').html(format(total_laba))

    })

    .fail(function(xhr){

        console.log(xhr.responseText)
        alert('Terjadi error')

    })

})


function renderTable(target,data){

    let html = ''

    data.forEach(function(row){

        html += `
        <tr>
            <td>${row.code} - ${row.name}</td>
            <td class="text-end">${format(row.balance)}</td>
        </tr>
        `

    })

    $('#'+target).html(html)

}


function format(num){
    return new Intl.NumberFormat('id-ID').format(num || 0)
}

</script>

@endsection
