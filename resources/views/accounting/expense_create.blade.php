@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Добавить расход</h2>

    <form method="POST" action="{{ route('expense.store') }}">
        @csrf

        <div class="mb-3">
            <label>Дата</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Документ / № счёта</label>
            <input type="text" name="document_number" class="form-control">
        </div>

        {{-- Поставщик --}}
        <div class="mb-3">
            <label>Поставщик</label>
            <div class="input-group">
                <select name="supplier_id" class="form-select">
                    <option value="">— Выберите поставщика —</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-primary" id="addSupplierBtn">➕ Новый</button>
            </div>
            <input type="text" name="supplier_name" id="newSupplierInput" class="form-control mt-2" placeholder="Введите имя поставщика" style="display:none;">
        </div>

        <div class="mb-3">
            <label>Описание</label>
            <input type="text" name="description" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Категория расхода</label>
            <select name="category" class="form-select" required>
                <option value="">— Выберите категорию —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Сумма (грн)</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Способ оплаты</label>
            <select name="payment_method" class="form-select">
                <option value="">— Выберите —</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->name }}">{{ $method->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Статья учёта</label>
            <select name="account_article" class="form-select">
                <option value="">— Выберите —</option>
                @foreach($articles as $article)
                    <option value="{{ $article->name }}">{{ $article->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Привязка к складу</label>
            <select name="warehouse" class="form-select">
                <option value="">— Выберите склад —</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->name }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Комментарий</label>
            <input type="text" name="comment" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">💾 Сохранить</button>
        <a href="{{ route('accounting.index') }}" class="btn btn-secondary">⬅ Назад</a>
    </form>
</div>

<script>
document.getElementById('addSupplierBtn').addEventListener('click', function() {
    document.getElementById('newSupplierInput').style.display = 'block';
    this.disabled = true;
});
</script>
@endsection
