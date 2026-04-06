@extends('template.master')

@section('transaksi_active', 'active')
@section('order_active', 'active')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="mb-4"><strong>Tambah order</strong></h4>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('order.store') }}" method="POST">
                    @csrf

                    {{-- Header --}}
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Tanggal</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Customer</label>
                            <select name="customer_id" class="form-select select2" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Termin</label>
                            <select name="termin_id" class="form-select select2" required>
                                <option value="">-- Pilih --</option>
                                @foreach ($terminals as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="table-responsive">
                    <table class="table table-bordered" id="items-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th width="120">Qty</th>
                                <th width="180">Harga</th>
                                <th width="180">Amount</th>
                                <th width="80">Hapus</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>

                    <button type="button" class="btn btn-primary mb-3 mt-3" id="add-row">
                        + Tambah Item
                    </button>

                    {{-- Summary --}}
                    <div class="row">
                        <div class="col-md-6 offset-md-6">

                            <div class="mb-2">
                                <label>Subtotal</label>
                                <input type="text" name="subtotal" id="subtotal" class="form-control" readonly>
                            </div>

                            <div class="mb-2">
                                <label>Diskon</label>
                                <input type="text" name="discount" id="discount" class="form-control" value="0">
                            </div>

                            <div class="mb-2">
                                <label>Biaya Lain-lain</label>
                                <input type="text" name="expenses" id="expenses" class="form-control" value="0">
                            </div>

                            <div class="mb-2">
                                <label>Pajak</label>
                                <input type="text" name="taxes" id="taxes" class="form-control" value="0">
                            </div>

                            <div class="mb-2">
                                <label>Grand Total</label>
                                <input type="text" name="grand_total" id="grand_total" class="form-control" readonly>
                            </div>

                            <div class="mb-2">
                                <label>DP / Dibayar</label>
                                <input type="text" name="paid" id="paid" class="form-control" value="0">
                            </div>

                            <div class="mb-2">
                                <label>Sisa</label>
                                <input type="text" name="left" id="left" class="form-control" readonly>
                            </div>

                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('order.index') }}" class="btn btn-secondary">Batal</a>
                        <button class="btn btn-success">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {

            let products = @json($products);
            let index = 0;

            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka || 0);
            }

            function parseRupiah(str) {
                return parseFloat((str || '').replace(/\./g, '')) || 0;
            }

            // tambah row
            $('#add-row').click(function() {
                let row = `
            <tr>
                <td>
                    <select name="items[${index}][product_id]" class="form-select select2 product">
                        <option value="">-- Pilih --</option>
                        ${products.map(p =>
                            `<option value="${p.id}" data-price="${p.price}" data-minimum_order="${p.minimum_order}">
                                    ${p.product_category?.name ?? '-'} - ${p.name} (${p.packaging?.name ?? '-'})
                                </option>`
                        ).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${index}][qty]" class="form-control qty" value="1">
                </td>
                <td>
                    <input type="text" name="items[${index}][price]" class="form-control price">
                </td>
                <td>
                    <input type="text" class="form-control amount" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove">X</button>
                </td>
            </tr>
        `;

                $('#items-table tbody').append(row);
                $('#items-table tbody tr:last .select2').select2();

                index++;
            });

            // pilih produk → isi harga + set qty minimum
            $(document).on('change', '.product', function() {
                let selected = $(this).find(':selected');
                let price = selected.data('price');
                let minimumOrder = selected.data('minimum_order');

                let row = $(this).closest('tr');

                // set price
                row.find('.price').val(formatRupiah(price)).trigger('keyup');

                // set qty ke minimum order (atau kelipatan terdekat)
                let qtyInput = row.find('.qty');
                qtyInput.attr('min', minimumOrder);
                let currentQty = parseInt(qtyInput.val()) || 0;

                if (currentQty < minimumOrder) {
                    qtyInput.val(minimumOrder);
                } else {
                    // pembulatan ke kelipatan minimum_order
                    let adjustedQty = Math.ceil(currentQty / minimumOrder) * minimumOrder;
                    qtyInput.val(adjustedQty);
                }

                qtyInput.trigger('keyup');
            });

            // format input uang
            $(document).on('keyup', '.price, #discount, #expenses, #taxes, #paid', function() {
                let value = parseRupiah($(this).val());
                $(this).val(formatRupiah(value));
            });

            // hitung amount
            $(document).on('keyup change', '.qty, .price', function() {
                let row = $(this).closest('tr');

                let qty = parseFloat(row.find('.qty').val()) || 0;
                let price = parseRupiah(row.find('.price').val());

                let amount = qty * price;

                row.find('.amount').val(formatRupiah(amount));

                calculateTotal();
            });

            // hapus row
            $(document).on('click', '.remove', function() {
                $(this).closest('tr').remove();
                calculateTotal();
            });

            function calculateTotal() {
                let subtotal = 0;

                $('.amount').each(function() {
                    subtotal += parseRupiah($(this).val());
                });

                $('#subtotal').val(formatRupiah(subtotal));

                let discount = parseRupiah($('#discount').val());
                let expenses = parseRupiah($('#expenses').val());
                let taxes = parseRupiah($('#taxes').val());

                let grandTotal = subtotal - discount + expenses + taxes;

                $('#grand_total').val(formatRupiah(grandTotal));

                let paid = parseRupiah($('#paid').val());
                let left = grandTotal - paid;

                $('#left').val(formatRupiah(left));
            }

            $('#discount, #expenses, #taxes, #paid').on('keyup change', calculateTotal);

        });
    </script>
@endsection
