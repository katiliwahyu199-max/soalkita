<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Models\TerbitanTugas;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $teacherQuery = User::where('role', 'guru');
        $teacherCount = (clone $teacherQuery)->count();
        $activeTeachers = (clone $teacherQuery)->where('subscription_ends_at', '>', $now)->count();
        $trialTeachers = (clone $teacherQuery)
            ->where('trial_ends_at', '>', $now)
            ->where(fn ($query) => $query->whereNull('subscription_ends_at')->orWhere('subscription_ends_at', '<=', $now))
            ->count();

        $approvedPayments = SubscriptionPayment::where('status', 'approved');
        $totalRevenue = (clone $approvedPayments)->sum('amount');
        $monthRevenue = (clone $approvedPayments)
            ->whereBetween('reviewed_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->sum('amount');
        $paidTeacherCount = (clone $approvedPayments)->distinct()->count('user_id');
        $repeatSubscriberIds = (clone $approvedPayments)
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) >= 2')
            ->pluck('user_id');
        $repeatPublisherIds = TerbitanTugas::query()
            ->select('user_id')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) >= 2')
            ->pluck('user_id');
        $repeatPublishers = TerbitanTugas::query()
            ->select('user_id')
            ->selectRaw('COUNT(*) as publications_count')
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) >= 2')
            ->orderByDesc('publications_count')
            ->with('user:id,name,email')
            ->limit(5)
            ->get();

        $firstMonth = $now->copy()->startOfMonth()->subMonths(5);
        $lastMonth = $now->copy()->endOfMonth();
        $monthKeys = collect(range(0, 5))
            ->map(fn ($offset) => $firstMonth->copy()->addMonths($offset));
        $revenueByMonth = (clone $approvedPayments)
            ->whereBetween('reviewed_at', [$firstMonth, $lastMonth])
            ->get(['amount', 'reviewed_at'])
            ->groupBy(fn ($payment) => $payment->reviewed_at?->format('Y-m'))
            ->map(fn ($payments) => $payments->sum('amount'));
        $publicationsByMonth = TerbitanTugas::whereBetween('created_at', [$firstMonth, $lastMonth])
            ->get(['created_at'])
            ->groupBy(fn ($publication) => $publication->created_at->format('Y-m'))
            ->map->count();

        $monthlyAnalytics = $monthKeys->map(fn ($month) => [
            'key' => $month->format('Y-m'),
            'label' => $month->translatedFormat('M Y'),
            'revenue' => (int) $revenueByMonth->get($month->format('Y-m'), 0),
            'publications' => (int) $publicationsByMonth->get($month->format('Y-m'), 0),
        ]);

        return view('admin.dashboard', [
            'teacherCount' => $teacherCount,
            'activeTeachers' => $activeTeachers,
            'trialTeachers' => $trialTeachers,
            'expiredTeachers' => max(0, $teacherCount - $activeTeachers - $trialTeachers),
            'pendingPayments' => SubscriptionPayment::where('status', 'pending')->count(),
            'totalRevenue' => $totalRevenue,
            'monthRevenue' => $monthRevenue,
            'paidTeacherCount' => $paidTeacherCount,
            'repeatSubscriberCount' => $repeatSubscriberIds->count(),
            'repeatPublisherCount' => $repeatPublisherIds->count(),
            'repeatUseRate' => $teacherCount ? round($repeatPublisherIds->count() / $teacherCount * 100) : 0,
            'monthlyAnalytics' => $monthlyAnalytics,
            'repeatPublishers' => $repeatPublishers,
            'latestPayments' => SubscriptionPayment::with('user')
                ->where('status', 'approved')
                ->latest('reviewed_at')
                ->limit(5)
                ->get(),
        ]);
    }
}