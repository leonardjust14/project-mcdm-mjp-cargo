<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeValue;
use App\Models\Criteria;
use Illuminate\Http\Request;

class AlternativeController extends Controller
{
    public function index()
    {
        $criteria = Criteria::orderBy('code')->get();
        $alternatives = Alternative::with(['values.criteria'])
            ->orderBy('name')
            ->get();

        return view('alternatives.index', [
            'criteria' => $criteria,
            'alternatives' => $alternatives,
        ]);
    }

    public function create()
    {
        $criteria = Criteria::orderBy('code')->get();

        return view('alternatives.create', [
            'criteria' => $criteria,
        ]);
    }

    public function store(Request $request)
    {
        $criteria = Criteria::orderBy('code')->get();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
            'values' => ['required', 'array'],
            'values.*' => ['required', 'numeric'],
        ]);

        $alternative = Alternative::create([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        foreach ($criteria as $criterion) {
            $value = $validated['values'][$criterion->id] ?? 0;
            AlternativeValue::updateOrCreate(
                [
                    'alternative_id' => $alternative->id,
                    'criteria_id' => $criterion->id,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        return redirect()->route('alternatives.index')
            ->with('status', 'Alternative added successfully.');
    }

    public function edit(Alternative $alternative)
    {
        $criteria = Criteria::orderBy('code')->get();
        $alternative->load('values');

        return view('alternatives.edit', [
            'criteria' => $criteria,
            'alternative' => $alternative,
        ]);
    }

    public function update(Request $request, Alternative $alternative)
    {
        $criteria = Criteria::orderBy('code')->get();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
            'values' => ['required', 'array'],
            'values.*' => ['required', 'numeric'],
        ]);

        $alternative->update([
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        foreach ($criteria as $criterion) {
            $value = $validated['values'][$criterion->id] ?? 0;
            AlternativeValue::updateOrCreate(
                [
                    'alternative_id' => $alternative->id,
                    'criteria_id' => $criterion->id,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        return redirect()->route('alternatives.index')
            ->with('status', 'Alternative updated successfully.');
    }

    public function destroy(Alternative $alternative)
    {
        $alternative->delete();

        return redirect()->route('alternatives.index')
            ->with('status', 'Alternative deleted successfully.');
    }
}
