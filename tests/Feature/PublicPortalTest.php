<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Event;
use App\Models\Mosque;
use Tests\TestCase;

class PublicPortalTest extends TestCase
{
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('MASJID INDONESIA');
        $response->assertSee('Digitalisasi Masjid');
    }

    public function test_mosque_public_portal_loads(): void
    {
        $mosque = Mosque::first();
        if (!$mosque) {
            $this->markTestSkipped('No mosque available.');
        }

        $response = $this->get('/masjid/' . $mosque->slug);
        $response->assertStatus(200);
        $response->assertSee($mosque->name);
        $response->assertSee('Jadwal Waktu Shalat Hari Ini');
    }

    public function test_public_prayer_page_loads(): void
    {
        $mosque = Mosque::first();
        if (!$mosque) {
            $this->markTestSkipped('No mosque available.');
        }

        $response = $this->get('/masjid/' . $mosque->slug . '/jadwal');
        $response->assertStatus(200);
        $response->assertSee('Tabel Jadwal Shalat Bulan Ini');
    }

    public function test_public_event_rsvp_registration(): void
    {
        $mosque = Mosque::first();
        $event = Event::where('mosque_id', $mosque->id)->first();
        if (!$event) {
            $this->markTestSkipped('No event available.');
        }

        $response = $this->post('/masjid/' . $mosque->slug . '/kajian/' . $event->slug . '/daftar', [
            'name' => 'Fulan bin Fulan',
            'phone' => '081299998888',
            'email' => 'fulan@example.com',
        ]);

        $response->assertSessionHas('success');
    }

    public function test_qr_verification_endpoint(): void
    {
        $doc = Document::first();
        if (!$doc) {
            $this->markTestSkipped('No document available.');
        }

        $response = $this->get('/verify/' . $doc->verification_code);
        $response->assertStatus(200);
        $response->assertSee('Sistem Verifikasi Digital Resmi');
    }
}
