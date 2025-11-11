@extends('layouts.app')

@section('content')
<div class="container">
    <h1>➕ Создать продажу</h1>

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

    <form method="POST" action="{{ route('product_sales.store') }}">
        @csrf

        <div class="mb-3">
            <label>Дата продажи</label>
            <input type="datetime-local" name="date" class="form-control" 
           value="{{ old('date', date('Y-m-d\TH:i')) }}" required>
        </div>

        <div class="mb-3">
            <label>Номер документа</label>
            <input type="text" name="document_number" class="form-control" value="{{ old('document_number') }}">
        </div>

        <div class="mb-3">
            <label>Клиент</label>
            <select name="client_id" class="form-control">
                <option value="">Выберите клиента</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Склад</label>
            <select name="warehouse_id" class="form-control">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr>
        <h5>🧾 Изделия</h5>

        <table class="table table-bordered align-middle" id="itemsTable">
            <thead>
                <tr>
                    <th>Изделие</th>
                    <th width="120">Кол-во</th>
                    <th width="150">Цена</th>
                    <th width="150">Сумма</th>
                    <th width="50"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="items[0][supply_id]" class="form-control product-select" onchange="updateProduct(this)">
                            <option value="">Выберите изделие</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    data-remaining="{{ $product->total_quantity }}"
                                    data-sku="{{ $product->sku }}">
                                    {{ $product->name }} ({{ $product->sku }}) – {{ $product->total_quantity }} шт.
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[0][quantity]" class="form-control qty-input" min="0.01" step="0.01" value="1" onchange="recalcRow(this)">
                    </td>
                    <td>
                        <input type="number" name="items[0][price]" class="form-control price-input" min="0" step="0.01" value="0" onchange="recalcRow(this)">
                    </td>
                    <td class="row-total">0.00</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">✖</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-outline-primary mb-3" onclick="addRow()">➕ Добавить изделие</button>

        <div class="mb-3">
            <label>💰 Итого</label>
            <input type="text" name="total_amount" class="form-control" value="0.00" readonly>
        </div>

        <div class="mb-3">
            <label>Примечание</label>
            <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">💾 Сохранить</button>
        <a href="{{ route('product_sales.index') }}" class="btn btn-secondary">⬅ Назад</a>
    </form>
</div>

<script>
let rowIndex = 1;

// Добавление строки
function addRow() {
    let table = document.querySelector('#itemsTable tbody');

    let row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="items[${rowIndex}][supply_id]" class="form-control product-select" onchange="updateProduct(this)">
                <option value="">Выберите изделие</option>
                @foreach($products as $product)
                    <option
                        value="{{ $product->id }}"
                        data-price="{{ $product->price ?? 0 }}"
                        data-remaining="{{ $product->quantity_remaining }}"
                        data-sku="{{ $product->sku }}"
                    >
                        {{ $product->name }} ({{ $product->sku }}) – {{ $product->quantity_remaining }} шт.
                    </option>
                @endforeach
            </select>
        </td>
        <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control qty-input" min="0.01" step="0.01" value="1" onchange="recalcRow(this)"></td>
        <td><input type="number" name="items[${rowIndex}][price]" class="form-control price-input" min="0" step="0.01" value="0" onchange="recalcRow(this)"></td>
        <td class="row-total">0.00</td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">✖</button></td>
    `;

    table.appendChild(row);
    rowIndex++;
}

// Удаление строки
function removeRow(btn) {
    btn.closest('tr').remove();
    recalcTotal();
}

// Подстановка цены при выборе товара
function updateProduct(select) {
    let price = select.selectedOptions[0].dataset.price;
    let priceInput = select.closest('tr').querySelector('.price-input');

    if (price > 0) {
        priceInput.value = price;
    }

    recalcRow(priceInput);
}

// Пересчёт строки
function recalcRow(input) {
    let tr = input.closest('tr');
    let qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
    let price = parseFloat(tr.querySelector('.price-input').value) || 0;
    let total = qty * price;

    tr.querySelector('.row-total').innerText = total.toFixed(2);
    recalcTotal();
}

// Итог по всем строкам
function recalcTotal() {
    let sum = 0;
    document.querySelectorAll('.row-total').forEach(cell => {
        sum += parseFloat(cell.innerText);
    });
    document.querySelector('[name="total_amount"]').value = sum.toFixed(2);
}
</script>

@endsection
