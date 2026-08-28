<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function __construct(protected VerificationService $verificationService) {}

    public function verify(Request $request, string $code): View
    {
        $result = $this->verificationService->verifyCode($code, $request);

        return view('public.verification', compact('result', 'code'));
    }
}
