@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-0">💰 Продажи</h1>



        <div class="d-flex">
            {{-- Бухгалтерия --}}
            <a href="{{ route('accounting.index') }}" class="ms-3 fs-4 text-decoration-none" title="Бухгалтерия">📘</a>
            
            {{-- ПРАЙС --}}
            <a href="{{ route('product_prices.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">📋</a>
            {{-- Склад --}}
            <a href="{{ route('warehouses.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">🏬</a>
            {{-- Производство --}}
            <a href="{{ route('manufactured_products.index') }}" class="ms-3 fs-4 text-decoration-none" title="Производство">🏭</a>
            {{-- Модели товаров --}}
            <a href="{{ route('product_models.index') }}" class="ms-3 fs-4 text-decoration-none" title="Модели товаров">🪑</a>
            {{-- Клиенты --}}
            <a href="{{ route('clients.index') }}" class="ms-3 fs-4 text-decoration-none" title="Клиенты">👥</a>
            {{-- Поставщики --}}
            <a href="{{ route('suppliers.index') }}" class="ms-3 fs-4 text-decoration-none" title="Поставщики">🤝</a>
            {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>
            {{-- Настройки --}}
            <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('product_sales.create') }}" class="btn btn-success mb-3">+ Новая продажа</a>


    <div class="mb-3">
    <a href="{{ route('product_sales.index') }}" 
       class="btn btn-sm {{ request('warehouse_id') ? 'btn-outline-secondary' : 'btn-secondary' }}">
        Все склады
    </a>

    @foreach($warehouses as $warehouse)
        <a href="{{ route('product_sales.index', ['warehouse_id' => $warehouse->id]) }}"
           class="btn btn-sm {{ request('warehouse_id') == $warehouse->id ? 'btn-primary' : 'btn-outline-primary' }}">
            {{ $warehouse->name }}
        </a>
    @endforeach
</div>


    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Склад</th>
                <th>Документ</th>
                <th>Клиент</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->date->format('d.m.Y') }}</td>
                    <td>{{ $sale->warehouse->name ?? '—' }}</td>
                    <td>{{ $sale->document_number ?? '-' }}</td>
                    <td>{{ $sale->client->name ?? '—' }}</td>
                    <td>{{ number_format($sale->total_amount, 2, ',', ' ') }} ₴</td>
                    <td>
                        @if($sale->status === 'paid')
                            <span class="badge bg-success">Оплачено</span>
                        @elseif($sale->status === 'draft')
                            <span class="badge bg-warning text-dark">Черновик</span>
                        @elseif($sale->status === 'shipped')
                            <span class="badge bg-secondary">Отправлено</span>
                        @endif
                    </td>
                    <td class="d-flex gap-1">
                    <a href="{{ route('product_sales.show', $sale) }}" class="btn btn-sm btn-primary">Просмотр</a>

                    {{-- Показывать Редактировать, только если НЕ оплачено/НЕ отправлено --}}
                    @if(!in_array($sale->status, ['paid', 'shipped']))
                        <a href="{{ route('product_sales.edit', $sale) }}" class="btn btn-sm btn-warning">✏️ Редактировать</a>
                    @endif

                    {{-- Показывать Удалить, только если НЕ оплачено/НЕ отправлено --}}
                    @if(!in_array($sale->status, ['paid', 'shipped']))
                        <form action="{{ route('product_sales.destroy', $sale) }}" method="POST" 
                            onsubmit="return confirm('Вы уверены, что хотите удалить эту продажу?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">❌ Удалить</button>
                        </form>
                    @endif

                    {{-- Если оплачен → кнопка Отправлено --}}
                    @if($sale->status === 'paid')
                        <form action="{{ route('product_sales.ship', $sale->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">🚚 Отправлено</button>
                        </form>
                    @endif

                    {{-- Если отправлен → кнопка Возврат --}}
                    @if($sale->status === 'shipped')
                        <form action="{{ route('product_sales.return', $sale->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-secondary">↩️ Возврат</button>
                        </form>
                    @endif
                </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
    <tr>
        <th colspan="3" class="text-end">Итого:</th>

        {{-- Сумма --}}
        <th id="total-uah">{{ number_format($totalSum, 2, '.', '') }}</th>

        {{-- 23% --}}
        <th id="total-23">
            {{ number_format($totalSum * 0.23, 2, '.', '') }} ₴
            <span class="text-danger" style="font-size: 0.65rem;">(23%)</span>
        </th>

        {{-- Курс --}}
        <th>
            <input type="number" step="0.01" id="usd-rate" 
       class="form-control form-control-sm"
       value="42">
        </th>

        {{-- $ от 23% --}}
        <th id="total-usd">0</th>

        <th colspan="2"></th>
    </tr>
</tfoot>

    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const rateInput = document.getElementById('usd-rate');
    const total23 = document.getElementById('total-23');
    const totalUsd = document.getElementById('total-usd');

    function recalc() {
        let rate = parseFloat(rateInput.value);
        let total = parseFloat(total23.textContent);

        if (!rate || rate <= 0) {
            totalUsd.textContent = '0';
            return;
        }

        let usd = total / rate;
        totalUsd.textContent = usd.toFixed(2) + ' $';
    }

    rateInput.addEventListener('input', recalc);

    // 🔥 сразу считаем при загрузке
    recalc();
});
</script>


@endsection
