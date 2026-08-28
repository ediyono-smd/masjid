@extends('layouts.admin')

@section('title', 'Profil & Fasilitas — ' . $mosque->name)
@section('page_title', 'Profil & Fasilitas Masjid')
@section('page_subtitle', 'Kelola informasi legal, sejarah, kapasitas, data geografis, dan inventaris fasilitas.')

@section('content')
<div class="space-y-8" x-data="{ facilityModal: false }">
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <h3 class="font-heading font-bold text-lg text-slate-900 border-b border-slate-100 pb-3">Informasi Umum Masjid</h3>

        <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Masjid *</label>
                    <input type="text" name="name" value="{{ old('name', $mosque->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori / Tipologi *</label>
                    <select name="type" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none">
                        @foreach(App\Enums\MosqueType::cases() as $type)
                            <option value="{{ $type->value }}" {{ $mosque->type === $type ? 'selected' : '' }}>{{ $type->label() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Registrasi SIMAS (Kemenag)</label>
                    <input type="text" name="kemenag_id" value="{{ old('kemenag_id', $mosque->kemenag_id) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="01.x.xx.xx.xx.xxxxx">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Daya Tampung Jamaah (Orang)</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $mosque->profile?->capacity) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Resmi</label>
                    <input type="email" name="email" value="{{ old('email', $mosque->email) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Telepon / Sekretariat</label>
                    <input type="text" name="phone" value="{{ old('phone', $mosque->phone) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Jalan Lengkap *</label>
                    <textarea name="address_line" rows="2" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">{{ old('address_line', $mosque->address_line) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Provinsi *</label>
                    <input type="text" name="province" value="{{ old('province', $mosque->province) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kabupaten / Kota *</label>
                    <input type="text" name="city" value="{{ old('city', $mosque->city) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kecamatan *</label>
                    <input type="text" name="district" value="{{ old('district', $mosque->district) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kelurahan / Desa *</label>
                    <input type="text" name="village" value="{{ old('village', $mosque->village) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sejarah Singkat Masjid</label>
                    <textarea name="history" rows="3" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">{{ old('history', $mosque->profile?->history) }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-6 py-3 rounded-xl text-xs transition shadow-md">
                    Simpan Perubahan Profil
                </button>
            </div>
        </form>
    </div>

    <!-- Facilities Section -->
    <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex justify-between items-center pb-3 border-b border-slate-100">
            <h3 class="font-heading font-bold text-lg text-slate-900">Fasilitas Masjid</h3>
            <button @click="facilityModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-4 py-2 rounded-xl text-xs flex items-center space-x-1.5 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Fasilitas</span>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($facilities as $fac)
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-start space-x-3 text-xs">
                    <div class="p-2.5 rounded-xl bg-emerald-100 text-emerald-800">
                        <i data-lucide="{{ $fac->icon ?? 'check-circle' }}" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <span class="font-bold text-slate-900 block">{{ $fac->name }}</span>
                        <span class="text-slate-500 text-[11px] block">{{ $fac->quantity }} Unit • Kondisi {{ $fac->condition }}</span>
                        <span class="text-slate-400 text-[10px] block mt-1">{{ $fac->description }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Tambah Fasilitas -->
    <div x-show="facilityModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="facilityModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tambah Fasilitas Baru</h3>
                <button @click="facilityModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.facilities.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Fasilitas *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Contoh: Tempat Wudhu Wanita">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori *</label>
                        <select name="category" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            <option value="IBADAH">Ibadah</option>
                            <option value="SANITASI">Sanitasi & Wudhu</option>
                            <option value="MULTIMEDIA">Multimedia & Audio</option>
                            <option value="AKSESIBILITAS">Aksesibilitas</option>
                            <option value="UMUM">Umum</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Unit *</label>
                        <input type="number" name="quantity" min="1" value="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kondisi *</label>
                    <select name="condition" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        <option value="EXCELLENT">Sangat Baik (Excellent)</option>
                        <option value="GOOD">Baik (Good)</option>
                        <option value="FAIR">Cukup (Fair)</option>
                        <option value="POOR">Butuh Perbaikan (Poor)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keterangan / Spesifikasi</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none"></textarea>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="facilityModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
