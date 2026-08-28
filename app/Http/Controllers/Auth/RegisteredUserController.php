<?php

namespace App\Http\Controllers\Auth;

use App\Enums\MosqueStatus;
use App\Enums\MosqueType;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\Mosque;
use App\Models\MosqueProfile;
use App\Models\PrayerSetting;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(protected AuditLogService $auditService) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mosque_name' => ['required', 'string', 'max:255'],
            'mosque_type' => ['required', 'string'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'district' => ['required', 'string', 'max:100'],
            'village' => ['required', 'string', 'max:100'],
            'address_line' => ['required', 'string'],
            'admin_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $slug = Str::slug($request->mosque_name);
            if (Mosque::where('slug', $slug)->exists()) {
                $slug .= '-' . strtolower(Str::random(4));
            }

            $mosque = Mosque::create([
                'name' => $request->mosque_name,
                'slug' => $slug,
                'type' => MosqueType::from($request->mosque_type),
                'status' => MosqueStatus::VERIFIED,
                'address_line' => $request->address_line,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'village' => $request->village,
                'email' => $request->email,
                'phone' => $request->phone_number,
                'verified_at' => now(),
            ]);

            MosqueProfile::create([
                'mosque_id' => $mosque->id,
                'history' => 'Profil masjid resmi terdaftar di platform MASJID INDONESIA.',
                'capacity' => 500,
            ]);

            PrayerSetting::create([
                'mosque_id' => $mosque->id,
                'calculation_method' => 'KEMENAG',
            ]);

            $freePlan = SubscriptionPlan::where('name', 'FREE')->first();
            if ($freePlan) {
                Subscription::create([
                    'mosque_id' => $mosque->id,
                    'plan_id' => $freePlan->id,
                    'status' => 'ACTIVE',
                    'starts_at' => now(),
                    'ends_at' => now()->addYear(),
                ]);
            }

            $user = User::create([
                'mosque_id' => $mosque->id,
                'name' => $request->admin_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]);

            $user->assignRole(RoleEnum::MOSQUE_ADMIN->value);

            return $user;
        });

        Auth::login($user);

        $this->auditService->log('REGISTER_MOSQUE', $user, null, ['mosque' => $request->mosque_name]);

        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang! Masjid dan akun admin Anda telah aktif.');
    }
}
