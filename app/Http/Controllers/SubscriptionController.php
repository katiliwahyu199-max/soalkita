<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $payment = $request->user()->payments()->latest()->first();

        return view('subscription.index', [
            'user' => $request->user(),
            'payment' => $payment,
            'settings' => SubscriptionSetting::current(),
        ]);
    }

    public function submitPayment(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->payments()->where('status', 'pending')->exists()) {
            return back()->withErrors(['proof' => 'Bukti pembayaran Anda sedang menunggu validasi admin.']);
        }

        $validated = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);
        $settings = SubscriptionSetting::current();
        $path = $validated['proof']->store('subscription-proofs', 'local');

        SubscriptionPayment::create([
            'user_id' => $user->id,
            'amount' => $settings->monthly_price,
            'proof_path' => $path,
        ]);

        return back()->with('success', 'Bukti pembayaran terkirim dan menunggu validasi admin.');
    }
}