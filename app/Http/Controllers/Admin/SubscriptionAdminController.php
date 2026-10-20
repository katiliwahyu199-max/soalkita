<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SubscriptionAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.subscriptions.index', [
            'settings' => SubscriptionSetting::current(),
            'payments' => SubscriptionPayment::with('user')->latest()->paginate(20),
            'teacherCount' => User::where('role', 'guru')->count(),
            'teachers' => User::where('role', 'guru')->latest()->get(),
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'monthly_price' => ['required', 'integer', 'min:1000', 'max:100000000'],
            'payment_instructions' => ['nullable', 'string', 'max:3000'],
        ]);

        SubscriptionSetting::current()->update($validated);

        return back()->with('success', 'Pengaturan langganan berhasil disimpan.');
    }

    public function updateTeacherAccess(Request $request, User $teacher): RedirectResponse
    {
        abort_unless($teacher->role === 'guru', 404);

        $validated = $request->validate([
            'trial_ends_at' => ['nullable', 'date_format:Y-m-d\\TH:i'],
            'subscription_ends_at' => ['nullable', 'date_format:Y-m-d\\TH:i'],
        ]);

        $teacher->forceFill($validated)->save();

        return back()->with('success', "Tanggal akses {$teacher->name} berhasil diperbarui.");
    }

    public function proof(SubscriptionPayment $payment)
    {
        abort_unless(Storage::disk('local')->exists($payment->proof_path), 404);

        return Storage::disk('local')->download($payment->proof_path);
    }

    public function approve(SubscriptionPayment $payment): RedirectResponse
    {
        DB::transaction(function () use ($payment): void {
            $payment = SubscriptionPayment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            abort_unless($payment->status === 'pending', 409, 'Pembayaran ini sudah ditinjau.');

            $user = User::whereKey($payment->user_id)->lockForUpdate()->firstOrFail();
            $activeEnd = collect([$user->trial_ends_at, $user->subscription_ends_at])
                ->filter(fn ($date) => $date?->isFuture())
                ->sortByDesc(fn ($date) => $date->timestamp)
                ->first();

            $user->subscription_ends_at = ($activeEnd?->copy() ?? now())->addMonthNoOverflow();
            $user->save();

            $payment->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'admin_note' => null,
            ]);
        });

        return back()->with('success', 'Pembayaran disetujui dan masa aktif guru diperpanjang satu bulan.');
    }

    public function reject(Request $request, SubscriptionPayment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'admin_note' => ['required', 'string', 'max:1000'],
        ]);

        $updated = SubscriptionPayment::whereKey($payment->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'admin_note' => $validated['admin_note'],
            ]);

        abort_unless($updated, 409, 'Pembayaran ini sudah ditinjau.');

        return back()->with('success', 'Pembayaran ditolak. Guru dapat mengirim bukti pembayaran baru.');
    }
}