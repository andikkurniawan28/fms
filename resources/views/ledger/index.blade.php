@extends('template.master')

@section('laporan_active', 'active')
@section('ledger_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="mb-4"><strong>Buku Besar</strong></h4>

    <div class="card mb-3">
        <div class="card-body">

            <div class="row">

                <div class="col-md-3 mb-3">
                    <label class="form-label">Dari</label>
                    <input
                        type="date"
                        id="date_from"
                        class="form-control"
                        value="{{ date('Y-m-01') }}"
                    >
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Sampai</label>
                    <input
                        type="date"
                        id="date_to"
                        class="form-control"
                        value="{{ date('Y-m-t') }}"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Akun</label>
                    <select id="account_id" class="form-control select2">
                        @foreach($accounts as $account)
                        <option value="{{ $account->id }}">
                            {{ $account->code }} - {{ $account->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="btn-process">
                        Proses
                    </button>
                </div>

            </div>

        </div>
    </div>


    <div class="card">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="ledger-table">

                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Keterangan</th>
                            <th class="text-end">Debit</th>
                            <th class="text-end">Kredit</th>
                            <th class="text-end">Saldo</th>
                        </tr>
                    </thead>

                    <tbody>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection


@section('script')

<script>

$('#btn-process').click(function(){

    let date_from = $('#date_from').val();
    let date_to   = $('#date_to').val();
    let account   = $('#account_id').val();

    if(!date_from || !date_to || !account)
    {
        alert('Lengkapi filter terlebih dahulu');
        return;
    }

    $.ajax({

        url: "{{ route('ledger.process') }}",
        method: "POST",
        data: {
            _token : "{{ csrf_token() }}",
            date_from : date_from,
            date_to : date_to,
            account_id : account
        },

        success:function(response){

            let html = '';

            response.data.forEach(function(row){

                html += `
                <tr>
                    <td>${row.date}</td>
                    <td>${row.code}</td>
                    <td>${row.description}</td>
                    <td class="text-end">${format(row.debit)}</td>
                    <td class="text-end">${format(row.credit)}</td>
                    <td class="text-end">${format(row.balance)}</td>
                </tr>
                `;

            });

            $('#ledger-table tbody').html(html);

        }

    });

});


function format(angka)
{
    return new Intl.NumberFormat('id-ID').format(angka || 0);
}

</script>

@endsection
