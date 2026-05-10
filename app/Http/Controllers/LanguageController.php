<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    public function __invoke(string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, ['es', 'en'], true), 404);

        session(['locale' => $locale]);

        return back();
    }
}
