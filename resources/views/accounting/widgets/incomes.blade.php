{{-- Виджет Доходы --}}
{{-- Виджет Доходы --}}
<h3>Доходы <a href="{{ route('income.create') }}" class="btn btn-success me-2">➕</a></h3>

<form method="GET" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="С">
    </div>
    <div class="col-auto">
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="По">
    </div>
    <div class="col-auto">
        <select name="income_category" class="form-select">
            <option value="">Все категории</option>
            @foreach($incomeCategories as $cat)
                <option value="{{ $cat->name }}" @if(request('income_category') == $cat->name) selected @endif>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary">Фильтровать</button>
        <a href="{{ route('accounting.index') }}" class="btn btn-secondary">Сбросить</a>
    </div>
</form>


<div style="max-height: 400px; overflow-y: auto;">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Документ</th>
                <th>Клиент</th>
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
            @foreach($incomes as $income)
            <tr>
                <td>{{ \Carbon\Carbon::parse($income->date)->format('d.m.Y') }}</td>
                <td>{{ $income->document_number }}</td>
                <td>{{ $income->clientRelation?->name ?? '-' }}</td>
                <td>{{ $income->description }}</td>
                <td>{{ $income->category }}</td>
                <td>{{ number_format($income->amount, 2) }} ₴</td>
                <td>{{ $income->payment_method }}</td>
                <td>{{ $income->account_article }}</td>
                <td>{{ $income->warehouse }}</td>
                <td>{{ $income->comment }}</td>
                <td>
                    <a href="{{ route('income.edit', $income) }}" class="btn btn-sm btn-warning">✏️</a>
                    <form action="{{ route('income.destroy', $income) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить доход?')">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
