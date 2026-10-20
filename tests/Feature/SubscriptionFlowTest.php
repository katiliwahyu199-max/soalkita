<?php

namespace Tests\Feature;

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionSetting;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\TerbitanTugas;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SubscriptionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_teacher_is_redirected_to_subscription_page(): void
    {
        $teacher = User::factory()->create([
            'trial_ends_at' => now()->subDay(),
        ]);

        $this->actingAs($teacher)
            ->get('/kelas')
            ->assertRedirect(route('subscription.index'));

        $this->actingAs($teacher)
            ->get(route('subscription.index'))
            ->assertOk();
    }

    public function test_teacher_can_submit_proof_and_admin_approval_starts_monthly_subscription(): void
    {
        Storage::fake('local');
        $teacher = User::factory()->create([
            'trial_ends_at' => now()->subDay(),
        ]);
        SubscriptionSetting::current()->update(['monthly_price' => 90000]);

        $this->actingAs($teacher)->post(route('subscription.payment'), [
            'proof' => UploadedFile::fake()->image('transfer.jpg'),
        ])->assertRedirect();

        $payment = SubscriptionPayment::firstOrFail();
        $this->assertSame(90000, $payment->amount);
        $this->assertSame('pending', $payment->status);
        Storage::disk('local')->assertExists($payment->proof_path);

        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $this->actingAs($admin)
            ->post(route('admin.subscriptions.approve', $payment))
            ->assertRedirect();

        $this->assertSame('approved', $payment->fresh()->status);
        $this->assertTrue($teacher->fresh()->subscription_ends_at->isFuture());
    }

    public function test_only_admin_can_open_subscription_management(): void
    {
        $teacher = User::factory()->create();

        $this->actingAs($teacher)
            ->get(route('admin.subscriptions.index'))
            ->assertForbidden();
    }

    public function test_admin_sidebar_shows_admin_dashboard_without_teacher_dashboard_link(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin')
            ->assertDontSee('>Dashboard</span>', false);
    }

    public function test_admin_seeder_creates_an_admin_from_configured_credentials(): void
    {
        Config::set('services.admin', [
            'name' => 'Admin SoalKita',
            'email' => 'admin@example.com',
            'password' => 'strong-test-password',
        ]);

        (new AdminUserSeeder)->run();

        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('strong-test-password', $admin->password));
        $this->assertNotNull($admin->email_verified_at);
    }

    public function test_admin_seeder_prompts_for_credentials_when_environment_is_not_configured(): void
    {
        Config::set('services.admin', [
            'name' => 'Administrator',
            'email' => null,
            'password' => null,
        ]);

        $this->artisan('db:seed', ['--class' => AdminUserSeeder::class])
            ->expectsQuestion('Admin name', 'Admin SoalKita')
            ->expectsQuestion('Admin email', 'admin@example.com')
            ->expectsQuestion('Admin password', 'strong-test-password')
            ->expectsQuestion('Confirm admin password', 'strong-test-password')
            ->assertExitCode(0);

        $this->assertSame('admin', User::where('email', 'admin@example.com')->firstOrFail()->role);
    }

    public function test_admin_dashboard_reports_revenue_and_repeat_usage(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $teacher = User::factory()->create();

        foreach ([now()->subDays(2), now()->subDay()] as $reviewedAt) {
            $teacher->payments()->create([
                'amount' => 90000,
                'proof_path' => 'subscription-proofs/receipt.jpg',
                'status' => 'approved',
                'reviewed_by' => $admin->id,
                'reviewed_at' => $reviewedAt,
            ]);
        }

        $kelas = Kelas::create(['user_id' => $teacher->id, 'nama_kelas' => 'Kelas Analitik']);
        $tugas = Tugas::create([
            'kelas_id' => $kelas->id,
            'nama_tugas' => 'Latihan Analitik',
            'jenis_tugas' => 'gabungan',
        ]);

        foreach (range(1, 2) as $_) {
            $teacher->terbitanTugas()->create([
                'kelas_id' => $kelas->id,
                'tugas_id' => $tugas->id,
                'status' => 'aktif',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Pendapatan bulan ini')
            ->assertSee('Rp180.000')
            ->assertSee('Berlangganan berulang')
            ->assertSee('Penggunaan berulang')
            ->assertSee($teacher->name)
            ->assertSee('2 tugas');
    }

    public function test_admin_can_edit_teacher_trial_and_subscription_dates(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();
        $teacher = User::factory()->create();
        $trialEnd = now()->subDay()->startOfMinute();
        $subscriptionEnd = now()->addDays(30)->startOfMinute();

        $this->actingAs($admin)
            ->put(route('admin.subscriptions.teachers.access', $teacher), [
                'trial_ends_at' => $trialEnd->format('Y-m-d\\TH:i'),
                'subscription_ends_at' => $subscriptionEnd->format('Y-m-d\\TH:i'),
            ])
            ->assertRedirect();

        $this->assertSame($trialEnd->timestamp, $teacher->fresh()->trial_ends_at->timestamp);
        $this->assertSame($subscriptionEnd->timestamp, $teacher->fresh()->subscription_ends_at->timestamp);

        $this->actingAs($admin)
            ->put(route('admin.subscriptions.teachers.access', $teacher), [
                'trial_ends_at' => '',
                'subscription_ends_at' => '',
            ])
            ->assertRedirect();

        $this->assertNull($teacher->fresh()->trial_ends_at);
        $this->assertNull($teacher->fresh()->subscription_ends_at);
    }
}