@extends('layouts.app')

@section('content')
<div class="container">
    <h1>👁 Просмотр цены</h1>

    <table class="table table-bordered">
        <tr>
            <th>SKU</th>
            <td>{{ $productPrice->sku }}</td>
        </tr>
        <tr>
            <th>Название</th>
            <td>{{ $productPrice->productModel->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Тип цены</th>
            <td>{{ $productPrice->type->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Цена</th>
            <td>{{ $productPrice->price }}</td>
        </tr>
       
    </table>

    <a href="{{ route('product_prices.index') }}" class="btn btn-secondary">⬅ Назад</a>
    <a href="{{ route('product_prices.edit', $productPrice->id) }}" class="btn btn-primary">✏ Редактировать</a>
</div>
@endsection
