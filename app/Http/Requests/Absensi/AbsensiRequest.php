<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Foundation\Http\FormRequest;

class AbsensiRequest extends FormRequest
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
        return [
            'absensi_id'        => 'required|string|max:20',
            'id_sdm'            => 'required|exists:mysql.sdm,id',
            'id_jenis_absensi'  => 'required|integer|exists:att.jenis_absensi,id',
            'tanggal'           => 'required|date',
            'keterangan'        => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom attribute names
     */
    public function attributes(): array
    {
        return [
            'absensi_id'       => 'Absensi ID',
            'id_sdm'           => 'Sdm',
            'id_jenis_absensi' => 'Jenis Absensi',
            'tanggal'          => 'Tanggal',
            'keterangan'       => 'Keterangan',
        ];
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'absensi_id.required'        => 'Absensi ID harus diisi.',
            'absensi_id.string'          => 'Absensi ID harus berupa string.',
            'absensi_id.max'             => 'Absensi ID maksimal 20 karakter.',

            'id_sdm.required'            => 'Sdm harus dipilih.',
            'id_sdm.exists'              => 'Sdm tidak ditemukan di database.',

            'id_jenis_absensi.required'  => 'Jenis absensi harus dipilih.',
            'id_jenis_absensi.exists'    => 'Jenis absensi tidak ditemukan di database.',

            'tanggal.required'           => 'Tanggal absensi harus diisi.',
            'tanggal.date'               => 'Tanggal absensi harus berupa format tanggal yang valid.',

            'keterangan.string'          => 'Keterangan harus berupa teks.',
            'keterangan.max'             => 'Keterangan maksimal 255 karakter.',
        ];
    }
}
