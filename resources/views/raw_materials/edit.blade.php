@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Редактировать сырьё</h1>

    <form action="{{ route('raw-materials.update', $raw_material) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Название *</label>
            <input type="text" name="name" value="{{ $raw_material->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Код / Артикул</label>
            <input type="text" name="code" value="{{ $raw_material->code }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Единица измерения</label>
            <select name="unit" class="form-control" required>
                <option value="{{ $raw_material->unit}}"> {{ $raw_material->unit}}</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control" rows="3">{{ $raw_material->description }}</textarea>
        </div>

        @if($raw_material->photo)
            <div class="mb-3">
                <img src="{{ asset('storage/'.$raw_material->photo) }}" alt="" width="150" class="img-thumbnail">
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Новое фото</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <button class="btn btn-primary">💾 Обновить</button>
        <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
