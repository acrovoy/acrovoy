<div class="row g-3 mt-0 mb-5">

    {{-- 1) Отчетность --}}
<div class="col-12 col-md-6 col-lg-3">
    <div class="card p-3 shadow-sm h-100 border-primary">
        <div class="d-flex align-items-center mb-2">
            <span class="fs-4 me-2">📊</span>
            <h5 class="mb-0">Отчетность</h5>
        </div>
        @php
            $profitColor = $netProfit >= 0 ? 'text-success' : 'text-danger';
            $afterTaxColor = $profitAfterTax >= 0 ? 'text-success' : 'text-danger';
        @endphp

        <p class="mb-1 text-success">Доходы: <strong>{{ number_format($totalIncome, 2) ?? 0 }} ₴</strong></p>
        <p class="mb-1 text-danger">Расходы: <strong>{{ number_format($totalExpense, 2) ?? 0 }} ₴</strong></p>
        <p class="mb-1">Валовая прибыль: <strong>{{ number_format($grossProfit, 2) ?? 0 }} ₴</strong></p>
        <p class="mb-1">Операционные расходы: <strong>{{ number_format($operatingExpenses, 2) ?? 0 }} ₴</strong></p>
        <p class="mb-1 {{ $profitColor }}">Чистая прибыль: <strong>{{ number_format($netProfit, 2) ?? 0 }} ₴</strong></p>
        <p class="mb-1">Налоги: <strong>{{ number_format($tax, 2) ?? 0 }} ₴</strong></p>
        <p class="mb-1 {{ $afterTaxColor }}">Прибыль после налога: <strong>{{ number_format($profitAfterTax, 2) ?? 0 }} ₴</strong></p>
        <p class="mb-0">Рентабельность: <strong>{{ number_format($rental, 2) ?? 0 }}%</strong></p>
    </div>
</div>



  {{-- 2) Последние поступившие на склад товары --}}
<div class="col-12 col-md-6 col-lg-6">
    <div class="card p-3 shadow-sm h-100 border-success">
        <div class="d-flex align-items-center mb-2">
            <span class="fs-4 me-2">🏷️</span>
            <h5 class="mb-0">Склад продукции</h5>
        </div>
        <div style="max-height: 200px; overflow-y: auto;">
            <ul class="list-group list-group-flush">
                @foreach($suppliesReady as $supply)
                    <li class="list-group-item p-1">
                        <div class="d-flex justify-content-between">
                            <span><strong>{{ $supply->name }}</strong> ({{ $supply->quantity_remaining ?? 0 }} {{ $supply->unit ?? '' }})</span>
                            <span class="badge bg-secondary">{{ $supply->warehouse->name ?? '—' }}</span>
                        </div>
                        <small class="text-muted">Дата: <span class="text-success">{{ $supply->date_received->format('d.m.Y') }}</span> Поставщик: <span class="text-success">{{ $supply->supplier?->name ?? $supply->supplier_name ?? '—' }}</span> Артикул: <span class="text-success">{{ $supply->sku }}</span></small>
                    </li>
                @endforeach
                @if($latestSupplies->isEmpty())
                    <li class="list-group-item p-1 text-muted">Нет поступлений</li>
                @endif
            </ul>
        </div>
    </div>
</div>

 {{-- 3) Последние изделия из производства --}}
    <div class="col-12 col-md-6 col-lg-3">
    <div class="card p-3 shadow-sm h-100 border-warning">
        <div class="d-flex align-items-center mb-2">
            <span class="fs-4 me-2">🏭</span>
            <h5 class="mb-0">На производстве</h5>
        </div>
        <div style="max-height: 200px; overflow-y: auto;">
            <ul class="list-group list-group-flush">
                @forelse($producedProducts as $product)
                    <li class="list-group-item p-1 d-flex justify-content-between">
                        <div>
                            <strong>{{ $product->name }}</strong><br>
                            <small>{{ $product->warehouse?->name ?? '—' }}</small>
                        </div>
                        <span class="badge bg-warning">Произведено</span>
                    </li>
                @empty
                    <li class="list-group-item p-1 text-muted">Нет изделий на производстве</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

 {{-- 2) Последние поступившие на склад товары --}}
<div class="col-12 col-md-6 col-lg-6">
    <div class="card p-3 shadow-sm h-100 border-success">
        <div class="d-flex align-items-center mb-2">
            <span class="fs-4 me-2">🧱</span>
            <h5 class="mb-0">Поступление сырья на склад</h5>
        </div>
        <div style="max-height: 200px; overflow-y: auto;">
            <ul class="list-group list-group-flush">
                @foreach($suppliesRaw as $supply)
                    <li class="list-group-item p-1">
                        <div class="d-flex justify-content-between">
                            <span>{{ $supply->date_received->format('d.m.Y') }} <strong>{{ $supply->name }}</strong> ({{ $supply->quantity ?? 0 }} {{ $supply->unit ?? '' }})</span>
                            <span class="badge bg-secondary">{{ $supply->warehouse->name ?? '—' }}</span>
                        </div>
                        <small class="text-muted">Поставщик: <span class="text-success">{{ $supply->supplier?->name ?? $supply->supplier_name ?? '—' }}</span> Артикул: <span class="text-success">{{ $supply->sku }}</span></small>
                    </li>
                @endforeach
                @if($latestSupplies->isEmpty())
                    <li class="list-group-item p-1 text-muted">Нет поступлений</li>
                @endif
            </ul>
        </div>
    </div>
</div>

   

   {{-- 4) Остатки на складе --}}
<div class="col-12 col-md-6 col-lg-6">
    <div class="card p-3 shadow-sm h-100 border-info">
        <div class="d-flex align-items-center mb-2">
            <span class="fs-4 me-2">📦</span>
            <h5 class="mb-0">Остатки на складе</h5>
        </div>
        <div style="max-height: 200px; overflow-y: auto;">
            <ul class="list-group list-group-flush">
                @foreach($warehouses as $warehouse)
                    <li class="list-group-item p-1 list-group-item-primary">
                        <strong>{{ $warehouse->name }}</strong>
                    </li>
                    @forelse($warehouse->supplies as $supply)
                        @php
                            $remaining = $supply->quantity_remaining ?? ($supply->quantity ?? 0);
                        @endphp
                        <li class="list-group-item p-1 d-flex justify-content-between">
                            <span>{{ $supply->name }} ({{ $supply->unit ?? '' }})</span>
                            <span class="badge {{ $remaining >= 2 ? 'bg-success' : 'bg-danger' }}">{{ $remaining }}</span>
                        </li>
                    @empty
                        <li class="list-group-item p-1 text-muted">Нет товаров</li>
                    @endforelse
                @endforeach

                @if($warehouses->isEmpty())
                    <li class="list-group-item p-1 text-muted">Нет данных о складах</li>
                @endif
            </ul>
        </div>
    </div>
</div>

{{-- 5) Последние приходы --}}
<div class="col-12 col-md-6 col-lg-3 mt-3">
    <div class="card p-3 shadow-sm h-100 border-secondary">
        <div class="d-flex align-items-center mb-2">
            <span class="fs-4 me-2">💰</span>
            <h5 class="mb-0">Поступление денег</h5>
        </div>
        <div style="max-height: 200px; overflow-y: auto;">
            <ul class="list-group list-group-flush">
                @forelse($latestIncomes as $income)
                    <li class="list-group-item p-1 d-flex justify-content-between">
                        <div>
                            <strong>{{ $income->clientRelation?->name ?? $income->client_name ?? '—' }}</strong><br>
                            <small>{{ $income->date->format('d.m.Y') ?? $income->date }}</small>
                        </div>
                        <span class="badge bg-success">{{ number_format($income->amount, 2) }} ₴</span>
                    </li>
                @empty
                    <li class="list-group-item p-1 text-muted">Нет доходов</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>










</div>
