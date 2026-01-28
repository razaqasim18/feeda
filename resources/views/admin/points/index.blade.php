@extends('layouts.app')
@section('header-title', __('Points Redeem List'))

@section('content')
    <div class="d-flex align-items-center flex-wrap gap-3 justify-content-between px-3">

        <h4>
            {{ __('Point Redeem List') }}
        </h4>

        <button type="button" data-bs-toggle="modal" data-bs-target="#createBrand" class="btn py-2 btn-primary">
            <i class="fa fa-plus-circle"></i>
            {{ __('Create New') }}
        </button>
    </div>

    <div class="container-fluid mt-3">

        <div class="mb-3 card">
            <div class="card-body">
                <div class="cardTitleBox">
                    @if (session('success'))
                    <div class="alert alert-success">
                          <p class="text text-success m-0">{{ session('success') }}</p>
                    </div>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table border-left-right table-responsive-md">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('SL') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Points') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        @forelse($points as $key => $point)
                            @php
                                $serial = $points->firstItem() + $key;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $serial }}</td>
                                <td>{{ $point->amount }}</td>
                                <td>{{ $point->points }}</td>
                                   
                                    <td class="text-center">
                                        <div class="d-flex gap-3 justify-content-center">
                                            <button type="button" class="btn btn-outline-primary btn-sm circleIcon"
                                                onclick="openUpdateModal({{ $point }})">
                                                <img src="{{ asset('assets/icons-admin/edit.svg') }}" alt="edit"
                                                    loading="lazy" />
                                            </button>

                                        </div>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="my-3">
            {{ $points->withQueryString()->links() }}
        </div>

    </div>


    <!--=== Create redeem Modal ===-->
    <form action="{{ route('admin.point.redeem.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="createBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Create Point Redeem') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="amount" class="form-label">{{ __('Amount') }} *</label>
                            <input type="text" class="form-control" id="amount" name="amount"
                                placeholder="Enter Amount" required />
                            @error('amount')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">{{ __('Points') }} *</label>
                            <input type="text" class="form-control" id="points" name="points"
                                placeholder="Enter Points" required />
                            @error('points')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--=== Edit Brand Modal ===-->
    <form action="" id="formEditBrand" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal fade" id="updateBrand" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ __('Edit Point Redeem') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align: left">

                        <div class="mb-3">
                            <label for="amount" class="form-label">{{ __('Amount') }} *</label>
                            <input type="text" class="form-control" id="editAmount" name="amount"
                                placeholder="Enter Amount" value="" required />
                            @error('amount')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Points') }} *</label>
                            <input type="text" class="form-control" id="editPoints" name="points"
                                placeholder="Enter Points" value="" required />
                            @error('points')
                                <p class="text text-danger m-0">{{ $message }}</p>
                            @enderror
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        const openUpdateModal = (point) => {
            $("#editAmount").val(point.amount);
            $("#editPoints").val(point.points);
            $("#formEditBrand").attr('action', `{{ route('admin.point.redeem.update', ':id') }}`.replace(':id', point.id));

            $("#updateBrand").modal('show');
        }
    </script>
@endpush
