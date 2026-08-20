<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Passcode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasscodeController extends Controller
{
    public function validateCode(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
        ]);

        $passcode = Passcode::where('code', $data['code'])
            ->where('is_active', true)
            ->first();

        if (! $passcode) {
            return response()->json([
                'valid' => false,
                'message' => 'Passcode tidak valid atau sudah tidak aktif.',
            ], 422);
        }

        $passcode->update(['last_used_at' => now()]);

        return response()->json([
            'valid' => true,
            'label' => $passcode->label,
        ]);
    }
}
