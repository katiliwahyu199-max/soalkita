<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-white">Dashboard Admin</h2>
    </x-slot>

    @php
        $maxRevenue = max(1, $monthlyAnalytics->max('revenue'));
        $maxPublications = max(1, $monthlyAnalytics->max('publications'));
    @endphp

    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-wrap items-end justify-between gap-4 border-b border-slate-300 pb-5">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Analitik operasional</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">Ringkasan bisnis</h1>
                <p class="mt-1 text-sm text-slate-600">Pendapatan dihitung dari pembayaran yang telah disetujui.</p>
            </div>
            <a href="{{ route('admin.subscriptions.index') }}" class="bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">Kelola pembayaran</a>
        </div>

        <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="border-l-4 border-emerald-600 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-600">Pendapatan bulan ini</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">Rp{{ number_format($monthRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="border-l-4 border-blue-700 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-600">Total pendapatan</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="border-l-4 border-cyan-600 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-600">Guru berlangganan aktif</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($activeTeachers) }}</p>
                <p class="mt-1 text-xs text-slate-500">dari {{ number_format($teacherCount) }} guru</p>
            </div>
            <div class="border-l-4 border-amber-500 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-600">Menunggu validasi</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($pendingPayments) }}</p>
                <a href="{{ route('admin.subscriptions.index') }}" class="mt-1 inline-block text-xs font-semibold text-blue-700 underline">Tinjau pembayaran</a>
            </div>
        </section>

        <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <div class="border-y border-slate-300 py-4">
                <p class="text-sm text-slate-600">Masa coba aktif</p>
                <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($trialTeachers) }} guru</p>
            </div>
            <div class="border-y border-slate-300 py-4">
                <p class="text-sm text-slate-600">Masa aktif berakhir</p>
                <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($expiredTeachers) }} guru</p>
            </div>
            <div class="border-y border-slate-300 py-4">
                <p class="text-sm text-slate-600">Berlangganan berulang</p>
                <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($repeatSubscriberCount) }} guru</p>
                <p class="text-xs text-slate-500">{{ number_format($paidTeacherCount) }} guru pernah membayar</p>
            </div>
            <div class="border-y border-slate-300 py-4">
                <p class="text-sm text-slate-600">Penggunaan berulang</p>
                <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format($repeatPublisherCount) }} guru</p>
                <p class="text-xs text-slate-500">{{ $repeatUseRate }}% guru menerbitkan tugas lebih dari sekali</p>
            </div>
        </section>

        <section class="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
            <div>
                <div class="mb-4 flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Tren 6 bulan</p>
                        <h2 class="text-lg font-bold text-slate-900">Pendapatan dan penerbitan</h2>
                    </div>
                    <div class="flex gap-4 text-xs text-slate-600">
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 bg-emerald-600"></span>Pendapatan</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 bg-blue-700"></span>Tugas terbit</span>
                    </div>
                </div>
                <div class="space-y-4 border-y border-slate-200 py-4">
                    @foreach ($monthlyAnalytics as $month)
                        <div class="grid grid-cols-[4.5rem_1fr] gap-3 sm:grid-cols-[5rem_1fr_1fr] sm:items-center">
                            <span class="text-xs font-medium text-slate-600">{{ $month['label'] }}</span>
                            <div>
                                <div class="mb-1 flex justify-between gap-2 text-xs text-slate-600 sm:hidden">
                                    <span>Pendapatan</span><span>Rp{{ number_format($month['revenue'], 0, ',', '.') }}</span>
                                </div>
                                <div class="h-2 bg-slate-100"><div class="h-full bg-emerald-600" style="width: {{ $month['revenue'] ? max(2, (int) ($month['revenue'] / $maxRevenue * 100)) : 0 }}%"></div></div>
                                <span class="mt-1 hidden text-xs text-slate-600 sm:block">Rp{{ number_format($month['revenue'], 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <div class="mb-1 flex justify-between gap-2 text-xs text-slate-600 sm:hidden">
                                    <span>Tugas terbit</span><span>{{ number_format($month['publications']) }}</span>
                                </div>
                                <div class="h-2 bg-slate-100"><div class="h-full bg-blue-700" style="width: {{ $month['publications'] ? max(2, (int) ($month['publications'] / $maxPublications * 100)) : 0 }}%"></div></div>
                                <span class="mt-1 hidden text-xs text-slate-600 sm:block">{{ number_format($month['publications']) }} tugas terbit</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-8">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Retensi penggunaan</p>
                    <h2 class="mb-3 text-lg font-bold text-slate-900">Guru yang sering menerbitkan</h2>
                    <div class="divide-y divide-slate-200 border-y border-slate-200">
                        @forelse ($repeatPublishers as $publisher)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $publisher->user?->name ?? 'Akun tidak tersedia' }}</p>
                                    <p class="truncate text-xs text-slate-500">{{ $publisher->user?->email }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-semibold text-slate-700">{{ number_format($publisher->publications_count) }} tugas</span>
                            </div>
                        @empty
                            <p class="py-5 text-sm text-slate-500">Belum ada guru yang menerbitkan tugas lebih dari sekali.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">Transaksi</p>
                    <h2 class="mb-3 text-lg font-bold text-slate-900">Pembayaran terbaru</h2>
                    <div class="divide-y divide-slate-200 border-y border-slate-200">
                        @forelse ($latestPayments as $payment)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $payment->user?->name ?? 'Akun tidak tersedia' }}</p>
                                    <p class="text-xs text-slate-500">{{ $payment->reviewed_at?->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-semibold text-emerald-700">Rp{{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="py-5 text-sm text-slate-500">Belum ada pembayaran yang disetujui.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>