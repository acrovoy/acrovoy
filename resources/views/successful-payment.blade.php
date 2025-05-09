@extends('layouts.app') <!-- или ваш основной шаблон -->

@section('title', `{{ __('successfull_payment.payment_success') }}`)

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-success text-white">
                        <h3>{{ __('successfull_payment.payment_successful') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">{{ __('successfull_payment.congratulations') }}</h4>
                            <p>{{ __('successfull_payment.yourpayment') }}</p>
                            <hr>
                            <p class="mb-0">{{ __('successfull_payment.contact_us') }}</p>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-success">{{ __('successfull_payment.gotoda') }}</a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
