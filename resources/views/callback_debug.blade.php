@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h4>🔧 Отладка Callback-уведомления</h4>
        </div>
        <div class="card-body">
            <pre>{{ print_r($data, true) }}</pre>
        </div>
    </div>
</div>
@endsection
