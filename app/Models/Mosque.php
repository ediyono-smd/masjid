<?php

namespace App\Models;

use App\Enums\MosqueStatus;
use App\Enums\MosqueType;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mosque extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'id',
        'kemenag_id',
        'name',
        'slug',
        'type',
        'status',
        'email',
        'phone',
        'address_line',
        'province',
        'city',
        'district',
        'village',
        'postal_code',
        'latitude',
        'longitude',
        'logo_url',
        'banner_url',
        'bank_accounts',
        'qris_url',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => MosqueType::class,
            'status' => MosqueStatus::class,
            'bank_accounts' => 'array',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'verified_at' => 'datetime',
        ];
    }

    public function profile(): HasOne
    {
        return $this->hasOne(MosqueProfile::class, 'mosque_id');
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(MosqueFacility::class, 'mosque_id');
    }

    public function staff(): HasMany
    {
        return $this->hasMany(MosqueStaff::class, 'mosque_id');
    }

    public function prayerSetting(): HasOne
    {
        return $this->hasOne(PrayerSetting::class, 'mosque_id');
    }

    public function prayerSchedules(): HasMany
    {
        return $this->hasMany(PrayerSchedule::class, 'mosque_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'mosque_id');
    }

    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'mosque_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'mosque_id');
    }

    public function donationCampaigns(): HasMany
    {
        return $this->hasMany(DonationCampaign::class, 'mosque_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'mosque_id');
    }

    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'mosque_id');
    }

    public function socialPrograms(): HasMany
    {
        return $this->hasMany(SocialProgram::class, 'mosque_id');
    }

    public function zakatPayments(): HasMany
    {
        return $this->hasMany(ZakatPayment::class, 'mosque_id');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'mosque_id');
    }

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'mosque_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'mosque_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'mosque_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'mosque_id');
    }
}
