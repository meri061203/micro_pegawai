<?php

namespace App\Http\Requests\Sdm;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SdmDokumenUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_dokumen' => 'required|string|max:255',
            'nama_file' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png',
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_dokumen' => 'Jenjang Dokumen',
            'nama_file' => 'Nama Dokumen',
            'file' => 'File Dokumen',
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
            'jenis_dokumen.required' => 'Jenis Dokumen harus berupa angka.',
            'jenis_dokumen.string' => 'Jenis Dokumen tidak ditemukan.',
            'jenis_dokumen.max' => 'Jenis Dokumen tidak boleh lebih dari :max karakter.',


            'nama_file.required' => 'Nama file wajib diisi.',
            'nama_file.string' => 'Nama file harus berupa teks.',
            'nama_file.max' => 'Nama file tidak boleh lebih dari :max karakter.',

            'file.file' => 'File harus berupa file.',
            'file.max' => 'File maksimal 10MB.',
            'file.mimes' => 'File harus bertipe pdf, jpg, jpeg, atau png.',
        ];
    }
}