<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">Langganan Guru</h2>
    </x-slot>

    @php
        $activeUntil = collect([$user->trial_ends_at, $user->subscription_ends_at])
            ->filter(fn ($date) => $date?->isFuture())
            ->sortByDesc(fn ($date) => $date->timestamp)
            ->first();
    @endphp

    <div class="mx-auto max-w-4xl space-y-6">
        @if (session('success'))
            <div class="border-l-4 border-emerald-600 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="border-l-4 border-amber-500 bg-amber-50 px-4 py-3 text-sm text-amber-900">{{ session('warning') }}</div>
        @endif

        <section class="border-b border-slate-300 pb-6">
            <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Status akun</p>
            <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">
                        {{ $activeUntil ? ($user->subscription_ends_at?->isFuture() ? 'Langganan aktif' : 'Masa coba gratis aktif') : 'Masa aktif berakhir' }}
                    </h1>
                    @if ($activeUntil)
                        <p class="mt-1 text-sm text-slate-600">Aktif sampai {{ $activeUntil->translatedFormat('d F Y, H:i') }}</p>
                    @else
                        <p class="mt-1 text-sm text-slate-600">Kirim bukti pembayaran untuk mengaktifkan kembali fitur guru.</p>
                    @endif
                </div>
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-blue-700 underline">Kembali ke dashboard</a>
            </div>
        </section>

        <section class="grid gap-8 md:grid-cols-[1fr_1.2fr]">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Paket bulanan</h2>
                <p class="mt-2 text-3xl font-bold text-slate-900">Rp{{ number_format($settings->monthly_price, 0, ',', '.') }}<span class="text-base font-medium text-slate-500"> / bulan</span></p>
                <div class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $settings->payment_instructions ?: 'Petunjuk pembayaran belum tersedia. Hubungi admin sebelum melakukan transfer.' }}</div>
            </div>

            <div class="border-l border-slate-200 pl-0 md:pl-8">
                <h2 class="text-lg font-bold text-slate-900">Pembayaran terakhir</h2>
                @if ($payment)
                    <p class="mt-2 text-sm text-slate-600">Rp{{ number_format($payment->amount, 0, ',', '.') }} · {{ $payment->created_at->translatedFormat('d F Y') }}</p>
                    <p class="mt-1 text-sm font-semibold {{ $payment->status === 'approved' ? 'text-emerald-700' : ($payment->status === 'rejected' ? 'text-rose-700' : 'text-amber-700') }}">
                        {{ ['pending' => 'Menunggu validasi', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'][$payment->status] ?? $payment->status }}
                    </p>
                    @if ($payment->status === 'rejected' && $payment->admin_note)
                        <p class="mt-2 text-sm text-slate-600">Catatan admin: {{ $payment->admin_note }}</p>
                    @endif
                @else
                    <p class="mt-2 text-sm text-slate-600">Belum ada pembayaran.</p>
                @endif

                @if (! $payment || $payment->status !== 'pending')
                    <form method="POST" action="{{ route('subscription.payment') }}" enctype="multipart/form-data" class="mt-5 space-y-3">
                        @csrf
                        <label for="proof" class="block text-sm font-medium text-slate-700">Bukti transfer (JPG, PNG, atau PDF; maks. 5 MB)</label>
                        <input id="proof" name="proof" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="block w-full text-sm text-slate-700 file:mr-4 file:border-0 file:bg-slate-200 file:px-4 file:py-2 file:font-semibold file:text-slate-800 hover:file:bg-slate-300">
                        @error('proof')<p class="text-sm text-rose-700">{{ $message }}</p>@enderror
                        <button type="submit" class="bg-blue-700 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-800">Kirim bukti pembayaran</button>
                    </form>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>