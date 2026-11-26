<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EraController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $eras = $request->user()->eras()
            ->withCount('heroNotes')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $eras]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules($request));

        $era = $request->user()->eras()->create($data);

        return response()->json([
            'message' => 'Era berhasil ditambahkan.',
            'data' => $era,
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $era = $request->user()->eras()->withCount('heroNotes')->findOrFail($id);

        return response()->json(['data' => $era]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $era = $request->user()->eras()->findOrFail($id);

        $era->update($request->validate($this->rules($request, $era->id)));

        return response()->json([
            'message' => 'Era berhasil diperbarui.',
            'data' => $era,
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $era = $request->user()->eras()->findOrFail($id);

        if ($era->heroNotes()->exists()) {
            return response()->json([
                'message' => 'Era tidak bisa dihapus karena masih memiliki catatan pahlawan.',
            ], 409);
        }

        $era->delete();

        return response()->json(['message' => 'Era berhasil dihapus.']);
    }

    private function rules(Request $request, ?int $ignoreId = null): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('eras', 'name')
                    ->where('user_id', $request->user()->id)
                    ->ignore($ignoreId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
