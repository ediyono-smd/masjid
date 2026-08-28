<?php

namespace App\Services;

use App\Enums\MosqueStatus;
use App\Models\Mosque;
use App\Models\MosqueFacility;
use App\Models\MosqueProfile;
use App\Models\MosqueStaff;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class MosqueService
{
    public function getVerifiedMosques(int $perPage = 12, ?string $search = null, ?string $province = null): LengthAwarePaginator
    {
        return Mosque::query()
            ->where('status', MosqueStatus::VERIFIED)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'ilike', "%{$search}%")
                        ->orWhere('city', 'ilike', "%{$search}%")
                        ->orWhere('district', 'ilike', "%{$search}%");
                });
            })
            ->when($province, fn($q) => $q->where('province', $province))
            ->with(['profile', 'facilities'])
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Mosque
    {
        return Mosque::where('slug', $slug)
            ->with(['profile', 'facilities', 'staff.user', 'prayerSetting'])
            ->firstOrFail();
    }

    public function createMosque(array $data): Mosque
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $mosque = Mosque::create($data);

        MosqueProfile::create([
            'mosque_id' => $mosque->id,
            'history' => $data['history'] ?? null,
            'vision' => $data['vision'] ?? null,
            'mission' => $data['mission'] ?? [],
            'capacity' => $data['capacity'] ?? 0,
        ]);

        return $mosque;
    }

    public function updateProfile(Mosque $mosque, array $data): Mosque
    {
        $mosque->update($data);

        if ($mosque->profile) {
            $mosque->profile->update($data);
        } else {
            MosqueProfile::create(array_merge($data, ['mosque_id' => $mosque->id]));
        }

        return $mosque->fresh(['profile']);
    }

    public function addStaff(Mosque $mosque, array $data): MosqueStaff
    {
        $data['mosque_id'] = $mosque->id;
        return MosqueStaff::create($data);
    }

    public function addFacility(Mosque $mosque, array $data): MosqueFacility
    {
        $data['mosque_id'] = $mosque->id;
        return MosqueFacility::create($data);
    }
}
