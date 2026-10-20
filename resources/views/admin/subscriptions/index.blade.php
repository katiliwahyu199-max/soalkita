<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">Kelola Langganan</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-10">
        @if (session('success'))
            <div class="border-l-4 border-emerald-600 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="border-l-4 border-rose-600 bg-rose-50 px-4 py-3 text-sm text-rose-900">{{ $errors->first() }}</div>
        @endif

        <section class="grid gap-8 border-b border-slate-300 pb-8 md:grid-cols-[16rem_1fr]">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Ringkasan</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $teacherCount }}</p>
                <p class="text-sm text-slate-600">guru terdaftar</p>
            </div>
            <form method="POST" action="{{ route('admin.subscriptions.settings') }}" class="grid gap-4 sm:grid-cols-2">
                @csrf
                @method('PUT')
                <div>
                    <label for="monthly_price" class="block text-sm font-medium text-slate-700">Harga per bulan (rupiah)</label>
                    <input id="monthly_price" name="monthly_price" type="number" min="1000" max="100000000" value="{{ old('monthly_price', $settings->monthly_price) }}" required class="mt-1 block w-full border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">
                </div>
                <div class="sm:row-span-2">
                    <label for="payment_instructions" class="block text-sm font-medium text-slate-700">Petunjuk pembayaran untuk guru</label>
                    <textarea id="payment_instructions" name="payment_instructions" rows="4" maxlength="3000" class="mt-1 block w-full border-slate-300 text-sm shadow-sm focus:border-blue-600 focus:ring-blue-600">{{ old('payment_instructions', $settings->payment_instructions) }}</textarea>
                </div>
                <div class="sm:col-start-1">
                    <button type="submit" class="bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Simpan pengaturan</button>
                </div>
            </form>
        </section>

        <section>
            <div class="mb-4 flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Validasi</p>
                    <h1 class="text-xl font-bold text-slate-900">Pembayaran guru</h1>
                </div>
            </div>
            <div class="overflow-x-auto border-y border-slate-200">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600">
                        <tr><th class="px-4 py-3">Guru</th><th class="px-4 py-3">Diajukan</th><th class="px-4 py-3">Nominal</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Bukti / tindakan</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($payments as $payment)
                            <tr class="align-top">
                                <td class="px-4 py-4"><p class="font-semibold text-slate-900">{{ $payment->user->name }}</p><p class="text-slate-500">{{ $payment->user->email }}</p></td>
                                <td class="px-4 py-4 text-slate-600">{{ $payment->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-4 py-4 font-medium text-slate-900">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-4">{{ ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'][$payment->status] ?? $payment->status }}</td>
                                <td class="space-y-3 px-4 py-4">
                                    <a class="font-semibold text-blue-700 underline" href="{{ route('admin.subscriptions.proof', $payment) }}">Unduh bukti</a>
                                    @if ($payment->status === 'pending')
                                        <div class="flex flex-wrap gap-2">
                                            <form method="POST" action="{{ route('admin.subscriptions.approve', $payment) }}">
                                                @csrf
                                                <button class="bg-emerald-700 px-3 py-1.5 font-semibold text-white hover:bg-emerald-800">Setujui</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.subscriptions.reject', $payment) }}" class="flex flex-wrap gap-2">
                                                @csrf
                                                <input name="admin_note" required maxlength="1000" placeholder="Alasan penolakan" aria-label="Alasan penolakan" class="w-44 border-slate-300 text-xs">
                                                <button class="bg-rose-700 px-3 py-1.5 font-semibold text-white hover:bg-rose-800">Tolak</button>
                                            </form>
                                        </div>
                                    @elseif ($payment->admin_note)
                                        <p class="text-xs text-slate-500">Catatan: {{ $payment->admin_note }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada pengajuan pembayaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $payments->links() }}</div>
        </section>

        <section>
            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Pemantauan</p>
            <h2 class="mb-4 text-xl font-bold text-slate-900">Daftar guru</h2>
            <div class="overflow-x-auto border-y border-slate-200">
                <table class="w-full min-w-[960px] text-left text-sm">
                    <thead class="bg-slate-100 text-xs uppercase tracking-wide text-slate-600"><tr><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Aktif sampai</th><th class="px-4 py-3">Atur tanggal akses</th></tr></thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($teachers as $teacher)
                            @php
                                $activeUntil = collect([$teacher->trial_ends_at, $teacher->subscription_ends_at])
                                    ->filter(fn ($date) => $date?->isFuture())
                                    ->sortByDesc(fn ($date) => $date->timestamp)
                                    ->first();
                            @endphp
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $teacher->name }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $teacher->email }}</td>
                                <td class="px-4 py-3">{{ $teacher->subscription_ends_at?->isFuture() ? 'Berlangganan' : ($teacher->trial_ends_at?->isFuture() ? 'Uji coba' : 'Kedaluwarsa') }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $activeUntil?->translatedFormat('d M Y, H:i') ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div x-data="{ editingAccess: false }" class="min-w-[25rem]">
                                        <button type="button" @click="editingAccess = !editingAccess" :aria-expanded="editingAccess.toString()" class="bg-slate-200 px-3 py-2 text-xs font-semibold text-slate-800 hover:bg-slate-300">
                                            <span x-text="editingAccess ? 'Tutup edit' : 'Edit tanggal'"></span>
                                        </button>
                                        <form x-show="editingAccess" x-cloak method="POST" action="{{ route('admin.subscriptions.teachers.access', $teacher) }}" class="mt-3 flex flex-wrap items-end gap-2">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label for="trial-{{ $teacher->id }}" class="block text-xs text-slate-600">Trial sampai</label>
                                                <input id="trial-{{ $teacher->id }}" name="trial_ends_at" type="datetime-local" value="{{ $teacher->trial_ends_at?->format('Y-m-d\\TH:i') }}" class="mt-1 border-slate-300 text-xs">
                                            </div>
                                            <div>
                                                <label for="subscription-{{ $teacher->id }}" class="block text-xs text-slate-600">Langganan sampai</label>
                                                <input id="subscription-{{ $teacher->id }}" name="subscription_ends_at" type="datetime-local" value="{{ $teacher->subscription_ends_at?->format('Y-m-d\\TH:i') }}" class="mt-1 border-slate-300 text-xs">
                                            </div>
                                            <button type="submit" class="bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">Simpan</button>
                                            <button type="button" @click="editingAccess = false" class="px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100">Batal</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada guru terdaftar.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>