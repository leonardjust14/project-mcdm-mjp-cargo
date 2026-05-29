<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Services\TopsisService;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

        $top = $result && !empty($result['ranking']) ? $result['ranking'][0] : null;

        return view('dashboard', [
            'criteria' => $criteria,
            'alternatives' => $alternatives,
            'top' => $top,
            'result' => $result,
        ]);
    }
}
