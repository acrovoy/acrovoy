@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Редактировать поставку: {{ $supply->name }} ({{ $warehouse->name }})</h1>

    <form action="{{ route('warehouses.supplies.update', [$warehouse, $supply]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Дата поступления</label>
            <input type="date" name="date_received" class="form-control" value="{{ $supply->date_received->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label>Номер документа</label>
            <input type="text" name="document_number" class="form-control" value="{{ $supply->document_number }}">
        </div>

        <div class="mb-3">
            <label>Поставщик</label>
            <select name="supplier_id" class="form-control">
                <option value="">- выберите поставщика -</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @if($supply->supplier_id == $supplier->id) selected @endif>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
            <small>или введите имя вручную</small>
            <input type="text" name="supplier_name" class="form-control mt-1" value="{{ $supply->supplier_name }}">
        </div>

        <div class="mb-3">
            <label>Артикул / Код товара</label>
            <input type="text" name="sku" class="form-control" value="{{ $supply->sku }}" required>
        </div>

        <div class="mb-3">
            <label>Наименование товара</label>
            <input type="text" name="name" class="form-control" value="{{ $supply->name }}" required>
        </div>

        <div class="mb-3">
            <label>Категория</label>
            <select name="category_id" class="form-control">
                <option value="">- выберите -</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($supply->category_id == $category->id) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Ед. изм.</label>
            <select name="unit" class="form-control" required>
                <option value="">- выберите -</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->name }}" @if($supply->unit == $unit->name) selected @endif>
                        {{ $unit->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Количество</label>
            <input type="number" step="0.01" name="quantity" class="form-control" value="{{ $supply->quantity }}" required>
        </div>

        <div class="mb-3">
            <label>Цена за ед.</label>
            <input type="number" step="0.01" name="price_per_unit" class="form-control" value="{{ $supply->price_per_unit }}" required>
        </div>

        <div class="mb-3">
            <label>Номер партии</label>
            <input type="text" name="batch_number" class="form-control" value="{{ $supply->batch_number }}">
        </div>

        <div class="mb-3">
            <label>Примечания</label>
            <textarea name="notes" class="form-control">{{ $supply->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Обновить</button>
        <a href="{{ route('warehouses.manage', $warehouse) }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection
