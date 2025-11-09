<?php

namespace App\Http\Requests\Sdm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonSdmStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_person' => 'required|integer|exists:person,id_person',
            'nip' => 'nullable|string|max:20',
            'status_pegawai' => 'nullable|in:TETEP,KONTRAK',
            'tipe_pegawai' => 'nullable|in:FULL TIME,PART TIME',
            'tanggal_masuk' => 'nullable|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'id_person' => 'ID Person',
            'nip' => 'NIP',
            'status_pegawai' => 'Status Pegawai',
            'tipe_pegawai' => 'Tipe Pegawai',
            'tanggal_masuk' => 'Tanggal Masuk',
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
            'id_person.required' => 'Field :attribute wajib diisi.',
            'id_person.integer' => 'Field :attribute harus berupa angka.',
            'id_person.exists' => 'Field :attribute tidak ditemukan.',
            'nip.string' => 'Field :attribute harus berupa teks.',
            'nip.max' => 'Field :attribute maksimal :max karakter.',
            'status_pegawai.in' => 'Field :attribute harus TETEP atau KONTRAK.',
            'tipe_pegawai.in' => 'Field :attribute harus FULL TIME atau PART TIME.',
            'tanggal_masuk.date' => 'Field :attribute harus berupa tanggal yang valid.',
        ];
    }
}
