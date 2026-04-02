@extends('template.master')

@section('master_active', 'active')
@section('packaging_active', 'active')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4"><strong>Edit packaging</strong></h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('packaging.update', $packaging->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $packaging->name) }}" required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('packaging.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
