<?php

namespace App\Http\Requests\Absensi;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class JadwalKerjaRequest extends FormRequest
{
   public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jadwal_kerja_id'  => 'required|string|max:10',
            'hari'             => 'required|string|max:20',
            'jam_masuk'        => 'required|date_format:H:i',
            'jam_pulang'       => 'required|date_format:H:i|after:jam_masuk',
            'toleransi_menit'  => 'required|integer|min:0|max:180',
        ];
    }

    public function attributes(): array
    {
        return [
            'jadwal_kerja_id' => 'ID Jadwal Kerja',
            'hari'            => 'Hari',
            'jam_masuk'       => 'Jam Masuk',
            'jam_pulang'      => 'Jam Pulang',
            'toleransi_menit' => 'Toleransi (Menit)',
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
            'jadwal_kerja_id.required' => 'Field :attribute wajib diisi.',
            'jadwal_kerja_id.string'   => 'Field :attribute harus berupa teks.',
            'jadwal_kerja_id.max'      => 'Field :attribute maksimal :max karakter.',

            'hari.required' => 'Field :attribute wajib diisi.',
            'hari.string'   => 'Field :attribute harus berupa teks.',
            'hari.max'      => 'Field :attribute maksimal :max karakter.',

            'jam_masuk.required'    => 'Field :attribute wajib diisi.',
            'jam_masuk.date_format' => 'Field :attribute harus berformat HH:MM.',

            'jam_pulang.required'    => 'Field :attribute wajib diisi.',
            'jam_pulang.date_format' => 'Field :attribute harus berformat HH:MM.',
            'jam_pulang.after'       => 'Field :attribute harus setelah Jam Masuk.',

            'toleransi_menit.required' => 'Field :attribute wajib diisi.',
            'toleransi_menit.integer'  => 'Field :attribute harus berupa angka.',
            'toleransi_menit.min'      => 'Field :attribute minimal :min menit.',
            'toleransi_menit.max'      => 'Field :attribute maksimal :max menit.',
        ];
    }
}