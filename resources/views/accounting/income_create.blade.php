@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Добавить доход</h2>

    <form method="POST" action="{{ route('income.store') }}">
        @csrf

        <div class="mb-3">
            <label>Дата</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Документ / № счёта</label>
            <input type="text" name="document_number" class="form-control">
        </div>

        {{-- Клиент --}}
        <div class="mb-3">
            <label>Клиент</label>
            <div class="input-group">
                <select name="client_id" class="form-select">
                    <option value="">— Выберите клиента —</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-primary" id="addClientBtn">➕ Новый</button>
            </div>
            <input type="text" name="client_name" id="newClientInput" class="form-control mt-2" placeholder="Введите имя клиента" style="display:none;">
        </div>

        <div class="mb-3">
            <label>Описание сделки</label>
            <input type="text" name="description" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Категория дохода</label>
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
document.getElementById('addClientBtn').addEventListener('click', function() {
    document.getElementById('newClientInput').style.display = 'block';
    this.disabled = true;
});
</script>
@endsection
