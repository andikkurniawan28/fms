@extends('template.master')

@section('master_active', 'active')
@section('product_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Tambah produk</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('product.store') }}" method="POST">
                @csrf

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori Produk</label>
                    <select name="product_category_id" class="form-select select2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('product_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                {{-- Harga --}}
                @foreach($packagings as $p)
                <div class="mb-3">
                    <label class="form-label">Harga Jual (Packaging {{ $p->name }})</label>
                    <input type="number" step="0.01" name="price_{{ $p->id }}" class="form-control" value="{{ old('price') }}" required>
                </div>
                @endforeach

                {{-- Base Price --}}
                {{-- <div class="mb-3">
                    <label class="form-label">Base Price</label>
                    <input type="number" step="0.01" name="base_price" class="form-control" value="{{ old('base_price') }}">
                </div> --}}

                {{-- Cost --}}
                {{-- <div class="mb-3">
                    <label class="form-label">Cost</label>
                    <input type="number" step="0.01" name="cost" class="form-control" value="{{ old('cost') }}">
                </div> --}}

                <div class="d-flex justify-content-end">
                    <a href="{{ route('product.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
