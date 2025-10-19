@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">✏️ Редактировать поставщика</h1>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">⬅️ Назад к списку</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Название</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $supplier->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Телефон</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Адрес</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $supplier->address) }}">
        </div>

        <button type="submit" class="btn btn-warning">💾 Обновить</button>
    </form>
</div>
@endsection
