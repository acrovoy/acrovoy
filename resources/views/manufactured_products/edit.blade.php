@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Редактировать изделие</h1>

    <form action="{{ route('manufactured_products.update', $manufacturedProduct->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Наименование и артикул --}}
        <div class="mb-3">
            <label class="form-label">Наименование</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $manufacturedProduct->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Артикул (SKU)</label>
            <input type="text" name="sku" class="form-control" value="{{ old('sku', $manufacturedProduct->sku) }}">
        </div>


         {{-- Статус --}}
        <div class="mb-3">
            <label class="form-label">Статус изделия</label>
            <select name="status" class="form-select">
                <option value="order" {{ old('status', $manufacturedProduct->status ?? '') == 'order' ? 'selected' : '' }}>Заказ на производство</option>
                <option value="produced" {{ old('status', $manufacturedProduct->status ?? '') == 'produced' ? 'selected' : '' }}>Произведено</option>
                <option value="stocked" {{ old('status', $manufacturedProduct->status ?? '') == 'stocked' ? 'selected' : '' }}>Поставлено на склад</option>
            </select>
        </div>

        {{-- Категория --}}
        <div class="mb-3">
            <label class="form-label">Категория</label>
            <select name="category_id" class="form-select">
                <option value="">— Выберите категорию —</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $manufacturedProduct->category_id) == $category->id ? 'selected' : '' }}>
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
                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $manufacturedProduct->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>

       

        {{-- Примечания --}}
        <div class="mb-3">
            <label class="form-label">Примечания</label>
            <textarea name="notes" class="form-control">{{ old('notes', $manufacturedProduct->notes) }}</textarea>
        </div>

        {{-- Состав изделия --}}
        <h4 class="mt-4">Состав изделия</h4>
        <div id="components-wrapper">
            @foreach(old('components', $manufacturedProduct->components->toArray()) as $index => $component)
            <div class="component-row row mb-2">
                <div class="col-md-6">
                    <select name="components[{{ $index }}][supply_id]" class="form-select">
                        <option value="">— Выберите материал —</option>
                        @foreach($supplies as $supply)
                            <option value="{{ $supply->id }}" {{ $component['supply_id'] == $supply->id ? 'selected' : '' }}>
                                {{ $supply->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.01" name="components[{{ $index }}][quantity]" class="form-control" value="{{ $component['quantity'] }}" placeholder="Количество">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-remove-component">✖</button>
                </div>
            </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-primary mb-3" id="add-component">➕ Добавить компонент</button>

        <button type="submit" class="btn btn-success">Сохранить изменения</button>
        <a href="{{ route('manufactured_products.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>

<script>
let componentIndex = {{ count(old('components', $manufacturedProduct->components)) }};

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

document.addEventListener('click', function(e){
    if(e.target && e.target.classList.contains('btn-remove-component')){
        const rows = document.querySelectorAll('.component-row');
        if(rows.length > 1){
            e.target.closest('.component-row').remove();
        }
    }
});
</script>
@endsection
