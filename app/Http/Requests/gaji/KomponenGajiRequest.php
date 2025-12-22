<?php

namespace App\Http\Requests\gaji;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class KomponenGajiRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
        'is_umum' => $this->has('is_umum') ? 1 : 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'komponen_id'   => 'required|string|max:10',
            'umum_id'       => 'nullable|string|max:10',
            'nama_komponen' => 'required|string|max:100',
            'jenis'         => 'required|string|max:50',
            'deskripsi'     => 'nullable|string',
            'is_umum'       => 'nullable|boolean',

            ];
    }

    public function attributes(): array
    {
        return [
            'komponen_id'   => 'ID Komponen',
            'umum_id'       => 'ID Umum',
            'nama_komponen' => 'Nama Komponen',
            'jenis'         => 'Jenis',
            'deskripsi'     => 'Deskripsi',
            'is_umum'       => 'Status Umum',
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
            'komponen_id.required'   => 'Field :attribute wajib diisi.',
            'komponen_id.string'     => 'Field :attribute harus berupa teks.',
            'komponen_id.max'        => 'Field :attribute maksimal :max karakter.',

            'umum_id.string'         => 'Field :attribute harus berupa teks.',
            'umum_id.max'            => 'Field :attribute maksimal :max karakter.',

            'nama_komponen.required' => 'Field :attribute wajib diisi.',
            'nama_komponen.string'   => 'Field :attribute harus berupa teks.',
            'nama_komponen.max'      => 'Field :attribute maksimal :max karakter.',

            'jenis.required'         => 'Field :attribute wajib diisi.',
            'jenis.string'           => 'Field :attribute harus berupa teks.',
            'jenis.max'              => 'Field :attribute maksimal :max karakter.',

            'deskripsi.string'       => 'Field :attribute harus berupa teks.',
            'deskripsi.max'          => 'Field :attribute maksimal :max karakter.',

            'is_umum.required'       => 'Field :attribute wajib diisi.',
            'is_umum.boolean'        => 'Field :attribute harus bernilai true / false.',
        ];
    }
}