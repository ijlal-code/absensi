@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold">Informasi Karyawan</h2>
        </div>
        <div class="col-md-6">
            <form action="{{ route('employees.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama, NIK, atau Unit Kerja..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" width="10%">Aksi</th>
                            <th>NIK</th>
                            <th>NAMA PEGAWAI</th>
                            <th>TKT. JABATAN</th>
                            <th>UNIT KERJA</th>
                            <th>NO. HANDPHONE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        <tr>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#employeeModal{{ $emp->id }}">
                                    Detail
                                </button>
                            </td>
                            <td>{{ $emp->nik ?? '-' }}</td>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->tkt_jabatan ?? '-' }}</td>
                            <td>{{ $emp->unit_kerja ?? '-' }}</td>
                            <td>
                                {{ $emp->no_hp_1 ?? '-' }}
                            </td>
                        </tr>

                        <div class="modal fade" id="employeeModal{{ $emp->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $emp->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="modalLabel{{ $emp->id }}">Detail Profil: {{ $emp->name }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-4 text-center">
                                            <div class="col-md-6">
                                                <p class="fw-bold mb-2">Foto Sekarang</p>
                                                <div class="border p-2 rounded bg-light">
                                                    @if($emp->foto_sekarang)
                                                        <img src="{{ asset('storage/' . $emp->foto_sekarang) }}" class="img-fluid rounded" style="max-height: 200px;" alt="Foto Sekarang">
                                                    @else
                                                        <img src="https://via.placeholder.com/150?text=No+Image" class="img-fluid rounded" alt="Placeholder">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="fw-bold mb-2">Foto Dulu</p>
                                                <div class="border p-2 rounded bg-light">
                                                    @if($emp->foto_lama)
                                                        <img src="{{ asset('storage/' . $emp->foto_lama) }}" class="img-fluid rounded" style="max-height: 200px;" alt="Foto Lama">
                                                    @else
                                                        <img src="https://via.placeholder.com/150?text=No+Image" class="img-fluid rounded" alt="Placeholder">
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3 fw-bold">Data Pribadi & Kontak</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td width="30%" class="fw-bold">NIK</td>
                                                <td width="2%">:</td>
                                                <td>{{ $emp->nik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Nama Lengkap</td>
                                                <td>:</td>
                                                <td>{{ $emp->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Tingkat Jabatan</td>
                                                <td>:</td>
                                                <td>{{ $emp->tkt_jabatan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Unit Kerja</td>
                                                <td>:</td>
                                                <td>{{ $emp->unit_kerja ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email</td>
                                                <td>:</td>
                                                <td>{{ $emp->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">No. HP (Utama)</td>
                                                <td>:</td>
                                                <td>{{ $emp->no_hp_1 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">No. HP (Alternatif 1)</td>
                                                <td>:</td>
                                                <td>{{ $emp->no_hp_2 ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">No. HP (Alternatif 2)</td>
                                                <td>:</td>
                                                <td>{{ $emp->no_hp_3 ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada data karyawan ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</div>
@endsection