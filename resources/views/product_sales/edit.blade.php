@extends('layouts.app')

@section('content')
<div class="container">

    <h1>✏ Редактировать продажу #{{ $sale->id }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('product_sales.update', $sale->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Дата продажи</label>
            <input type="date" name="date" class="form-control" 
       value="{{ old('date', $sale->date ? \Carbon\Carbon::parse($sale->date)->format('Y-m-d') : '') }}" required>
        </div>

        <div class="mb-3">
            <label>Номер документа</label>
            <input type="text" name="document_number" class="form-control" value="{{ old('document_number', $sale->document_number) }}">
        </div>

        <div class="mb-3">
            <label>Клиент</label>
            <select name="client_id" class="form-control">
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ $client->id == old('client_id', $sale->client_id) ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label>Способ оплаты</label>
            <select name="payment_method_id" class="form-select">
                <option value="">— Выберите —</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}" {{ $method->id == old('payment_method_id', $sale->payment_method_id) ? 'selected' : '' }}>
                        {{ $method->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label>Склад</label>
            <select name="warehouse_id" class="form-control">
                <option value="">— Выберите —</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ $warehouse->id == old('warehouse_id', $sale->warehouse_id) ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr>
        <h5>🧾 Изделия</h5>

        <table class="table table-bordered align-middle" id="items-table">
            <thead>
                <tr>
                    <th>Изделие</th>
                    <th width="120">Кол-во</th>
                    <th width="150">Цена</th>
                    <th width="150">Сумма</th>
                    <th width="50">❌</th>
                </tr>
            </thead>
            <tbody>
                @php $items = old('items', $sale->items->toArray()); @endphp
                @foreach($items as $i => $item)
                    <tr>
                        <td>
                            <select name="items[{{ $i }}][supply_id]" class="form-control item-select">
                                <option value="">Выберите изделие</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $item['supply_id'] ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }}) - Доступно {{ $product->quantity_remaining }} шт.
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[{{ $i }}][quantity]" min="0" step="0.01" value="{{ $item['quantity'] }}" class="form-control item-quantity">
                        </td>
                        <td>
                            <input type="number" name="items[{{ $i }}][price]" min="0" step="0.01" value="{{ $item['price'] }}" class="form-control item-price">
                        </td>
                        <td class="item-subtotal">0.00</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-outline-primary mb-3" id="add-item">➕ Добавить изделие</button>

        <div class="mb-3">
            <label>💰 Итого</label>
            <input type="text" name="total_amount" id="total-amount" class="form-control" value="0.00" readonly>
        </div>

        <div class="mb-3">
            <label>Примечание</label>
            <textarea name="notes" class="form-control">{{ old('notes', $sale->notes) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">💾 Обновить</button>
        <a href="{{ route('product_sales.index') }}" class="btn btn-secondary">⬅ Назад</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    function recalcRow(row) {
        const qty = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        row.querySelector('.item-subtotal').textContent = (qty * price).toFixed(2);
    }

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('#items-table tbody tr').forEach(row => {
            total += parseFloat(row.querySelector('.item-subtotal').textContent) || 0;
        });
        document.getElementById('total-amount').value = total.toFixed(2);
    }

    // Пересчёт при изменении
    function attachEvents(row) {
        row.querySelectorAll('.item-quantity, .item-price').forEach(input => {
            input.addEventListener('input', () => {
                recalcRow(row);
                recalcTotal();
            });
        });
        row.querySelector('.remove-item').addEventListener('click', () => {
            row.remove();
            recalcTotal();
        });
    }

    // Инициализация существующих строк
    document.querySelectorAll('#items-table tbody tr').forEach(row => {
        recalcRow(row);
        attachEvents(row);
    });
    recalcTotal();

    // Добавление новой строки
    document.getElementById('add-item').addEventListener('click', function() {
        const tbody = document.querySelector('#items-table tbody');
        const index = tbody.querySelectorAll('tr').length;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="items[${index}][supply_id]" class="form-control item-select">
                    <option value="">Выберите изделие</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }}) - Доступно {{ $product->quantity_remaining }} шт.</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="items[${index}][quantity]" min="0" step="0.01" value="1" class="form-control item-quantity"></td>
            <td><input type="number" name="items[${index}][price]" min="0" step="0.01" value="0" class="form-control item-price"></td>
            <td class="item-subtotal">0.00</td>
            <td><button type="button" class="btn btn-danger btn-sm remove-item">×</button></td>
        `;
        tbody.appendChild(row);
        recalcRow(row);
        attachEvents(row);
        recalcTotal();
    });

});
</script>
@endsection
