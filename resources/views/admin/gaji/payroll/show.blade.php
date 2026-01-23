@extends('admin.layouts.index')

@section('css')
    <style>
        .payslip-container {
            border: 1px solid #e4e6ef;
            padding: 30px;
            background: #fff;
            box-shadow: 0 0.5rem 1.5rem 0.5rem rgba(0, 0, 0, 0.075);
            max-width: 900px; /* A4 width approx */
            margin: 0 auto;
        }
        .payslip-header {
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .payslip-title {
            text-align: center;
            text-transform: uppercase;
            font-weight: 800;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        .payslip-subtitle {
            text-align: center;
            font-size: 1.1rem;
            color: #555;
        }
        .info-table td {
            padding: 5px 10px;
        }
        .nominal-table th {
            background-color: #f5f8fa;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            color: #5e6278;
        }
        .nominal-table td, .nominal-table th {
            padding: 10px 15px;
            border-bottom: 1px dashed #eff2f5;
        }
        .total-row {
            background-color: #f1faff !important;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .net-pay {
            background-color: #e8fff3 !important;
            color: #1e1e2d;
            font-weight: 800;
            font-size: 1.25rem;
            border-top: 2px solid #1e1e2d;
        }
        @media print {
            .no-print { display: none; }
            .content { padding: 0; margin: 0; }
            .payslip-container { box-shadow: none; border: none; }
        }
    </style>
@endsection

@section('list')
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.gaji.payroll.index') }}" class="text-muted text-hover-primary">Payroll</a>
    </li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-200 w-5px h-2px"></span>
    </li>
    <li class="breadcrumb-item text-dark">Slip Gaji</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-end mb-5 no-print">
            <button onclick="window.print()" class="btn btn-info btn-sm me-2">
                <i class="bi bi-printer me-2"></i>Cetak
            </button>
            <a href="{{ route('admin.gaji.payroll.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
        </div>

        <div class="payslip-container">
            <div class="payslip-header">
                <div class="row align-items-center">
                    <div class="col-2 text-center">
                        {{-- <img src="{{ asset('assets/media/logos/logo.png') }}" alt="Logo" style="max-height: 80px;"> --}}
                        <!-- Placeholder Logo -->
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; margin: 0 auto; border: 1px solid #ddd;">
                            <i class="bi bi-building fs-1 text-muted"></i>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="payslip-title">SLIP GAJI PEGAWAI</div>
                        <div class="payslip-subtitle">Universitas Nurul Jadid</div>
                        <div class="text-center text-muted small">Paiton Probolinggo Jawa Timur</div>
                    </div>
                    <div class="col-2 text-end">
                       <h3 class="fw-bolder text-gray-400">#{{ substr($trx->transaksi_id, -6) }}</h3>
                    </div>
                </div>
            </div>

            <div class="row mb-10">
                <div class="col-md-6">
                    <table class="info-table">
                        <tr>
                            <td class="fw-bold text-muted w-100px">NAMA</td>
                            <td>: <span class="fw-bolder text-dark">{{ $trx->sdm->person->nama_lengkap ?? '-' }}</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">NIK/NIP</td>
                            <td>: {{ $trx->sdm->nip ?? $trx->sdm->person->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">PERIODE</td>
                            <td>: <span class="badge badge-light-primary fw-bolder fs-7">{{ \Carbon\Carbon::parse($trx->gaji_periode->tanggal_mulai ?? now())->isoFormat('MMMM Y') }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="info-table text-md-end ms-auto">
                        <tr>
                            <td class="fw-bold text-muted">UNIT KERJA</td>
                            <td>: {{ $trx->sdm->sdm_struktural->master_unit->unit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">JABATAN</td>
                            <td>: {{ $trx->sdm->sdm_struktural->master_jabatan->jabatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">TANGGAL CETAK</td>
                            <td>: {{ now()->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Details -->
            <div class="row">
                <!-- PENGHASILAN -->
                <div class="col-md-6 mb-5">
                    <div class="border rounded p-0">
                        <div class="bg-light p-3 rounded-top border-bottom">
                            <h4 class="card-title fw-bolder m-0 text-success">PENGHASILAN</h4>
                        </div>
                        <table class="table nominal-table mb-0">
                            <tbody>
                                @php $totalPenghasilan = 0; @endphp
                                @foreach($trx->details as $detail)
                                    @php 
                                        $jenis = strtoupper($detail->komponen->jenis ?? '');
                                        $incomeKeywords = ['PENERIMAAN', 'PENGHASIL', 'PENDAPATAN', 'GAJI', 'TUNJANGAN', 'HONOR', 'INSENTIF', 'BONUS'];
                                        $isIncome = false;
                                        foreach ($incomeKeywords as $kw) {
                                            if (str_contains($jenis, $kw)) {
                                                $isIncome = true;
                                                break;
                                            }
                                        }
                                        // Fallback default to income if logic is ambiguous, unless it's explicitly Potongan? 
                                        // For safety let's assume strict check first.
                                    @endphp
                                    @if($isIncome)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ $detail->komponen->nama_komponen ?? 'Komponen' }}</span>
                                                    @if($detail->keterangan && $detail->keterangan != 'Auto - '.$detail->komponen->nama_komponen)
                                                        <span class="text-muted small fst-italic">{{ $detail->keterangan }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-dark">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                        @php $totalPenghasilan += $detail->nominal; @endphp
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td class="text-end">TOTAL PENGHASILAN</td>
                                    <td class="text-end text-success">Rp {{ number_format($totalPenghasilan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- POTONGAN -->
                <div class="col-md-6 mb-5">
                    <div class="border rounded p-0">
                        <div class="bg-light p-3 rounded-top border-bottom">
                            <h4 class="card-title fw-bolder m-0 text-danger">POTONGAN</h4>
                        </div>
                        <table class="table nominal-table mb-0">
                            <tbody>
                                @php $totalPotongan = 0; @endphp
                                @foreach($trx->details as $detail)
                                    @php 
                                        $jenis = strtoupper($detail->komponen->jenis ?? '');
                                        $incomeKeywords = ['PENERIMAAN', 'PENGHASIL', 'PENDAPATAN', 'GAJI', 'TUNJANGAN', 'HONOR', 'INSENTIF', 'BONUS'];
                                        $isIncomeCheck = false;
                                        foreach ($incomeKeywords as $kw) {
                                            if (str_contains($jenis, $kw)) {
                                                $isIncomeCheck = true;
                                                break;
                                            }
                                        }

                                        $isPotongan = !$isIncomeCheck;
                                        
                                        // Explicit deduction check to be sure
                                        $deductionKeywords = ['POTONGAN', 'IURAN', 'DENDA', 'CICILAN', 'ANGSURAN', 'PINJAMAN', 'KURANG'];
                                        foreach ($deductionKeywords as $kw) {
                                            if (str_contains($jenis, $kw)) {
                                                $isPotongan = true;
                                                break;
                                            }
                                        }
                                    @endphp

                                    @if($isPotongan)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">{{ $detail->komponen->nama_komponen ?? 'Komponen' }}</span>
                                                     @if($detail->keterangan && $detail->keterangan != 'Auto - '.$detail->komponen->nama_komponen)
                                                        <span class="text-muted small fst-italic">{{ $detail->keterangan }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-dark">Rp {{ number_format($detail->nominal, 0, ',', '.') }}</td>
                                        </tr>
                                        @php $totalPotongan += $detail->nominal; @endphp
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td class="text-end">TOTAL POTONGAN</td>
                                    <td class="text-end text-danger">Rp {{ number_format($totalPotongan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="separator separator-dashed my-5"></div>

            <div class="row">
                <div class="col-md-12">
                     <div class="d-flex justify-content-between align-items-center p-5 rounded ms-auto net-pay w-100">
                        <span>TOTAL DITERIMA (Take Home Pay)</span>
                        <span>Rp {{ number_format($trx->total_dibayar ?? ($totalPenghasilan - $totalPotongan), 0, ',', '.') }}</span>
                    </div>
                    <div class="text-end mt-2">
                        <span class="text-muted small fst-italic">Terbilang: # {{ ucwords(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format($trx->total_dibayar ?? ($totalPenghasilan - $totalPotongan))) }} Rupiah #</span>
                    </div>
                </div>
            </div>

            <br><br>
            <div class="row mt-10">
                <div class="col-4 text-center">
                    <p class="mb-10">Penerima,</p>
                    <br><br>
                    <p class="fw-bold text-decoration-underline">{{ $trx->sdm->person->nama_lengkap ?? '..........................' }}</p>
                </div>
                <div class="col-4 offset-4 text-center">
                    <p class="mb-10">Bagian Keuangan,</p>
                    <br><br>
                    <p class="fw-bold text-decoration-underline">Admin Keuangan</p>
                </div>
            </div>
        </div>
    </div>
@endsection
