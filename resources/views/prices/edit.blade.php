@extends('layouts.app')

@section('content')
<div class="container">
    <h1>✏ Редактировать цену</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('product_prices.update', $productPrice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="sku">Артикул (SKU)</label>
            <select name="sku" id="sku" class="form-control" required>
                @foreach($models as $model)
                    <option value="{{ $model->sku }}" @if($productPrice->sku == $model->sku) selected @endif>
                        {{ $model->sku }} - {{ $model->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price_type_id">Тип цены</label>
            <select name="price_type_id" id="price_type_id" class="form-control" required>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @if($productPrice->price_type_id == $type->id) selected @endif>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price">Цена</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ $productPrice->price }}" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-success">💾 Обновить</button>
        <a href="{{ route('product_prices.index') }}" class="btn btn-secondary">⬅ Назад</a>
    </form>
</div>
@endsection
