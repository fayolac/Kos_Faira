<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Rumah Kos Faira</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <div class="auth-card auth-card--wide">

        <div class="text-center mb-4">
            <div class="auth-title">Register - Rumah Kos Faira</div>
        </div>

        @if(session('error'))
            <div class="alert-error-custom">{{ session('error') }}</div>
        @endif

        <form action="/register" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Nama Email-->
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">
                        Nama lengkap <span class="required-star">*</span>
                    </label>
                    <input type="text" name="nama"
                           class="form-control @error('nama') is-invalid @enderror"
                           placeholder="Masukkan nama"
                           value="{{ old('nama') }}">
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label">
                        Email <span class="required-star">*</span>
                    </label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Masukkan email"
                           value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Password & No. Telp-->
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">
                        Password <span class="required-star">*</span>
                    </label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Min 8 karakter">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-6">
                    <label class="form-label">
                        No. telepon <span class="required-star">*</span>
                    </label>
                    <input type="text" name="no_telp"
                           class="form-control @error('no_telp') is-invalid @enderror"
                           placeholder="08XX-XXXX-XXXX"
                           value="{{ old('no_telp') }}">
                    @error('no_telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Pekerjaan Agama-->
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">
                        Pekerjaan <span class="required-star">*</span>
                    </label>
                    <select name="pekerjaan"
                            class="form-select @error('pekerjaan') is-invalid @enderror">
                        <option value="" disabled selected>Pilih pekerjaan ...</option>
                        <option value="Mahasiswa"  {{ old('pekerjaan') == 'Mahasiswa'  ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="Karyawan"   {{ old('pekerjaan') == 'Karyawan'   ? 'selected' : '' }}>Karyawan</option>
                        <option value="Wirausaha"  {{ old('pekerjaan') == 'Wirausaha'  ? 'selected' : '' }}>Wirausaha</option>
                        <option value="Lainnya"    {{ old('pekerjaan') == 'Lainnya'    ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6">
                    <label class="form-label">
                        Agama <span class="required-star">*</span>
                    </label>
                    <select name="agama"
                            class="form-select @error('agama') is-invalid @enderror">
                        <option value="" disabled selected>Pilih agama ...</option>
                        <option value="Islam"     {{ old('agama') == 'Islam'     ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen"   {{ old('agama') == 'Kristen'   ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik"   {{ old('agama') == 'Katolik'   ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu"     {{ old('agama') == 'Hindu'     ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha"    {{ old('agama') == 'Buddha'    ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu"  {{ old('agama') == 'Konghucu'  ? 'selected' : '' }}>Konghucu</option>
                    </select>
                    @error('agama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Foto KTP-->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label mb-0">
                        Foto KTP <span class="required-star">*</span>
                    </label>
                    <span class="file-format-note">JPG / PNG - maks. 2MB</span>
                </div>

                <div class="upload-area @error('foto_ktp') border-danger @enderror">
                    <div id="placeholder-ktp">
                        <div class="upload-text">klik atau drag foto KTP ke sini</div>
                    </div>
                    <img id="preview-ktp"
                        src=""
                        alt="Preview KTP"
                        style="max-width:100%; max-height:120px; border-radius:6px; display:none;">
                    <input type="file"
                        name="foto_ktp"
                        accept="image/jpg,image/jpeg,image/png"
                        onchange="previewFoto(this, 'preview-ktp', 'placeholder-ktp')">
                </div>

                <div class="upload-note">
                    Foto KTP digunakan untuk verifikasi identitas penyewa oleh pemilik kos.
                </div>

                @error('foto_ktp')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-orange-full">Daftar</button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="/login">Login</a>
        </div>

    </div>

<script src="{{ asset('js/global.js') }}"></script>
</body>
</html>