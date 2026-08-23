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
            'phone' => ['required', 'string'],
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

        $employee = $passcode->employee;

        if (! $employee || $employee->phone !== $data['phone']) {
            return response()->json([
                'valid' => false,
                'message' => 'Passcode dan nomor HP tidak sesuai.',
            ], 422);
        }

        $passcode->update(['last_used_at' => now()]);

        return response()->json([
            'valid' => true,
            'label' => $employee->name,
        ]);
    }
}
