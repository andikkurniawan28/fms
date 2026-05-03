@extends('template.master')

@section('laporan_active', 'active')
@section('ledger_active', 'active')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="mb-4"><strong>Buku Besar</strong></h4>

        {{-- FILTER --}}
        <div class="card mb-3">
            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">Dari</label>
                        <input type="date" id="date_from" class="form-control"
                            value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Sampai</label>
                        <input type="date" id="date_to" class="form-control"
                            value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Akun</label>
                        <select id="account_id" class="form-select">
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->code }} - {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="btn-process">
                            Proses
                        </button>
                    </div>

                </div>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="ledger-table">

                        <thead class="table-light">
                            <tr>
                                <th width="120">Tanggal</th>
                                <th width="120">Kode</th>
                                <th>Keterangan</th>
                                <th class="text-end" width="150">Debit</th>
                                <th class="text-end" width="150">Kredit</th>
                                <th class="text-end" width="150">Saldo</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Silakan klik "Proses"
                                </td>
                            </tr>
                        </tbody>

                        {{-- <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end" id="total_debit">0</th>
                                <th class="text-end" id="total_credit">0</th>
                                <th></th>
                            </tr>
                        </tfoot> --}}

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection


@section('script')

    <script>
        $('#btn-process').click(function() {

            let date_from = $('#date_from').val();
            let date_to = $('#date_to').val();
            let account = $('#account_id').val();

            if (!date_from || !date_to || !account) {
                alert('Lengkapi filter terlebih dahulu');
                return;
            }

            $('#ledger-table tbody').html(`
        <tr>
            <td colspan="6" class="text-center">Loading...</td>
        </tr>
    `);

            $.ajax({
                url: "{{ route('ledger.process') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    date_from: date_from,
                    date_to: date_to,
                    account_id: account
                },

                success: function(response) {

                    let html = '';
                    let total_debit = 0;
                    let total_credit = 0;

                    // === SALDO AWAL ===
                    html += `
            <tr class="table-warning">
                <td colspan="5"><strong>Saldo Awal</strong></td>
                <td class="text-end"><strong>${format(response.saldo_awal)}</strong></td>
            </tr>
            `;

                    // === DATA ===
                    response.data.forEach(function(row) {

                        total_debit += Number(row.debit || 0);
                        total_credit += Number(row.credit || 0);

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

                    // === TOTAL ===
                    $('#total_debit').text(format(total_debit));
                    $('#total_credit').text(format(total_credit));
                },

                error: function() {
                    alert('Terjadi kesalahan');
                }
            });

        });


        function format(angka) {
            return new Intl.NumberFormat('id-ID').format(angka || 0);
        }
    </script>

@endsection
