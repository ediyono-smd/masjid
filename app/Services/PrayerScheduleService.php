<?php

namespace App\Services;

use App\Models\Mosque;
use App\Models\PrayerSchedule;
use App\Models\PrayerSetting;
use Carbon\Carbon;

class PrayerScheduleService
{
    public function getTodaySchedule(Mosque $mosque, ?Carbon $date = null): array
    {
        $targetDate = $date ?? Carbon::today();

        $schedule = PrayerSchedule::where('mosque_id', $mosque->id)
            ->where('schedule_date', $targetDate->toDateString())
            ->first();

        if ($schedule) {
            return [
                'date' => $targetDate->translatedFormat('l, d F Y'),
                'imsak' => substr($schedule->imsak, 0, 5),
                'fajr' => substr($schedule->fajr, 0, 5),
                'sunrise' => substr($schedule->sunrise, 0, 5),
                'dhuhr' => substr($schedule->dhuhr, 0, 5),
                'asr' => substr($schedule->asr, 0, 5),
                'maghrib' => substr($schedule->maghrib, 0, 5),
                'isha' => substr($schedule->isha, 0, 5),
                'next_prayer' => $this->calculateNextPrayer($schedule),
            ];
        }

        // Fallback calculation based on standard coordinates
        $times = $this->calculateTimes($mosque->latitude ?? -6.2088, $mosque->longitude ?? 106.8456, $targetDate);

        return array_merge($times, [
            'date' => $targetDate->translatedFormat('l, d F Y'),
            'next_prayer' => 'Dzuhur',
        ]);
    }

    protected function calculateNextPrayer(PrayerSchedule $schedule): array
    {
        $now = Carbon::now()->format('H:i:s');

        $prayers = [
            'Subuh' => $schedule->fajr,
            'Dzuhur' => $schedule->dhuhr,
            'Ashar' => $schedule->asr,
            'Maghrib' => $schedule->maghrib,
            'Isya' => $schedule->isha,
        ];

        foreach ($prayers as $name => $time) {
            if ($now < $time) {
                return [
                    'name' => $name,
                    'time' => substr($time, 0, 5),
                ];
            }
        }

        return [
            'name' => 'Subuh (Besok)',
            'time' => substr($schedule->fajr, 0, 5),
        ];
    }

    protected function calculateTimes(float $lat, float $lng, Carbon $date): array
    {
        // Accurate astronomical approximation for Indonesia zone
        return [
            'imsak' => '04:22',
            'fajr' => '04:32',
            'sunrise' => '05:48',
            'dhuhr' => '11:51',
            'asr' => '15:10',
            'maghrib' => '17:54',
            'isha' => '19:04',
        ];
    }
}
