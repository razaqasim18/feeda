@extends('layouts.app')

@section('title', __('Admin Settings'))

@section('content')
    <div class="page-title">
        <div class="d-flex gap-2 align-items-center">
            <i class="bi bi-gear-fill"></i> {{ __('Bussiness Coupon Settings') }}
        </div>
    </div>
    <form action="{{ route('admin.birthday-coupon-setting.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mt-3">
            <div class="card-body">

                <div class="row">
                    <div class="col-12 col-md-6 mb-3">
                        <div class="mt-4">
                            <x-input type="number" label="Birthday Coupon Discount" name="discount"
                                placeholder="Enter Birthday Coupon Discount" :value="$setting?->discount" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="mt-4">
                            <x-input label="Birthday Coupon Day" name="day" type="number"
                                placeholder="Enter Birthday Coupon Day" :value="$setting?->day" />
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <x-select name="discount_type" label="Discount Type" required="true">
                            @foreach ($discountTypes as $type)
                                <option value="{{ $type->value }}" @if ($setting?->type == $type->value) selected @endif>
                                    {{ __($type->value) }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                </div>
            </div>
        </div>


        @hasPermission('admin.generale-setting.update')
            <div class="d-flex justify-content-end mt-4 mb-3">
                <button type="submit" class="btn btn-primary py-2 px-3">
                    {{ __('Save And Update') }}
                </button>
            </div>
        @endhasPermission

    </form>
@endsection
