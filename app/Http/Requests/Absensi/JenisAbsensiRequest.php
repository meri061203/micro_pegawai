<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class JenisAbsensiRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_absensi_id' => 'required|string|max:10',
            'nama' => 'required|string|max:100',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_absensi_id' => 'ID Jenis Absensi',
            'nama' => 'Nama',
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
            'jenis_absensi_id.required' => 'Field :attribute wajib diisi.',
            'jenis_absensi_id.string' => 'Field :attribute harus berupa teks.',

            'nama.required' => 'Field :attribute wajib diisi.',
            'nama.string'   => 'Field :attribute harus berupa teks.',
            'nama.max'      => 'Field :attribute maksimal :max karakter.',
        ];
    }
}