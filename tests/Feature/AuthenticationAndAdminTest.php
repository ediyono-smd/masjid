<?php

namespace Tests\Feature;

use App\Models\Mosque;
use App\Models\User;
use Tests\TestCase;

class AuthenticationAndAdminTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk Akun Takmir');
    }

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Registrasi Masjid Baru');
    }

    public function test_superadmin_can_access_superadmin_dashboard(): void
    {
        $superadmin = User::where('email', 'superadmin@masjidindonesia.id')->first();
        if (!$superadmin) {
            $this->markTestSkipped('Superadmin user not found.');
        }

        $response = $this->actingAs($superadmin)->get('/superadmin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Super Admin Platform Overview');
    }

    public function test_mosque_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('email', 'admin@al-jabbar.id')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found.');
        }

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Saldo Kas Berjalan');
    }

    public function test_admin_can_access_finance_ledger(): void
    {
        $admin = User::where('email', 'admin@al-jabbar.id')->first();
        if (!$admin) {
            $this->markTestSkipped('Admin user not found.');
        }

        $response = $this->actingAs($admin)->get('/admin/keuangan');
        $response->assertStatus(200);
        $response->assertSee('Daftar Jurnal Transaksi Kas');
    }
}
