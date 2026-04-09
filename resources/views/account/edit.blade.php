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
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $account->name) }}" required>
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
