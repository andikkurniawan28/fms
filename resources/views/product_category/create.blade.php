@extends('template.master')

@section('master_active', 'active')
@section('product_category_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Tambah kategori produk</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('product_category.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" autofocus required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('product_category.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
