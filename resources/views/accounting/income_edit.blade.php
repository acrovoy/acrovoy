@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">✏️ Редактировать доход</h1>
    <form method="POST" action="{{ route('income.update', $income) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Дата</label>
            <input type="date" name="date" class="form-control"
            value="{{ old('date', $income->date ? \Carbon\Carbon::parse($income->date)->format('Y-m-d') : '') }}" required>
            
        </div>
        <div class="mb-3">
            <label>Документ/№ счёта</label>
            <input type="text" name="document_number" value="{{ $income->document_number }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Клиент</label>
            <input type="text" name="client" value="{{ $income->client }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Описание сделки</label>
            <input type="text" name="description" value="{{ $income->description }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Категорияф дохода</label>
            <input type="text" name="category" value="{{ $income->category }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Сумма (грн)</label>
            <input type="number" step="0.01" name="amount" value="{{ $income->amount }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Способ оплаты</label>
            <input type="text" name="payment_method" value="{{ $income->payment_method }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Статья учёта</label>
            <input type="text" name="account_article" value="{{ $income->account_article }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Привязка к складу</label>
            <input type="text" name="warehouse" value="{{ $income->warehouse }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>Комментарий</label>
            <input type="text" name="comment" value="{{ $income->comment }}" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
@endsection
