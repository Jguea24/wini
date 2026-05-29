<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => [
                'company_name' => Setting::getValue('company_name', 'Wini'),
                'company_ruc' => Setting::getValue('company_ruc', ''),
                'company_address' => Setting::getValue('company_address', ''),
                'company_phone' => Setting::getValue('company_phone', ''),
                'company_email' => Setting::getValue('company_email', ''),
                'currency' => Setting::getValue('currency', 'USD'),
                'report_footer' => Setting::getValue('report_footer', 'Producto sostenible'),
                'invoice_prefix' => Setting::getValue('invoice_prefix', 'FAC'),
                'invoice_next_number' => Setting::getValue('invoice_next_number', '1'),
                'invoice_tax_rate' => Setting::getValue('invoice_tax_rate', '0'),
                'invoice_signature_path' => Setting::getValue('invoice_signature_path', ''),
                'invoice_signature_name' => Setting::getValue('invoice_signature_name', 'Johnny Grefa'),
                'invoice_signature_role' => Setting::getValue('invoice_signature_role', 'CEO de Wini'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:120'],
            'company_ruc' => ['nullable', 'string', 'max:30'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:30'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'currency' => ['required', 'string', 'max:10'],
            'report_footer' => ['nullable', 'string', 'max:255'],
            'invoice_prefix' => ['required', 'string', 'max:10'],
            'invoice_next_number' => ['required', 'integer', 'min:1', 'max:999999'],
            'invoice_tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'invoice_signature_name' => ['nullable', 'string', 'max:120'],
            'invoice_signature_role' => ['nullable', 'string', 'max:120'],
            'invoice_signature' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ]);

        unset($data['invoice_signature']);

        foreach ($data as $key => $value) {
            Setting::setValue($key, $value);
        }

        if ($request->hasFile('invoice_signature')) {
            $previousPath = Setting::getValue('invoice_signature_path');

            if ($previousPath) {
                Storage::disk('public')->delete($previousPath);
            }

            Setting::setValue(
                'invoice_signature_path',
                $request->file('invoice_signature')->store('signatures', 'public')
            );
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Configuracion actualizada correctamente.');
    }
}
