@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">🤝 Карточка поставщика</h1>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">⬅️ Назад к списку</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $supplier->name }}</h4>
            <p><strong>Email:</strong> {{ $supplier->email ?? '—' }}</p>
            <p><strong>Телефон:</strong> {{ $supplier->phone ?? '—' }}</p>
            <p><strong>Адрес:</strong> {{ $supplier->address ?? '—' }}</p>
        </div>
    </div>

    <div class="d-flex">
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning me-2">✏️ Редактировать</a>
        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Удалить поставщика?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑️ Удалить</button>
        </form>
    </div>
</div>
@endsection
