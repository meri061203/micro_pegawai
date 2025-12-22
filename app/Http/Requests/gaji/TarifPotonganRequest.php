<?php

namespace App\Http\Requests\gaji;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TarifPotonganRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'potongan_id'        => 'required|string|max:10',
            'nama_potongan'      => 'required|string|max:50',
            'tarif_per_kejadian' => 'required|numeric|min:0',
            'deskripsi'          => 'nullable|string|max:255',

            ];
    }

    public function attributes(): array
    {
        return [
            'potongan_id'        => 'ID Potongan',
            'nama_potongan'      => 'Nama Potongan',
            'tarif_per_kejadian' => 'Tarif per Kejadian',
            'deskripsi'          => 'Deskripsi',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->messages(),
            ], 422)
        );
    }

    public function messages(): array
    {
        return [
            'potongan_id.required' => 'Field :attribute wajib diisi.',
            'potongan_id.string'   => 'Field :attribute harus berupa teks.',
            'potongan_id.max'      => 'Field :attribute maksimal :max karakter.',

            'nama_potongan.required' => 'Field :attribute wajib diisi.',
            'nama_potongan.string'   => 'Field :attribute harus berupa teks.',
            'nama_potongan.max'      => 'Field :attribute maksimal :max karakter.',

            'tarif_per_kejadian.required' => 'Field :attribute wajib diisi.',
            'tarif_per_kejadian.numeric'  => 'Field :attribute harus berupa angka.',
            'tarif_per_kejadian.min'      => 'Field :attribute tidak boleh kurang dari 0.',

            'deskripsi.string' => 'Field :attribute harus berupa teks.',
            'deskripsi.max'    => 'Field :attribute maksimal :max karakter.',
        ];
    }
}