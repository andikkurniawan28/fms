@extends('template.master')

@section('master_active', 'active')
@section('product_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Edit produk</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('product.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Kategori --}}
                <div class="mb-3">
                    <label class="form-label">Kategori Produk</label>
                    <select name="product_category_id" class="form-select select2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control"
                        value="{{ old('name', $product->name) }}" required>
                </div>

                {{-- Packaging (readonly biar jelas ini produk yang mana) --}}
                <div class="mb-3">
                    <label class="form-label">Packaging</label>
                    <input type="text" class="form-control"
                        value="{{ $product->packaging->name ?? '-' }}" readonly>
                </div>

                {{-- Harga --}}
                <div class="mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" step="0.01" name="price"
                        class="form-control"
                        value="{{ old('price', $product->price) }}" required>
                </div>

                {{-- Base Price --}}
                <div class="mb-3">
                    <label class="form-label">Base Price</label>
                    <input type="number" step="0.01" name="base_price"
                        class="form-control"
                        value="{{ old('base_price', $product->base_price) }}">
                </div>

                {{-- Cost --}}
                <div class="mb-3">
                    <label class="form-label">Cost</label>
                    <input type="number" step="0.01" name="cost"
                        class="form-control"
                        value="{{ old('cost', $product->cost) }}">
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('product.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
