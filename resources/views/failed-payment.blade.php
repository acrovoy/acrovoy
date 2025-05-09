@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow text-center">
                <div class="card-body">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="red" class="bi bi-x-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                    <h1 class="h3 text-danger mb-3">
                        {{ __('payment_failed.payment_failed') }}
                    </h1>
                    <p class="text-muted mb-2">
                        {{ __('payment_failed.something_went_wrong') }}
                    </p>
                    <p class="text-secondary mb-4">
                        {{ __('payment_failed.please_try_again') }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            {{ __('payment_failed.back_to_dashboard') }}
                        </a>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
