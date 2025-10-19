@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
        <h1 class="mb-0">📦 Производство изделий</h1>

        <div class="d-flex">

            {{-- Бухгалтерия --}}
            <a href="{{ route('accounting.index') }}" class="ms-3 fs-4 text-decoration-none" title="Бухгалтерия">📘</a>
            
            {{-- Склад --}}
            <a href="{{ route('warehouses.index') }}" class="ms-3 fs-4 text-decoration-none" title="Склад">🏬</a>

           

            {{-- Клиенты --}}
            <a href="{{ route('clients.index') }}" class="ms-3 fs-4 text-decoration-none" title="Клиенты">👥</a>

            {{-- Поставщики --}}
            <a href="{{ route('suppliers.index') }}" class="ms-3 fs-4 text-decoration-none" title="Поставщики">🤝</a>

             {{-- Отчеты --}}
            <a href="{{ route('reports.index') }}" class="ms-3 fs-4 text-decoration-none" title="Отчеты">📊</a>

            {{-- Настройки --}}
            <a href="{{ route('constants.index') }}" class="ms-3 fs-4 text-decoration-none" title="Настройки">⚙️</a>

           
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('manufactured_products.create') }}" class="btn btn-success mb-3">Создать изделие</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Статус</th>
                <th>Артикул</th>
                <th>Название</th>
                <th>Категория</th>
                <th>Склад</th>
                <th>Себестоимость</th>
                <th>Компоненты</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>
                    @if($product->status === 'order')
                        <span class="badge bg-secondary">Заказ</span>
                    @elseif($product->status === 'produced')
                        <span class="badge bg-warning">Произведено</span>
                    @elseif($product->status === 'stocked')
                        <span class="badge bg-success">На складе</span>
                    @endif
                </td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? 'Без категории' }}</td>
                <td>{{ $product->warehouse->name ?? 'Без склада' }}</td>
               
                <td>
    @php
        $totalCost = 0;

        if ($product->status === 'order') {
            // Ещё не произведено — считаем себестоимость динамически по текущим остаткам
            foreach ($product->components as $component) {
                $requiredQty = $component->quantity;

                $supplies = \App\Models\Supply::where('sku', $component->supply->sku)
                    ->where('quantity_remaining', '>=', 0)
                    ->orderBy('date_received')
                    ->get();

                foreach ($supplies as $supply) {
                    if ($requiredQty <= 0) break;
                    $takeQty = min($requiredQty, $supply->quantity_remaining);
                    $totalCost += $takeQty * $supply->price_per_unit;
                    $requiredQty -= $takeQty;
                }
            }
        } else {
            // Уже произведено — берём зафиксированные данные из componentCosts
            $savedCosts = $product->componentCosts ?? collect();
            $totalCost = $savedCosts->sum('total_price');
        }
    @endphp

    <strong>{{ number_format($totalCost, 2, '.', ' ') }}</strong> грн
</td>
                
                
                <td>
                    @if($product->status === 'order')
                        {{-- Ещё не произведено: считаем динамически из остатков --}}
                        @foreach($product->components as $component)
                            @php
                                $requiredQty = $component->quantity;
                                $supplyDetails = [];

                                $supplies = \App\Models\Supply::where('sku', $component->supply->sku)
                                    ->where('quantity_remaining', '>=', 0)
                                    ->orderBy('date_received')
                                    ->get();

                                foreach ($supplies as $supply) {
                                    if ($requiredQty <= 0) break;

                                    $takeQty = min($requiredQty, $supply->quantity_remaining);

                                    $supplyDetails[] = [
                                        'name'  => $supply->name,
                                        'qty'   => $takeQty,
                                        'unit'  => $supply->unit,
                                        'price' => $supply->price_per_unit,
                                        'total' => $takeQty * $supply->price_per_unit
                                    ];

                                    $requiredQty -= $takeQty;
                                }
                            @endphp

                            <div>
                                <strong>{{ $component->supply->name ?? 'Нет сырья' }}</strong>
                                <small>(нужно: {{ $component->quantity }} {{ $component->supply->unit ?? '' }})</small>
                                <br>
                                @foreach($supplyDetails as $detail)
                                    <span>{{ $detail['qty'] }} {{ $detail['unit'] }} × {{ number_format($detail['price'], 2) }} = {{ number_format($detail['total'], 2) }}</span>
                                    @if(!$loop->last)+@endif
                                @endforeach
                            </div>
                        @endforeach

                    @else
                        {{-- Уже произведено: показываем зафиксированные данные из manufactured_product_component_costs --}}
                        @php
                            $savedCosts = $product->componentCosts ?? collect(); // если null, используем пустую коллекцию
                        @endphp

                        @forelse($savedCosts as $cost)
                            <div>
                                <strong>{{ $cost->component_name }}</strong>
                                <small>({{ $cost->quantity }} {{ $cost->unit }})</small>
                                <br>
                                <span>{{ number_format($cost->unit_price, 2) }} × {{ $cost->quantity }} = 
                                    <span class="text-success">{{ number_format($cost->total_price, 2) }}</span>
                                </span>
                            </div>
                        @empty
                            <span class="text-muted">Нет зафиксированных данных о компонентах и себестоимости.</span>
                        @endforelse
                    @endif
                </td>



                <td>
                    <a href="{{ route('manufactured_products.edit', $product) }}" class="btn btn-primary btn-sm mb-1">Редактировать</a>

                    @php
                        // Проверяем, хватает ли всех компонентов
                        $allComponentsAvailable = true;
                    @endphp

                    @if($product->status === 'order')
                        @php
                            $allComponentsAvailable = true;
                            $missingComponents = [];
                        @endphp

                        @foreach($product->components as $component)
                            @php
                                $stock = \App\Models\Supply::getTotalRemainingBySku($component->supply->sku, $component->supply->warehouse_id);
                                if ($stock < $component->quantity) {
                                    $allComponentsAvailable = false;
                                    $missingComponents[] = ($component->supply->name ?? 'Нет сырья') . 
                                        ' — нужно: ' . number_format($component->quantity, 2, '.', ' ') . 
                                        ', на складе: ' . number_format($stock, 2, '.', ' ');
                                }
                            @endphp
                        @endforeach

                        <form action="{{ route('manufactured_products.produce', $product) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('PATCH')

                            <button 
                                class="btn btn-warning btn-sm mb-1 {{ !$allComponentsAvailable ? 'disabled text-secondary bg-light border-0' : '' }}" 
                                {{ !$allComponentsAvailable ? 'disabled title=' . '"' . implode(' | ', $missingComponents) . '"' : '' }}
                            >
                                Произвести
                            </button>
                        </form>
                    @endif

                   
                    @if($product->status === 'produced')
                        <form action="{{ route('manufactured_products.stock', $product) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('PATCH')

                             <input type="hidden" name="produced_quantity" value="1">

                            <button type="submit" class="btn btn-success btn-sm">
                                На склад
                            </button>
                        </form>
                    @endif


                    <form action="{{ route('manufactured_products.destroy', $product) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm mb-1" onclick="return confirm('Удалить изделие?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
