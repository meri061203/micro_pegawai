<?php

namespace App\Http\Requests\gaji;

use Illuminate\Foundation\Http\FormRequest;

class GajiJabatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gaji_master_id' => ['required', 'string', 'max:10'],
            'komponen_id'    => ['required', 'string', 'max:10'],
            'id_jabatan'     => ['required', 'integer'],
            'nominal'        => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'gaji_master_id' => 'Gaji Master',
            'komponen_id'   => 'Komponen Gaji',
            'id_jabatan'    => 'Jabatan',
            'nominal'       => 'Nominal',
        ];
    }

    public function messages(): array
    {
        return [
            'gaji_master_id.required' => 'Gaji master wajib diisi.',
            'gaji_master_id.string'   => 'Gaji master harus berupa teks.',
            'gaji_master_id.max'      => 'Gaji master maksimal 10 karakter.',

            'komponen_id.required' => 'Komponen wajib dipilih.',
            'komponen_id.string'   => 'Komponen tidak valid.',
            'komponen_id.max'      => 'Kode komponen maksimal 10 karakter.',

            'id_jabatan.required' => 'Jabatan wajib dipilih.',
            'id_jabatan.integer'  => 'Jabatan tidak valid.',

            'nominal.required' => 'Nominal wajib diisi.',
            'nominal.numeric'  => 'Nominal harus berupa angka.',
            'nominal.min'      => 'Nominal tidak boleh minus.',
        ];
    }
}
