@extends('layouts.admin')

@section('title', 'Audit Log Keamanan — MASJID INDONESIA')
@section('page_title', 'Audit Trail & Keamanan Sistem')
@section('page_subtitle', 'Rekam jejak setiap aktivitas autentikasi, modifikasi data keuangan, dan perubahan izin pengguna.')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-heading font-bold text-base text-slate-900">Catatan Aktivitas Sistem (Audit Log)</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Jenis Peristiwa</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Masjid Terkait</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4">Perubahan Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="py-3 px-4">
                                <span class="bg-slate-100 text-slate-800 font-mono font-bold px-2 py-0.5 rounded text-[10px]">{{ $log->event_type }}</span>
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-900">{{ $log->user?->name ?? 'Sistem / Tamu' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $log->mosque?->name ?? 'Global' }}</td>
                            <td class="py-3 px-4 font-mono text-slate-500">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-600 max-w-xs truncate">
                                {{ $log->new_values ? json_encode($log->new_values) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 italic">Belum ada catatan log.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
