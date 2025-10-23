@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">🧱 База сырья</h1>

    <a href="{{ route('raw-materials.create') }}" class="btn btn-success mb-3">➕ Добавить сырьё</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($materials as $mat)
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                @if($mat->photo)
                    <img src="{{ asset('storage/'.$mat->photo) }}" class="card-img-top" alt="{{ $mat->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $mat->name }}</h5>
                    @if($mat->code)
                        <p class="text-muted small mb-1">Код: {{ $mat->code }}</p>
                    @endif
                    @if($mat->unit)
                        <p class="text-muted small mb-1">Ед. изм: {{ $mat->unit }}</p>
                    @endif
                    @if($mat->description)
                        <p class="small mt-2">{{ $mat->description }}</p>
                    @endif
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('raw-materials.edit', $mat) }}" class="btn btn-primary btn-sm">✏️</a>
                    <form action="{{ route('raw-materials.destroy', $mat) }}" method="POST" onsubmit="return confirm('Удалить?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑️</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection