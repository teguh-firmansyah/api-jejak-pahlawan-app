<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HeroNoteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notes = $request->user()->heroNotes()
            ->with('era:id,name')
            ->when(
                $request->filled('era_id'),
                fn($query) => $query->where('era_id', $request->query('era_id'))
            )
            ->latest('id')
            ->get();

        return response()->json(['data' => $notes]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules($request));

        $note = $request->user()->heroNotes()->create($data);

        return response()->json([
            'message' => 'Catatan pahlawan berhasil ditambahkan.',
            'data' => $note->load('era:id,name'),
        ], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $note = $request->user()->heroNotes()->with('era:id,name')->findOrFail($id);

        return response()->json(['data' => $note]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $note = $request->user()->heroNotes()->findOrFail($id);

        $note->update($request->validate($this->rules($request)));

        return response()->json([
            'message' => 'Catatan pahlawan berhasil diperbarui.',
            'data' => $note->load('era:id,name'),
        ]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $note = $request->user()->heroNotes()->findOrFail($id);

        $note->delete();

        return response()->json(['message' => 'Catatan pahlawan berhasil dihapus.']);
    }

    private function rules(Request $request): array
    {
        return [
            'era_id' => [
                'required',
                'integer',
                Rule::exists('eras', 'id')->where('user_id', $request->user()->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'birth_year' => ['nullable', 'string', 'max:50'],
            'death_year' => ['nullable', 'string', 'max:50'],
            'ascension_year' => ['nullable', 'string', 'max:50'],
            'summary' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
