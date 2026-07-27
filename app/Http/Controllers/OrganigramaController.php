<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class OrganigramaController extends Controller
{
    public function __invoke(): View
    {
        $members = User::query()
            ->where('is_active', true)
            ->where('show_in_org_chart', true)
            ->orderBy('org_chart_order')
            ->orderBy('name')
            ->get();

        return view('organigrama.index', [
            'ceo' => $members->firstWhere('org_chart_level', 'ceo'),
            'directors' => $members->where('org_chart_level', 'director')->values(),
            'support' => $members->where('org_chart_level', 'support')->values(),
        ]);
    }
}
