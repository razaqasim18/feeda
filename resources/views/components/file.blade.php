<div>

    @if (isset($label))
        <label for="{{ $name }}" class="form-label">
            {{ __($label) }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <input type="file" name="{{ $name }}" id="{{ $name }}"
        class="form-control @error($name) is-invalid
    @enderror"
        @if ($preview) @if ($accept) onchange="previewVideoFile(event,'{{ $name }}')" @else onchange="previewFile(event,'{{ $preview }}')" @endif
        @endif
    @if ($required) required @endif
    @if ($accept) accept="{{ $accept }}" @endif />
    @error($name)
        <p class="text text-danger m-0">{{ $message }}</p>
    @enderror
</div>
