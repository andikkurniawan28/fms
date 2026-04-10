@extends('template.master')

@section('master_active', 'active')
@section('account_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Edit akun</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('account.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kode</label>
                    <input type="number"
                           name="code"
                           class="form-control"
                           value="{{ old('code', $account->code) }}"
                           autofocus
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sub</label>
                    <select name="sub" class="form-control">
                        <option value="Aset Lancar" {{ old('sub', $account->sub) == 'Aset Lancar' ? 'selected' : '' }}>Aset Lancar</option>
                        <option value="Aset Tetap" {{ old('sub', $account->sub) == 'Aset Tetap' ? 'selected' : '' }}>Aset Tetap</option>
                        <option value="Kewajiban Jangka Pendek" {{ old('sub', $account->sub) == 'Kewajiban Jangka Pendek' ? 'selected' : '' }}>Kewajiban Jangka Pendek</option>
                        <option value="Modal" {{ old('sub', $account->sub) == 'Modal' ? 'selected' : '' }}>Modal</option>
                        <option value="Pendapatan Usaha" {{ old('sub', $account->sub) == 'Pendapatan Usaha' ? 'selected' : '' }}>Pendapatan Usaha</option>
                        <option value="Pendapatan Lain-lain" {{ old('sub', $account->sub) == 'Pendapatan Lain-lain' ? 'selected' : '' }}>Pendapatan Lain-lain</option>
                        <option value="Beban Operasional" {{ old('sub', $account->sub) == 'Beban Operasional' ? 'selected' : '' }}>Beban Operasional</option>
                        <option value="Beban Lain-lain" {{ old('sub', $account->sub) == 'Beban Lain-lain' ? 'selected' : '' }}>Beban Lain-lain</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $account->name) }}"
                           required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('account.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
