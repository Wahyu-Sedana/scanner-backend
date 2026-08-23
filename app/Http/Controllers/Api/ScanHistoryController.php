<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Passcode;
use App\Models\ScanHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScanHistoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $entries = ScanHistory::query()
            ->with(['passcode:id,label', 'passcode.employee:id,passcode_id,name'])
            ->when($request->string('mode')->isNotEmpty(), fn ($query) => $query->where('mode', $request->string('mode')))
            ->when($request->string('status')->isNotEmpty(), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate($request->integer('per_page', 25));

        return response()->json($entries);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'barcode' => ['required', 'string'],
            'format' => ['nullable', 'string'],
            'mode' => ['required', 'in:member,redeem,event-ticket'],
            'status' => ['required', 'in:success,failed'],
            'reason' => ['nullable', 'string'],
            'passcode' => ['nullable', 'string'],
        ]);

        $passcodeId = null;
        if (! empty($data['passcode'])) {
            $passcodeId = Passcode::where('code', $data['passcode'])->value('id');
        }

        $entry = ScanHistory::create([
            'barcode' => $data['barcode'],
            'format' => $data['format'] ?? null,
            'mode' => $data['mode'],
            'status' => $data['status'],
            'reason' => $data['reason'] ?? null,
            'passcode_id' => $passcodeId,
        ]);

        return response()->json($entry, 201);
    }
}
