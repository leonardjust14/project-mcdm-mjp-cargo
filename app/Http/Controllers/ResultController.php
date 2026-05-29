<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Services\TopsisService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request, TopsisService $topsis)
    {
        $criteria = Criteria::orderBy('code')->get();
        $alternatives = Alternative::with('values')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $result = $criteria->isEmpty() || $alternatives->isEmpty()
            ? null
            : $topsis->calculate($alternatives, $criteria);

        return view('results.index', [
            'criteria' => $criteria,
            'alternatives' => $alternatives,
            'result' => $result,
        ]);
    }
}
