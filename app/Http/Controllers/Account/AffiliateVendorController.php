<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\VendorAffiliateSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateVendorController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $setting = VendorAffiliateSetting::firstOrNew([
            'vendor_id' => $user->directory_id,
        ]);

        $stats = [
            'program_status' => $setting->enabled ? 'Enabled' : 'Disabled',
            'commission_type' => ucfirst($setting->commission_type ?? 'percentage'),
            'commission_rate' => number_format((float) ($setting->commission_rate ?? 0), 2),
            'clicks' => 0,
            'orders' => 0,
            'pending' => 0,
            'earnings' => 0,
        ];

        return view('account.company.affiliate.dashboard', compact('setting', 'stats'));
    }

    public function edit()
    {
        $user = Auth::user();
        $setting = VendorAffiliateSetting::firstOrNew([
            'vendor_id' => $user->directory_id,
        ]);

        return view('account.company.affiliate.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'commission_type' => ['required', 'in:percentage,fixed'],
            'commission_rate' => ['required', 'numeric', 'min:0'],
        ]);

        $setting = VendorAffiliateSetting::firstOrNew([
            'vendor_id' => $user->directory_id,
        ]);

        $setting->fill([
            'vendor_id' => $user->directory_id,
            'enabled' => (bool) $validated['enabled'],
            'commission_type' => $validated['commission_type'],
            'commission_rate' => $validated['commission_rate'],
        ]);

        $setting->save();

        return redirect()->route('vendor.affiliate.settings')->with('success_msg', 'Affiliate settings updated successfully.');
    }
}


