<?php

namespace App\Http\Requests\Products;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        if($this->isMethod('post')) {
            $thumbnailRequired = 'required';
        }else{
            $thumbnailRequired = 'nullable';
        }

        return [
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $this->route('product')],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug,' . $this->route('product')],
            'price' => ['required', 'numeric'],
            'discount' => ['required', 'numeric'],
            'thumbnail' => [ $thumbnailRequired, 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'status' => ['required', 'string', 'in:publish,unpublish'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}
