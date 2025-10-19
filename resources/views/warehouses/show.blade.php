@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">🏬 Карточка склада</h1>
        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">⬅️ Назад к списку</a>
    </div>

    {{-- Основная информация --}}
    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $warehouse->name }}</h4>
            <p><strong>Локация:</strong> {{ $warehouse->location ?? '—' }}</p>
            <p><strong>Менеджер:</strong> {{ $warehouse->manager ?? '—' }}</p>
        </div>
    </div>

    {{-- Кнопки редактирования и удаления --}}
    <div class="d-flex">
        <a href="{{ route('warehouses.edit', $warehouse) }}" class="btn btn-warning me-2">✏️ Редактировать</a>
        <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" onsubmit="return confirm('Удалить склад?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑️ Удалить</button>
        </form>
    </div>
</div>
@endsection
