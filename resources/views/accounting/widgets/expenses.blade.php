
{{-- Виджет Расходы --}}
<h3 class="mt-5 ">Расходы <a href="{{ route('expense.create') }}" class="btn btn-danger">➕</a></h3>

<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="С">
    </div>
    <div class="col-auto">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="По">
    </div>
    <div class="col-auto">
        <select name="expense_category" class="form-select">
            <option value="">Все категории</option>
            @foreach($expenseCategories as $cat)
                <option value="{{ $cat->name }}" @if(request('expense_category') == $cat->name) selected @endif>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Фильтровать</button>
        <a href="{{ route('accounting.index') }}" class="btn btn-secondary">Сбросить</a>
    </div>
</form>

<div class='mb-5' style="max-height: 400px; overflow-y: auto;">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Документ</th>
                <th>Поставщик</th>
                <th>Описание</th>
                <th>Категория</th>
                <th>Сумма (₴)</th>
                <th>Способ оплаты</th>
                <th>Статья учёта</th>
                <th>Склад</th>
                <th>Комментарий</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ \Carbon\Carbon::parse($expense->date)->format('d.m.Y') }}</td>
                <td>{{ $expense->document_number }}</td>
                <td>{{ $expense->supplierRelation?->name ?? '-' }}</td>
                <td>{{ $expense->description }}</td>
                <td>{{ $expense->category }}</td>
                <td>{{ number_format($expense->amount, 2) }} ₴</td>
                <td>{{ $expense->payment_method }}</td>
                <td>{{ $expense->account_article }}</td>
                <td>{{ $expense->warehouse }}</td>
                <td>{{ $expense->comment }}</td>
                <td>
                    <a href="{{ route('expense.edit', $expense) }}" class="btn btn-sm btn-warning">✏️</a>
                    <form action="{{ route('expense.destroy', $expense) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить расход?')">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
