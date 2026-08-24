<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $product = $this->route('product');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:150'],
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($product),
            ],
            'description' => ['nullable', 'string', 'max:3000'],
            'price' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori produk yang dipilih tidak ditemukan.',
            'name.required' => 'Nama produk wajib diisi.',
            'name.string' => 'Nama produk harus berupa teks.',
            'name.max' => 'Nama produk maksimal 150 karakter.',
            'sku.required' => 'Kode SKU produk wajib diisi.',
            'sku.string' => 'Kode SKU harus berupa teks.',
            'sku.max' => 'Kode SKU maksimal 50 karakter.',
            'sku.unique' => 'Kode SKU tersebut sudah digunakan oleh produk lain.',
            'description.string' => 'Deskripsi produk harus berupa teks.',
            'description.max' => 'Deskripsi produk maksimal 3000 karakter.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.integer' => 'Harga produk harus berupa bilangan Rupiah tanpa desimal.',
            'price.min' => 'Harga produk tidak boleh kurang dari 1 Rupiah.',
            'stock.required' => 'Stok produk wajib diisi.',
            'stock.integer' => 'Stok produk harus berupa angka bulat.',
            'stock.min' => 'Stok produk tidak boleh bernilai negatif.',
            'thumbnail.image' => 'Thumbnail harus berupa file gambar.',
            'thumbnail.mimes' => 'Format gambar yang diperbolehkan adalah JPG, JPEG, PNG, atau WEBP.',
            'thumbnail.max' => 'Ukuran gambar maksimal 2 MB.',
            'status.required' => 'Status produk wajib dipilih.',
            'status.in' => 'Status produk yang dipilih tidak valid.',
        ];
    }
}
