@extends('layouts.app')
@section('header-title', __('Birthday Coupon Codes'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4> {{ __('Birthday Coupon Codes') }} </h4>

        {{-- @hasPermission('admin.coupon.create')
            <a href="{{ route('admin.coupon.create') }}" class="btn py-2 btn-primary">
                <i class="bi bi-patch-plus"></i>
                {{ __('Create New') }}
            </a>
        @endhasPermission --}}
    </div>

    <div class="mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card rounded-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-left-right">
                                <thead>
                                    <tr>
                                        <th>{{ __('Code') }}</th>
                                        <th>{{ __('Discount') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Started At') }}</th>
                                        <th>{{ __('Expired At') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($coupons as $coupon)
                                        <tr>
                                            <td>{{ $coupon->code }}</td>
                                            <td>
                                                {!! $coupon->type == 'Amount' ? showCurrency($coupon->discount) : $coupon->discount . '%' !!}
                                            </td>
                                            <td>
                                                {{ $coupon->user->fullName }}
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($coupon->started_at)->format('M d, Y h:i a') }}
                                            </td>
                                            <td>
                                                {{ Carbon\Carbon::parse($coupon->expired_at)->format('M d, Y h:i a') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{ $coupons->links() }}

            </div>
        </div>
    </div>
@endsection
