@extends('layouts.public')

@section('title', $campaign->title . ' — ' . $mosque->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-12" x-data="{ nominal: 50000, isAnonymous: false }">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left 2 Cols: Campaign Detail & Doa Feed -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6 shadow-sm">
                <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-3 py-1 rounded-full uppercase">
                    {{ $campaign->category }}
                </span>
                <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-slate-900 leading-snug">{{ $campaign->title }}</h1>

                <!-- Progress Bar Card -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                    <div class="flex justify-between items-baseline">
                        <div>
                            <span class="text-xs text-slate-500 block">Dana Terkumpul</span>
                            <span class="font-heading font-extrabold text-2xl text-emerald-700">Rp{{ number_format((float) $campaign->collected_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-500 block">Target Kebutuhan</span>
                            <span class="font-semibold text-slate-700 text-sm">Rp{{ number_format((float) $campaign->target_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-600 h-3 rounded-full transition-all duration-500" style="width: {{ $campaign->progress_percentage }}%"></div>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-500 pt-1">
                        <span>{{ $campaign->donor_count }} Donatur Dermawan</span>
                        <span class="font-bold text-emerald-700">{{ $campaign->progress_percentage }}% Tercapai</span>
                    </div>
                </div>

                <!-- Description -->
                <div class="text-sm text-slate-700 leading-relaxed space-y-3">
                    <h3 class="font-heading font-bold text-base text-slate-900">Uraian Program</h3>
                    <p>{{ $campaign->description }}</p>
                </div>
            </div>

            <!-- Recent Donors & Prayers Feed -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6 shadow-sm">
                <h3 class="font-heading font-bold text-lg text-slate-900 flex items-center space-x-2">
                    <i data-lucide="message-square-heart" class="w-5 h-5 text-emerald-600"></i>
                    <span>Doa-Doa Para Donatur</span>
                </h3>

                <div class="space-y-4">
                    @forelse($campaign->donations as $don)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-slate-900">{{ $don->display_name }}</span>
                                <span class="text-emerald-700 font-bold">Rp{{ number_format((float) $don->amount, 0, ',', '.') }}</span>
                            </div>
                            @if($don->doa_message)
                                <p class="text-xs text-slate-600 italic">"{{ $don->doa_message }}"</p>
                            @endif
                            <span class="text-[10px] text-slate-400 block">{{ $don->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 italic">Jadilah donatur pertama untuk program kebaikan ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Checkout Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xl sticky top-28 space-y-6">
                <h3 class="font-heading font-bold text-lg text-slate-900">Formulir Infaq & Donasi</h3>

                <form action="{{ route('public.donations.store', $mosque->slug) }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                    <!-- Quick Nominal Buttons -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-700">Pilih Nominal Infaq</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach([25000, 50000, 100000, 250000, 500000, 1000000] as $amountOption)
                                <button type="button" @click="nominal = {{ $amountOption }}" :class="nominal === {{ $amountOption }} ? 'bg-emerald-700 text-white font-bold border-emerald-700' : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-emerald-600'" class="p-2.5 rounded-xl border text-xs text-center transition">
                                    Rp{{ number_format($amountOption, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Custom Nominal Input -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Atau Masukkan Nominal Sendiri (Rp)</label>
                        <input type="number" name="amount" x-model="nominal" min="10000" step="5000" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-700">
                    </div>

                    <!-- Anonymous Checkbox -->
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="is_anon" name="is_anonymous" value="1" x-model="isAnonymous" class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                        <label for="is_anon" class="text-xs text-slate-700 font-medium">Sembunyikan nama saya (Hamba Allah)</label>
                    </div>

                    <!-- Donor Name -->
                    <div x-show="!isAnonymous">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="donor_name" :required="!isAnonymous" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp (Untuk e-Kwitansi)</label>
                        <input type="text" name="donor_phone" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="0812xxxxxxxx">
                    </div>

                    <!-- Doa Message -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pesan Kebaikan / Doa (Opsional)</label>
                        <textarea name="doa_message" rows="2" class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-emerald-700" placeholder="Tuliskan hajat atau doa..."></textarea>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Pembayaran</label>
                        <select name="payment_method" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none">
                            <option value="QRIS">QRIS Nasional (BCA, Mandiri, GoPay, OVO, ShopeePay)</option>
                            <option value="BANK_TRANSFER">Transfer Rekening Kas Masjid</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-700 to-emerald-600 hover:from-emerald-800 hover:to-emerald-700 text-white font-bold py-3.5 rounded-2xl text-sm transition shadow-md">
                        Lanjutkan Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
