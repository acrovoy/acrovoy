@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">➕ Создать модель изделия</h1>

    <form action="{{ route('product_models.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Название --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Название модели</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        {{-- Артикул --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Артикул (SKU)</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
        </div>

        {{-- Фото --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Фото модели</label>
            <input type="file" name="photo" class="form-control">
        </div>

        {{-- Описание --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Описание</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
        </div>

        {{-- Компоненты --}}
        <div class="mb-3">
            <label class="form-label fw-bold">Компоненты</label>

            <div id="components-wrapper">
                <div class="d-flex mb-2 component-item">
                    <select name="components[0][raw_material_id]" class="form-select me-2" required>
                        <option value="">— выбрать сырьё —</option>
                        @foreach($rawmaterials as $rawmaterial)
                            <option value="{{ $rawmaterial->id }}">{{ $rawmaterial->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="components[0][quantity]" class="form-control me-2" placeholder="Кол-во" min="0" step="0.01" required>
                    <button type="button" class="btn btn-danger remove-component">✖</button>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm" id="add-component">➕ Добавить компонент</button>
        </div>

        <button type="submit" class="btn btn-success">💾 Сохранить</button>
        <a href="{{ route('product_models.index') }}" class="btn btn-secondary">⬅ Назад</a>
    </form>
</div>

{{-- JS для добавления/удаления компонентов --}}
<script>
let componentIndex = 1;

document.getElementById('add-component').addEventListener('click', function() {
    const wrapper = document.getElementById('components-wrapper');
    const newRow = document.createElement('div');
    newRow.classList.add('d-flex', 'mb-2', 'component-item');

    newRow.innerHTML = `
        <select name="components[${componentIndex}][raw_material_id]" class="form-select me-2" required>
            <option value="">— выбрать сырьё —</option>
            @foreach($rawmaterials as $rawmaterial)
                <option value="{{ $rawmaterial->id }}">{{ $rawmaterial->name }}</option>
            @endforeach
        </select>
        <input type="number" name="components[${componentIndex}][quantity]" class="form-control me-2" placeholder="Кол-во" min="0" step="0.01" required>
        <button type="button" class="btn btn-danger remove-component">✖</button>
    `;

    wrapper.appendChild(newRow);
    componentIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-component')) {
        e.target.closest('.component-item').remove();
    }
});
</script>
@endsection
