@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">➕ Добавить сырьё</h1>

    <form action="{{ route('raw-materials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Название *</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Код / Артикул</label>
            <input type="text" name="code" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Единица измерения</label>
            <select name="unit" class="form-control" required>
                <option value="">- выберите -</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->name }}">{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Фото</label>
            <input type="file" name="photo" class="form-control">
        </div>

        <button class="btn btn-success">💾 Сохранить</button>
        <a href="{{ route('raw-materials.index') }}" class="btn btn-secondary">Назад</a>
    </form>
</div>
@endsection
