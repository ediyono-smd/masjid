@extends('layouts.admin')

@section('title', 'Perpustakaan Masjid — ' . $mosque->name)
@section('page_title', 'Perpustakaan & Katalog Kitab')
@section('page_subtitle', 'Kelola koleksi buku Islam, kitab kuning, mushaf Al-Quran, dan sirkulasi peminjaman jamaah.')

@section('content')
<div class="space-y-8" x-data="{ bookModal: false, loanModal: false, selectedBookId: '' }">
    <div class="flex justify-between items-center">
        <h3 class="font-heading font-bold text-lg text-slate-900">Katalog Kitab & Buku Perpustakaan</h3>
        <div class="flex space-x-2">
            <button @click="loanModal = true" class="bg-slate-900 hover:bg-slate-800 text-white font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 transition">
                <i data-lucide="book-up" class="w-4 h-4"></i>
                <span>Catat Peminjaman</span>
            </button>
            <button @click="bookModal = true" class="bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2.5 rounded-xl text-xs flex items-center space-x-1.5 shadow-sm transition">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                <span>Tambah Judul Buku</span>
            </button>
        </div>
    </div>

    <!-- Active Loans Bar if any -->
    @if($activeLoans->count() > 0)
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h4 class="font-heading font-bold text-sm text-slate-900 flex items-center space-x-2">
                <i data-lucide="hourglass" class="w-4 h-4 text-amber-500"></i>
                <span>Buku Yang Sedang Dipinjam Jamaah</span>
            </h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($activeLoans as $loan)
                    <div class="p-3 bg-slate-50 rounded-2xl border border-slate-100 text-xs flex justify-between items-center">
                        <div>
                            <span class="font-bold text-slate-900 block">{{ $loan->book->title }}</span>
                            <span class="text-slate-500 text-[11px] block">Peminjam: {{ $loan->borrower_name }} ({{ $loan->borrower_phone }})</span>
                            <span class="text-amber-700 text-[10px] block font-semibold">Tenggat: {{ $loan->due_date->format('d/m/Y') }}</span>
                        </div>
                        <form action="{{ route('admin.library.loan.return', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-emerald-100 hover:bg-emerald-700 hover:text-white text-emerald-800 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                                Kembali
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Books Catalog Table -->
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Kode Buku</th>
                        <th class="py-3 px-4">Judul Kitab / Buku</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Penulis / Pengarang</th>
                        <th class="py-3 px-4">Penerbit</th>
                        <th class="py-3 px-4">Rak</th>
                        <th class="py-3 px-4 text-center">Eksemplar (Tersedia / Total)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($books as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-slate-600">{{ $b->book_code }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $b->title }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $b->category?->name ?? 'Umum' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $b->author ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $b->publisher ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-600 font-semibold">{{ $b->shelf_location ?? 'Rak Utama' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $b->copies_available > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $b->copies_available }} / {{ $b->copies_total }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada koleksi buku dalam katalog.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Buku -->
    <div x-show="bookModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="bookModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Tambah Judul Buku / Kitab</h3>
                <button @click="bookModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.library.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kode Buku *</label>
                        <input type="text" name="book_code" value="LIB-{{ rand(1000, 9999) }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Buku / Kitab *</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="Tafsir Jalalain / Riyadhus Shalihin">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pengarang / Penulis</label>
                        <input type="text" name="author" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Penerbit</label>
                        <input type="text" name="publisher" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Bahasa</label>
                        <input type="text" name="language" value="Indonesia" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah *</label>
                        <input type="number" name="copies_total" value="1" min="1" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Lokasi Rak</label>
                        <input type="text" name="shelf_location" value="Rak A-1" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="bookModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Catat Peminjaman -->
    <div x-show="loanModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200" @click.outside="loanModal = false">
            <div class="flex justify-between items-center pb-3 border-b border-slate-100">
                <h3 class="font-heading font-bold text-base text-slate-900">Catat Peminjaman Kitab</h3>
                <button @click="loanModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('admin.library.loan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Judul Buku *</label>
                    <select name="book_id" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                        @foreach($books as $b)
                            @if($b->copies_available > 0)
                                <option value="{{ $b->id }}">{{ $b->title }} (Tersedia: {{ $b->copies_available }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Jamaah Peminjam *</label>
                    <input type="text" name="borrower_name" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp *</label>
                    <input type="text" name="borrower_phone" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none" placeholder="08xxxxxxxx">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Pinjam *</label>
                        <input type="date" name="loan_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tenggat Kembali *</label>
                        <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none">
                    </div>
                </div>

                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" @click="loanModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-xs">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs shadow-md">Simpan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
