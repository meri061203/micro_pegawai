<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LiburNasionalRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'libur_nasional_id' => 'required|string|max:10',
            'nama_libur_nasional' => 'required|string|max:100',
            'tanggal' => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'libur_nasional_id' => 'ID Libur Nasional',
            'nama_libur_nasional' => 'Nama Libur Nasional',
            'tanggal' => 'Tanggal Libur',
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
            'libur_nasional_id.required' => 'Field :attribute wajib diisi.',
            'libur_nasional_id.string' => 'Field :attribute harus berupa teks.',

            'nama_libur_nasional.required' => 'Field :attribute wajib diisi.',
            'nama_libur_nasional.string'   => 'Field :attribute harus berupa teks.',
            'nama_libur_nasional.max'      => 'Field :attribute maksimal :max karakter.',

             'tanggal.required' => 'Field :attribute wajib diisi.',
             'tanggal.date'     => 'Field :attribute harus berupa tanggal yang valid.',
        ];
    }
}