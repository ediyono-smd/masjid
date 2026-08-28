@extends('layouts.public')

@section('title', 'Pendaftaran Masjid Baru — MASJID INDONESIA')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center mx-auto shadow-md">
                <i data-lucide="landmark" class="w-6 h-6 text-gold-400"></i>
            </div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Registrasi Masjid Baru</h1>
            <p class="text-xs text-slate-500">Mulai digitalisasi tata kelola, transparansi keuangan kas, dan pelayanan jamaah.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-700 text-xs p-3 rounded-xl border border-red-200 space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Masjid -->
            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-emerald-800 border-b border-slate-100 pb-2">1. Data Pokok Masjid</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Masjid Resmi *</label>
                        <input type="text" name="mosque_name" value="{{ old('mosque_name') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Contoh: Masjid Jami' Nurul Iman">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tipologi / Kategori Masjid *</label>
                        <select name="mosque_type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700">
                            <option value="JAMI">Masjid Jami' (Kelurahan/Desa)</option>
                            <option value="BESAR">Masjid Besar (Kecamatan)</option>
                            <option value="AGUNG">Masjid Agung (Kab/Kota)</option>
                            <option value="RAYA">Masjid Raya (Provinsi)</option>
                            <option value="MUSHOLLA">Musholla / Langgar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Provinsi *</label>
                        <input type="text" name="province" value="{{ old('province') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Jawa Barat">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kabupaten / Kota *</label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Kota Bandung">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kecamatan *</label>
                        <input type="text" name="district" value="{{ old('district') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Gedebage">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kelurahan / Desa *</label>
                        <input type="text" name="village" value="{{ old('village') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Cimenerang">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Jalan Lengkap *</label>
                        <textarea name="address_line" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Jl. Raya No. 123..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Akun Admin Takmir -->
            <div class="space-y-4">
                <h3 class="font-heading font-bold text-sm text-emerald-800 border-b border-slate-100 pb-2">2. Akun Administrator Masjid</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Pengurus *</label>
                        <input type="text" name="admin_name" value="{{ old('admin_name') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Ustadz Abdullah, S.Pd.I">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email Login *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="takmir@masjidnuruliman.id">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp Pengurus *</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="081234567890">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Akun *</label>
                        <input type="password" name="password" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="••••••••">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi *</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3.5 rounded-2xl text-xs transition shadow-md">
                Daftarkan Masjid Sekarang
            </button>
        </form>

        <div class="text-center pt-2 text-xs text-slate-500">
            Sudah memiliki akun pengurus?
            <a href="{{ route('login') }}" class="text-emerald-700 font-bold hover:underline">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
