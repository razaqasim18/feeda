<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'nullable';

        return [
            'title' => ['nullable', 'string', 'max:255'],
            'video' => ["nullable", 'mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime,video/x-matroska', 'max:51200'], // 50MB
        ];
    }

    public function messages(): array
    {
        $request = request();
        if ($request->is('api/*')) {
            $lan = $request->header('accept-language') ?? 'en';
            app()->setLocale($lan);
        }

        return [
            'video.required' => __('The video field is required.'),
            'video.mimetypes' => __('The video must be a file of type: mp4, avi, mpeg, mov, mkv.'),
            'video.max' => __('The video may not be greater than 50MB.'),
        ];
    }
}
