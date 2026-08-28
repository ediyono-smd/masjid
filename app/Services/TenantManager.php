<?php

namespace App\Services;

use App\Models\Mosque;
use Illuminate\Support\Facades\Auth;

class TenantManager
{
    protected ?string $mosqueId = null;
    protected ?Mosque $mosque = null;

    public function setMosqueId(?string $mosqueId): void
    {
        $this->mosqueId = $mosqueId;
        $this->mosque = null;
    }

    public function getMosqueId(): ?string
    {
        if ($this->mosqueId !== null) {
            return $this->mosqueId;
        }

        if (Auth::check() && Auth::user()->mosque_id) {
            return Auth::user()->mosque_id;
        }

        return session('active_mosque_id');
    }

    public function getMosque(): ?Mosque
    {
        $id = $this->getMosqueId();
        if (!$id) {
            return null;
        }

        if (!$this->mosque || $this->mosque->id !== $id) {
            $this->mosque = Mosque::find($id);
        }

        return $this->mosque;
    }

    public function isSuperAdmin(): bool
    {
        return Auth::check() && Auth::user()->hasRole('SUPER_ADMIN');
    }
}
