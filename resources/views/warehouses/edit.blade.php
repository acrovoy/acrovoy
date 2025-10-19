@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">✏️ Редактировать склад</h1>
        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">⬅️ Назад к списку</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label class="form-label">Название склада</label>
            <input type="text" name="name" value="{{ $warehouse->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Локация</label>
            <input type="text" name="location" value="{{ $warehouse->location }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Менеджер</label>
            <input type="text" name="manager" value="{{ $warehouse->manager }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-warning">💾 Сохранить</button>
    </form>
</div>
@endsection
