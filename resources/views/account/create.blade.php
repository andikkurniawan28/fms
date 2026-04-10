@extends('template.master')

@section('master_active', 'active')
@section('account_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Tambah akun</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('account.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="number" name="code" class="form-control" value="{{ old('code') }}" autofocus required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sub</label>
                    <select name="sub" class="form-control">
                        <option value="Aset Lancar">Aset Lancar</option>
                        <option value="Aset Tetap">Aset Tetap</option>
                        <option value="Kewajiban Jangka Pendek">Kewajiban Jangka Pendek</option>
                        <option value="Modal">Modal</option>
                        <option value="Pendapatan Usaha">Pendapatan Usaha</option>
                        <option value="Pendapatan Lain-lain">Pendapatan Lain-lain</option>
                        <option value="Beban Operasional">Beban Operasional</option>
                        <option value="Beban Lain-lain">Beban Lain-lain</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('account.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
