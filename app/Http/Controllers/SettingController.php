<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => [
                'company_name' => Setting::getValue('company_name', 'Wini'),
                'currency' => Setting::getValue('currency', 'USD'),
                'report_footer' => Setting::getValue('report_footer', 'Producto sostenible'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:120'],
            'currency' => ['required', 'string', 'max:10'],
            'report_footer' => ['nullable', 'string', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Configuracion actualizada correctamente.');
    }
}
