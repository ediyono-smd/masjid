@extends('layouts.public')

@section('title', 'Masuk Portal Pengurus & Takmir — MASJID INDONESIA')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center mx-auto shadow-md">
                <i data-lucide="lock" class="w-6 h-6 text-gold-400"></i>
            </div>
            <h1 class="font-heading font-extrabold text-2xl text-slate-900">Masuk Akun Takmir</h1>
            <p class="text-xs text-slate-500">Akses dashboard pengelolaan operasional & keuangan masjid Anda.</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-700 text-xs p-3 rounded-xl border border-red-200 space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none" placeholder="admin@al-jabbar.id">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi *</label>
                <input type="password" name="password" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-700 focus:outline-none" placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 rounded-xl text-xs transition shadow-md">
                Masuk ke Dashboard
            </button>
        </form>

        <!-- Quick Demo Credentials helper for convenient evaluation -->
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-2">
            <span class="font-bold text-slate-700 block">Akun Uji Coba Demo:</span>
            <div class="space-y-1 text-[11px] text-slate-600">
                <div><strong>Super Admin:</strong> <code>superadmin@masjidindonesia.id</code> / <code>password</code></div>
                <div><strong>Admin Masjid:</strong> <code>admin@al-jabbar.id</code> / <code>password</code></div>
                <div><strong>Bendahara:</strong> <code>bendahara@al-jabbar.id</code> / <code>password</code></div>
            </div>
        </div>

        <div class="text-center pt-2 text-xs text-slate-500">
            Belum mendaftarkan masjid Anda?
            <a href="{{ route('register') }}" class="text-emerald-700 font-bold hover:underline">Daftar Sekarang</a>
        </div>
    </div>
</div>
@endsection
