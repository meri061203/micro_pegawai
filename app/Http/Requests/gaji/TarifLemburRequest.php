<?php

namespace App\Http\Requests\gaji;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TarifLemburRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tarif_id'       => 'required|string|max:10',
            'jenis_lembur'   => 'required|string|max:50',
            'tarif_per_jam'  => 'required|numeric|min:0',
            'berlaku_mulai'  => 'required|date',

            ];
    }

    public function attributes(): array
    {
        return [
            'tarif_id'      => 'ID Tarif',
            'jenis_lembur'  => 'Jenis Lembur',
            'tarif_per_jam' => 'Tarif per Jam',
            'berlaku_mulai' => 'Berlaku Mulai',
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
            'tarif_id.required' => 'Field :attribute wajib diisi.',
            'tarif_id.string'   => 'Field :attribute harus berupa teks.',
            'tarif_id.max'      => 'Field :attribute maksimal :max karakter.',

            'jenis_lembur.required' => 'Field :attribute wajib diisi.',
            'jenis_lembur.string'   => 'Field :attribute harus berupa teks.',
            'jenis_lembur.max'      => 'Field :attribute maksimal :max karakter.',

            'tarif_per_jam.required' => 'Field :attribute wajib diisi.',
            'tarif_per_jam.numeric'  => 'Field :attribute harus berupa angka.',
            'tarif_per_jam.min'      => 'Field :attribute tidak boleh kurang dari 0.',

            'berlaku_mulai.required' => 'Field :attribute wajib diisi.',
            'berlaku_mulai.date'     => 'Field :attribute harus berupa tanggal.',
        ];
    }
}