@extends('layouts.app')

@section('header-title', __('Banner Video'))

@section('content')
    <div class="page-title">
        {{-- <div class="d-flex gap-2 align-items-center">
            <i class="fa-solid fa-image"></i> {{ __('Add New Banner') }}
        </div> --}}
    </div>
    <form action="{{ route('admin.video.save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-md-6">
                <div class="card mt-3 h-100">
                    <div class="card-body">
                        <div class="">
                            <x-input label="" name="id" type="hidden" :value="$video?->id" />
                            <x-input label="Title" name="title" type="text" placeholder="Enter Short Title"
                                :value="$video?->title" />
                            <x-input label="" name="videodisplay" type="hidden" :value="$video?->thumbnail" />

                        </div>
                        <div class="mt-4">
                            <x-input label="Product Link" name="product_link" type="url"
                                placeholder="Enter Product Link" :value="$video?->product_link" />
                        </div>
                        <div class="mt-4">
                            @if ($video?->thumbnail)
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <!-- ❌ Delete button -->
                                    <button type="button" id="removeVideoBtn"
                                        class="btn btn-danger btn-sm py-2 px-3 position-absolute top-0 end-0 m-2">
                                        {{ __('X') }}
                                    </button>
                                    <div class="ratio4x1 position-relative">


                                        <!-- 🎥 Video Preview -->
                                        <video id="videoPreview" width="100%" controls>
                                            <source src="{{ $video->thumbnail }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="ratio4x1">
                                        <video id="videoPreview" width="100%" controls>
                                            <source src="https://placehold.co/2000x500/f1f5f9/png" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                </div>
                            @endif


                            <x-file name="video" label="Video upload limit 50MB" preview="Video" required=""
                                accept="video/*" />
                        </div>

                        @if ($businessModel != 'single')
                            <div
                                class="mt-4 border d-inline-flex align-items-center justify-content-center gap-2 p-2 rounded">
                                <label for="forShop" class="form-label mb-0 fw-bold">
                                    {{ __('This Banner For Own Shop') }}
                                </label>
                                <input type="checkbox" name="for_shop" id="forShop" style="width: 20px; height: 20px"
                                    class="form-check-input m-0" />
                            </div>
                        @endif


                        <div class="col-12 d-flex justify-content-end mt-4">
                            <button class="btn btn-primary py-2 px-5">
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </form>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeBtn = document.getElementById('removeVideoBtn');
            const videoPreview = document.getElementById('videoPreview');
            const videoDisplayInput = document.querySelector('input[name="videodisplay"]');

            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove video preview
                    videoPreview.src = '';
                    videoPreview.load();

                    // Optional: visually hide the video
                    videoPreview.style.display = 'none';
                    removeBtn.style.display = 'none';

                    // Clear hidden input value so backend knows video is removed
                    if (videoDisplayInput) {
                        videoDisplayInput.value = '';
                    }
                });
            }
        });
    </script>
@endsection
