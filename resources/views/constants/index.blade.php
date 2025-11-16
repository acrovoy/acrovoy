@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4 mt-4">
        <h1 class="mb-0">⚙️ Настройки</h1>

        <div class="d-flex">


        {{-- ПРАЙС --}}
            <a href="{{ route('product_prices.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">📋</a>
            
        {{-- ПРОДАЖИ --}}
            <a href="{{ route('product_sales.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">💰</a>

            
             {{-- Бухгалтерия --}}
            <a href="{{ route('accounting.index') }}" class="ms-3 fs-4 text-decoration-none" title="Бухгалтерия">📘</a>
            
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
        </div>


    </div>

    {{-- Сообщения --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- КАТЕГОРИИ ДОХОДА --}}
    <h3>Категории дохода</h3>
    <form action="{{ route('constants.income.store') }}" method="POST" class="d-flex mb-3">
        @csrf
        <input type="text" name="name" class="form-control me-2" placeholder="Новая категория дохода" required>
        <button type="submit" class="btn btn-success">➕ Добавить</button>
    </form>
    <table class="table table-bordered mb-5">
        <thead>
            <tr>
                <th>Название</th>
                <th width="150">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incomeCategories as $category)
            <tr>
                <td>
                    <form action="{{ route('constants.income.update', $category) }}" method="POST" class="d-flex">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $category->name }}" class="form-control me-2">
                        <button type="submit" class="btn btn-warning btn-sm">💾</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('constants.income.destroy', $category) }}" method="POST" onsubmit="return confirm('Удалить категорию?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- КАТЕГОРИИ РАСХОДА --}}
    <h3>Категории расходов</h3>
    <form action="{{ route('constants.expense.store') }}" method="POST" class="d-flex mb-3">
        @csrf
        <input type="text" name="name" class="form-control me-2" placeholder="Новая категория расхода" required>
        <button type="submit" class="btn btn-danger">➕ Добавить</button>
    </form>
    <table class="table table-bordered mb-5">
        <thead>
            <tr>
                <th>Название</th>
                <th width="150">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenseCategories as $category)
            <tr>
                <td>
                    <form action="{{ route('constants.expense.update', $category) }}" method="POST" class="d-flex">
                        @csrf
                        @method('PUT')
                        <input type="text" name="name" value="{{ $category->name }}" class="form-control me-2">
                        <button type="submit" class="btn btn-warning btn-sm">💾</button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('constants.expense.destroy', $category) }}" method="POST" onsubmit="return confirm('Удалить категорию?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- СПОСОБЫ ОПЛАТЫ --}}
<h3>Способы оплаты</h3>
<form action="{{ route('constants.payment.store') }}" method="POST" class="d-flex mb-3">
    @csrf
    <input type="text" name="name" class="form-control me-2" placeholder="Новый способ оплаты" required>
    <button type="submit" class="btn btn-primary">➕ Добавить</button>
</form>
<table class="table table-bordered mb-5">
    <thead>
        <tr>
            <th>Название</th>
            <th width="150">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\PaymentMethod::orderBy('name')->get() as $method)
        <tr>
            <td>
                <form action="{{ route('constants.payment.update', $method) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $method->name }}" class="form-control me-2">
                    <button type="submit" class="btn btn-warning btn-sm">💾</button>
                </form>
            </td>
            <td>
                <form action="{{ route('constants.payment.destroy', $method) }}" method="POST" onsubmit="return confirm('Удалить способ оплаты?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


{{-- СТАТЬИ УЧЁТА --}}
<h3>Статьи учёта</h3>
<form action="{{ route('constants.account.store') }}" method="POST" class="d-flex mb-3">
    @csrf
    <input type="text" name="name" class="form-control me-2" placeholder="Новая статья учёта" required>
    <button type="submit" class="btn btn-secondary">➕ Добавить</button>
</form>
<table class="table table-bordered mb-5">
    <thead>
        <tr>
            <th>Название</th>
            <th width="150">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\AccountArticle::orderBy('name')->get() as $article)
        <tr>
            <td>
                <form action="{{ route('constants.account.update', $article) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $article->name }}" class="form-control me-2">
                    <button type="submit" class="btn btn-warning btn-sm">💾</button>
                </form>
            </td>
            <td>
                <form action="{{ route('constants.account.destroy', $article) }}" method="POST" onsubmit="return confirm('Удалить статью учёта?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- КАТЕГОРИИ ПОСТАВОК --}}
<h3>Категории поставок</h3>
<form action="{{ route('constants.supply.store') }}" method="POST" class="d-flex mb-3">
    @csrf
    <input type="text" name="name" class="form-control me-2" placeholder="Новая категория поставки" required>
    <button type="submit" class="btn btn-info">➕ Добавить</button>
</form>
<table class="table table-bordered mb-5">
    <thead>
        <tr>
            <th>Название</th>
            <th width="150">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\SupplyCategory::orderBy('name')->get() as $category)
        <tr>
            <td>
                <form action="{{ route('constants.supply.update', $category) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $category->name }}" class="form-control me-2">
                    <button type="submit" class="btn btn-warning btn-sm">💾</button>
                </form>
            </td>
            <td>
                <form action="{{ route('constants.supply.destroy', $category) }}" method="POST" onsubmit="return confirm('Удалить категорию поставки?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- ЕДИНИЦЫ ИЗМЕРЕНИЯ --}}
<h3>Единицы измерения</h3>
<form action="{{ route('constants.unit.store') }}" method="POST" class="d-flex mb-3">
    @csrf
    <input type="text" name="name" class="form-control me-2" placeholder="Новая единица измерения" required>
    <button type="submit" class="btn btn-success">➕ Добавить</button>
</form>
<table class="table table-bordered mb-5">
    <thead>
        <tr>
            <th>Название</th>
            <th width="150">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\Unit::orderBy('name')->get() as $unit)
        <tr>
            <td>
                <form action="{{ route('constants.unit.update', $unit) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $unit->name }}" class="form-control me-2">
                    <button type="submit" class="btn btn-warning btn-sm">💾</button>
                </form>
            </td>
            <td>
                <form action="{{ route('constants.unit.destroy', $unit) }}" method="POST" onsubmit="return confirm('Удалить единицу измерения?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- УСЛОВИЯ ОПЛАТЫ --}}
<h3>Условия оплаты</h3>
<form action="{{ route('constants.payment_term.store') }}" method="POST" class="d-flex mb-3">
    @csrf
    <input type="text" name="name" class="form-control me-2" placeholder="Новое условие оплаты" required>
    <button type="submit" class="btn btn-primary">➕ Добавить</button>
</form>
<table class="table table-bordered mb-5">
    <thead>
        <tr>
            <th>Название</th>
            <th width="150">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\PaymentTerm::orderBy('name')->get() as $term)
        <tr>
            <td>
                <form action="{{ route('constants.payment_term.update', $term) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $term->name }}" class="form-control me-2">
                    <button type="submit" class="btn btn-warning btn-sm">💾</button>
                </form>
            </td>
            <td>
                <form action="{{ route('constants.payment_term.destroy', $term) }}" method="POST" onsubmit="return confirm('Удалить условие оплаты?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- ТИПЫ ЦЕН --}}
<h3>Типы цен</h3>
<form action="{{ route('constants.price_type.store') }}" method="POST" class="d-flex mb-3">
    @csrf
    <input type="text" name="name" class="form-control me-2" placeholder="Новый тип цены" required>
    <input type="text" name="code" class="form-control me-2" placeholder="Код (например: retail, vip, wholesale)" required>
    <button type="submit" class="btn btn-primary">➕ Добавить</button>
</form>

<table class="table table-bordered mb-5">
    <thead>
        <tr>
            <th>Название</th>
            <th>Код</th>
            <th width="150">Действия</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Models\PriceType::orderBy('name')->get() as $type)
        <tr>
            <td>
                <form action="{{ route('constants.price_type.update', $type) }}" method="POST" class="d-flex">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $type->name }}" class="form-control me-2">
                    <input type="text" name="code" value="{{ $type->code }}" class="form-control me-2">
                    <button type="submit" class="btn btn-warning btn-sm">💾</button>
                </form>
            </td>
            <td>{{ $type->code }}</td>
            <td>
                <form action="{{ route('constants.price_type.destroy', $type) }}" method="POST" onsubmit="return confirm('Удалить тип цены?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
@endsection
