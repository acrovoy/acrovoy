@extends('layouts.app')

@section('content')
<div class="container">
    <h1>📄 Просмотр продажи #{{ $productSale->id }}</h1>

    <p><strong>Дата:</strong> {{ $productSale->date->format('d.m.Y H:i') }}</p>
    <p><strong>Документ:</strong> {{ $productSale->document_number }}</p>
    <p><strong>Клиент:</strong> {{ $productSale->client->name ?? '—' }}</p>
    <p><strong>Способ оплаты:</strong> {{ $productSale->paymentMethod->name ?? '—' }}</p>
    <p><strong>Склад:</strong> {{ $productSale->warehouse->name ?? '—' }}</p>

    <p><strong>Статус:</strong>
        @if($productSale->status == 'draft')
            ❗ Черновик
        @elseif($productSale->status == 'paid')
            ✅ Оплачено
        @elseif($productSale->status == 'shipped')
            🚚 Отправлено
        @endif
    </p>

    <hr>
    <h5>Товары</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Изделие</th>
                <th>SKU</th>
                <th>Количество</th>
                <th>Цена за единицу</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productSale->items as $item)
            <tr>
                <td>{{ $item->name ?? '—' }}</td>
                <td>{{ $item->sku ?? '—' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Итого:</strong> {{ number_format($productSale->total_amount, 2) }}</p>
    <p><strong>Примечание:</strong> {{ $productSale->notes }}</p>

    <a href="{{ route('product_sales.index') }}" class="btn btn-secondary">⬅ Назад</a>

    {{-- Редактировать можно только в статусе draft --}}
    @if($productSale->status === 'draft')
        <a href="{{ route('product_sales.edit', $productSale->id) }}" class="btn btn-primary">✏️ Редактировать</a>
    @endif

    {{-- Кнопки изменений статуса --}}
    @if($productSale->status === 'draft')
        {{-- Оплатить --}}
        <form action="{{ route('product_sales.pay', $productSale->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success">✅ Оплачено</button>
        </form>

    @elseif($productSale->status === 'paid')
        {{-- Отправить --}}
        <form action="{{ route('product_sales.ship', $productSale->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-info">🚚 Отправлено</button>
        </form>

        {{-- Вернуть в черновик --}}
        <form action="{{ route('product_sales.draft', $productSale->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-warning">↩ Вернуть в черновик</button>
        </form>

    @elseif($productSale->status === 'shipped')
        {{-- Возврат — вернёт обратно в paid --}}
        <form action="{{ route('product_sales.return', $productSale->id) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-secondary">↩ Возврат</button>
        </form>
    @endif

</div>
@endsection
