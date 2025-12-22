<?php

namespace App\Http\Requests\gaji;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GajiPeriodeRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'periode_id' => 'required|string|max:10',
            'tahun' => 'required|numeric|digits:4|min:2000|max:2099',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
            'status'           => 'required|in:DRAFT,FINAL,CLOSED',

            ];
    }

    public function attributes(): array
    {
        return [
            'periode_id' => 'ID Periode',
            'tahun' => 'Tahun',
            'tanggal_mulai'   => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'status'          => 'Status',
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
            'periode_id.required' => 'Field :attribute wajib diisi.',
            'periode_id.string'   => 'Field :attribute harus berupa teks.',
            'periode_id.max'      => 'Field :attribute maksimal :max karakter.',

            'tahun.required' => 'Field :attribute wajib diisi.',
            'tahun.digits'   => 'Field :attribute harus 4 digit.',
            'tahun.integer'  => 'Field :attribute harus berupa angka.',
            'tahun.min'      => 'Field :attribute minimal :min.',
            'tahun.max'      => 'Field :attribute maksimal :max.',

            'tanggal_mulai.required' => 'Field :attribute wajib diisi.',
            'tanggal_mulai.date'     => 'Field :attribute harus berupa tanggal.',

            'tanggal_selesai.required' => 'Field :attribute wajib diisi.',
            'tanggal_selesai.date'     => 'Field :attribute harus berupa tanggal.',
            'tanggal_selesai.after_or_equal' =>
                'Field :attribute harus setelah atau sama dengan Tanggal Masuk.',

            'status.required' => 'Field :attribute wajib diisi.',
            'status.in'       => 'Field :attribute harus bernilai DRAFT,FINAL,CLOSED.',
        ];
    }
}