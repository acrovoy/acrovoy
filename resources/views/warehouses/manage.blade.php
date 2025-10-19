@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">⚙️ Управление складом: {{ $warehouse->name }}</h1>
        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">⬅️ Назад к списку</a>
    </div>

    {{-- Кнопка добавления поставки --}}
    <a href="{{ route('warehouses.supplies.create', $warehouse) }}" class="btn btn-success mb-3">➕ Добавить поставку</a>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                
                <th>Дата поступл</th>
                <th>Номер документа</th>
                <th>Поставщик</th>
                <th>Артикул / Код товара</th>
                <th>Наименование товара</th>
                <th>Категория</th>
                <th>Ед. изм.</th>
                <th>Кол-во</th>
                <th>Цена за ед.</th>
                <th>Сумма</th>
                <th>Номер партии</th>
                <th>Кол-во списано</th>
                <th>Остаток</th>
                <th>Примечания</th>
                <th>Управление</th>
            </tr>
        </thead>
        <tbody>
        @if($warehouse->supplies && $warehouse->supplies->count())
            @foreach($warehouse->supplies as $index => $supply)
            <tr>
                
                <td>
                    {{ $supply->date_received ? $supply->date_received->format('d.m.Y') : '-' }}
                </td>
                <td>{{ $supply->document_number }}</td>
                <td>{{ $supply->supplier->name ?? $supply->supplier_name }}</td>
                <td>{{ $supply->sku }}</td>
                <td>{{ $supply->name }}</td>
                <td>{{ $supply->category->name ?? '-' }}</td>
                <td>{{ $supply->unit }}</td>
                <td>{{ $supply->quantity }}</td>
                <td>{{ number_format($supply->price_per_unit, 2) }} ₴</td>
                <td>{{ number_format($supply->quantity * $supply->price_per_unit, 2) }} ₴</td>
                <td>{{ $supply->batch_number }}</td>
                <td>{{ $supply->quantity_used ?? 0 }}</td>
                <td>{{ $supply->quantity_remaining ?? $supply->quantity }}</td>
                <td>{{ $supply->notes }}</td>
                <td>
                    <a href="{{ route('warehouses.supplies.edit', [$warehouse, $supply]) }}" class="btn btn-sm btn-primary">✏️</a>
                    <form action="{{ route('warehouses.supplies.destroy', [$warehouse, $supply]) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить поставку?')">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="16" class="text-center">Поставок пока нет</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
@endsection
