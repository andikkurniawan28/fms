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

                let html = '';

                // ===== PENDAPATAN =====
                html += `
                    <tr class="table-primary">
                        <td colspan="2"><strong>PENDAPATAN</strong></td>
                    </tr>
                    `;

                res.pendapatan_detail.forEach(function(row) {
                    let isMinus = row.balance < 0 ? 'text-danger fw-bold' : '';

                    html += `
                    <tr>
                    <td>${row.code} - ${row.name}</td>
                    <td class="text-end ${isMinus}">${format(row.balance)}</td>
                    </tr>
                    `;
                });

                html += `
                    <tr class="table-light">
                        <th>Total Pendapatan</th>
                        <th class="text-end" id="total_pendapatan"></th>
                    </tr>
                    `;

                                    // ===== BEBAN =====
                                    html += `
                    <tr class="table-danger">
                        <td colspan="2"><strong>BEBAN</strong></td>
                    </tr>
                    `;

                res.beban_detail.forEach(function(row) {
                    let isMinus = row.balance < 0 ? 'text-danger fw-bold' : '';

                    html += `
                    <tr>
                    <td>${row.code} - ${row.name}</td>
                    <td class="text-end ${isMinus}">${format(row.balance)}</td>
                    </tr>
                    `;
                                    });

                                    html += `
                    <tr class="table-light">
                        <th>Total Beban</th>
                        <th class="text-end" id="total_beban"></th>
                    </tr>

                    <tr class="table-warning">
                        <th>Laba Bersih</th>
                        <th class="text-end fw-bold" id="laba"></th>
                    </tr>
                    `;

                $('#table-body').html(html);

                setValue('#total_pendapatan', res.pendapatan);
                setValue('#total_beban', res.beban);
                setValue('#laba', res.laba, true);

            })

            .fail(function(xhr){
                console.log(xhr.responseText);
                alert('Terjadi error, cek console');
            });

        });

        function format(num) {
            let n = Number(num) || 0;
            let abs = Math.abs(n);
            let formatted = new Intl.NumberFormat('id-ID').format(abs);

            return n < 0 ? `(${formatted})` : formatted;
        }

        function setValue(selector, value, highlight = false) {
            let el = $(selector);
            let isMinus = value < 0;

            el.removeClass('text-danger text-success fw-bold');

            if (isMinus) {
                el.addClass('text-danger fw-bold');
            } else if (highlight) {
                el.addClass('text-success fw-bold');
            }

            el.html(format(value));
        }
    </script>

@endsection
