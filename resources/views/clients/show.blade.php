@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">👤 Карточка клиента</h1>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">⬅️ Назад к списку</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $client->name }}</h4>
            <p><strong>Email:</strong> {{ $client->email ?? '—' }}</p>
            <p><strong>Телефон:</strong> {{ $client->phone ?? '—' }}</p>
            <p><strong>Адрес:</strong> {{ $client->address ?? '—' }}</p>
        </div>
    </div>

    <div class="d-flex">
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning me-2">✏️ Редактировать</a>
        <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Удалить клиента?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">🗑️ Удалить</button>
        </form>
    </div>
</div>
@endsection
