@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">➕ Создать заказ на производство изделия</h1>

    <form action="{{ route('manufactured_products.store') }}" method="POST">
        @csrf

        {{-- Выбор модели для автозаполнения --}}
        <div class="mb-3">
            <label class="form-label">Выберите модель изделия (для автозаполнения состава)</label>
            <select id="product-model-select" class="form-select">
                <option value="">— Выберите модель —</option>
                @foreach($productModels as $model)
                    <option value="{{ $model->id }}">{{ $model->name }} ({{ $model->sku }})</option>
                @endforeach
            </select>
        </div>

        {{-- Наименование и артикул --}}
        <div class="mb-3">
            <label class="form-label">Наименование</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Артикул (SKU)</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku') }}">
        </div>

        {{-- Статус --}}
        <div class="mb-3">
            <label class="form-label">Статус изделия</label>
            <select name="status" class="form-select">
                <option value="order" {{ old('status') == 'order' ? 'selected' : '' }}>Заказ на производство</option>
                <option value="produced" {{ old('status') == 'produced' ? 'selected' : '' }}>Произведено</option>
                <option value="stocked" {{ old('status') == 'stocked' ? 'selected' : '' }}>Поставлено на склад</option>
            </select>
        </div>

        {{-- Категория --}}
        <div class="mb-3">
            <label class="form-label">Категория</label>
            <select name="category_id" class="form-select">
                <option value="">— Выберите категорию —</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Склад --}}
        <div class="mb-3">
            <label class="form-label">Склад</label>
            <select name="warehouse_id" class="form-select">
                <option value="">— Выберите склад —</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Примечания --}}
        <div class="mb-3">
            <label class="form-label">Примечания</label>
            <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        {{-- Состав изделия --}}
        <h4 class="mt-4">Состав изделия</h4>
        <div id="components-wrapper">
            <div class="component-row row mb-2">
                <div class="col-md-6">
                    <select name="components[0][supply_id]" class="form-select">
                        <option value="">— Выберите материал —</option>
                        @foreach($supplies as $supply)
                            <option value="{{ $supply->id }}">{{ $supply->name }} ({{ $supply->price_per_unit }}) - {{ $supply->quantity_remaining }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.01" name="components[0][quantity]" class="form-control" placeholder="Количество">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-component">✖</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary mb-3" id="add-component">➕ Добавить компонент</button>

        <button type="submit" class="btn btn-success">Сохранить изделие</button>
        <a href="{{ route('manufactured_products.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>

<script>
let componentIndex = 1;

// Добавление новой строки компонента
document.getElementById('add-component').addEventListener('click', function() {
    const wrapper = document.getElementById('components-wrapper');
    const row = document.querySelector('.component-row').cloneNode(true);

    row.querySelectorAll('input, select').forEach(el => {
        const name = el.getAttribute('name');
        const newName = name.replace(/\d+/, componentIndex);
        el.setAttribute('name', newName);
        el.value = '';
    });

    wrapper.appendChild(row);
    componentIndex++;
});

// Удаление строки компонента
document.addEventListener('click', function(e){
    if(e.target && e.target.classList.contains('btn-remove-component')){
        const rows = document.querySelectorAll('.component-row');
        if(rows.length > 1){
            e.target.closest('.component-row').remove();
        }
    }
});

// Автозаполнение компонентов при выборе модели
document.getElementById('product-model-select').addEventListener('change', function() {
    const modelId = this.value;
    const wrapper = document.getElementById('components-wrapper');
    wrapper.innerHTML = '';
    componentIndex = 0;

    if (!modelId) return;

    fetch(`/product-models/${modelId}/components`)
        .then(res => res.json())
        .then(data => {
            data.forEach(comp => {
                const row = document.createElement('div');
                row.classList.add('component-row', 'row', 'mb-2');

                if (comp.supply_exists) {
                    row.innerHTML = `
                        <div class="col-md-6">
                            <select name="components[${componentIndex}][supply_id]" class="form-select">
                                <option value="${comp.supply_id}" selected>
                                    ${comp.name} (${comp.price_per_unit}) - ${comp.quantity_remaining}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" step="0.01" name="components[${componentIndex}][quantity]" class="form-control" value="${comp.quantity}" placeholder="Количество">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove-component">✖</button>
                        </div>
                    `;
                } else {
                    row.innerHTML = `
                        <div class="col-md-10">
                            <input type="text" class="form-control" value="Компонент '${comp.name}' отсутствует на складе" disabled>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-remove-component">✖</button>
                        </div>
                    `;
                }

                wrapper.appendChild(row);
                componentIndex++;
            });
        });
});
</script>
@endsection
