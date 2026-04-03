@extends('template.master')

@section('master_active', 'active')
@section('product_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Tambah produk</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="POST" id="form-product">
                @csrf

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori Produk</label>
                    <select name="product_category_id" class="form-select select2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                {{-- Minimum Order --}}
                <div class="mb-3">
                    <label class="form-label">Minimum Order</label>
                    <input type="number" name="minimum_order" class="form-control" value="1" required>
                </div>

                {{-- Harga --}}
                @foreach($packagings as $p)
                <div class="mb-3">
                    <label class="form-label">Harga Jual (Packaging {{ $p->name }})</label>
                    <input type="text" name="price_{{ $p->id }}" class="form-control rupiah">
                </div>
                @endforeach

                <div class="d-flex justify-content-end">
                    <a href="{{ route('product.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(function(){

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka || 0);
    }

    function parseRupiah(str) {
        return (str || '').replace(/\./g, '');
    }

    // format saat ketik
    $(document).on('keyup', '.rupiah', function(){
        let val = parseRupiah($(this).val());
        $(this).val(formatRupiah(val));
    });

    // bersihin sebelum submit
    $('#form-product').on('submit', function(){
        $('.rupiah').each(function(){
            let clean = parseRupiah($(this).val());
            $(this).val(clean);
        });
    });

});
</script>
@endsection
