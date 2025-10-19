@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Редактировать расход</h1>

    <form method="POST" action="{{ route('expense.update', $expense) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Дата</label>
            <input type="date" name="date" value="{{ $expense->date }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Документ / № счёта</label>
            <input type="text" name="document_number" value="{{ $expense->document_number }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Поставщик</label>
            <input type="text" name="supplier" value="{{ $expense->supplier }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Описание</label>
            <input type="text" name="description" value="{{ $expense->description }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Категория расхода</label>
            <input type="text" name="category" value="{{ $expense->category }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Сумма</label>
            <input type="number" name="amount" value="{{ $expense->amount }}" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Способ оплаты</label>
            <input type="text" name="payment_method" value="{{ $expense->payment_method }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Статья учёта</label>
            <input type="text" name="account_article" value="{{ $expense->account_article }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Привязка к складу/производству</label>
            <input type="text" name="warehouse" value="{{ $expense->warehouse }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Комментарий</label>
            <input type="text" name="comment" value="{{ $expense->comment }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="{{ route('accounting.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection
