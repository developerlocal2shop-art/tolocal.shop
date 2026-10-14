<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliateLink;
use App\Models\Product;
use App\Models\VendorAffiliateSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{

    public function dashboard(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $affiliate = Affiliate::where('user_id', Auth::id())->first();

        if (!$affiliate) {
            return redirect()->route('affiliate.register')
                ->with('info', 'Please register for the affiliate program to access your dashboard.');
        }

        $clicks = $affiliate->clicks()->count();
        $conversions = $affiliate->conversions();
        $totalConversions = $conversions->count();
        $pendingConversions = $conversions->where('status', 'pending')->count();
        $approvedConversions = $conversions->where('status', 'approved')->get();
        $paidEarnings = $approvedConversions->sum('paid_amount');

        $walletBalance = 0;
        if (auth()->check() && auth()->user()->affiliateWallet) {
            $walletBalance = (float) auth()->user()->affiliateWallet->balance;
        }

        $stats = [
            'program_status' => ucfirst($affiliate->status ?? 'inactive'),
            'commission_type' => $affiliate->commission_type === 'fixed' ? 'Fixed' : 'Percentage',
            'commission_rate' => (float) ($affiliate->commission_rate ?? 0),
            'clicks' => $clicks,
            'qualified_orders' => $totalConversions,
            'pending_payouts' => $pendingConversions,
            'wallet_balance' => $walletBalance,
            'total_earnings' => round($paidEarnings + $walletBalance, 2),
            'affiliate_code' => $affiliate->affiliate_code,
        ];

        $recentConversions = $affiliate->conversions()->latest()->take(5)->get();
        $recentClicks = $affiliate->clicks()->latest()->take(5)->get();
        $products = collect();
        $affiliateLinks = collect();

        if ($affiliate->status === 'approved') {
            $enabledVendorIds = VendorAffiliateSetting::where('enabled', true)->pluck('vendor_id');

            $products = Product::query()
                ->where('status', 'Active')
                ->where('deleted', '0')
                ->whereIn('directory_id', $enabledVendorIds)
                ->when($request->filled('product_search'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->input('product_search') . '%');
                })
                ->orderBy('name')
                ->paginate(15, ['*'], 'products_page')
                ->appends($request->only('product_search'));

            $affiliateLinks = $affiliate->links()
                ->with('product')
                ->latest()
                ->take(10)
                ->get();
        }

        return view('account.affiliate.dashboard', compact(
            'affiliate',
            'stats',
            'recentConversions',
            'recentClicks',
            'products',
            'affiliateLinks'
        ));
    }

    public function productSuggestions(Request $request)
    {
        $affiliate = Affiliate::where('user_id', Auth::id())->firstOrFail();
        abort_unless($affiliate->status === 'approved', 403);

        $search = trim((string) $request->query('q', ''));
        if (mb_strlen($search) < 2) {
            return response()->json([]);
        }

        $enabledVendorIds = VendorAffiliateSetting::where('enabled', true)->pluck('vendor_id');
        $products = Product::query()
            ->select('id', 'name')
            ->where('status', 'Active')
            ->where('deleted', '0')
            ->whereIn('directory_id', $enabledVendorIds)
            ->where('name', 'like', '%' . $search . '%')
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function storeLink(Request $request)
    {
        $affiliate = Affiliate::where('user_id', Auth::id())->firstOrFail();
        abort_unless($affiliate->status === 'approved', 403);

        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:product,id'],
        ]);

        $enabledVendorIds = VendorAffiliateSetting::where('enabled', true)->pluck('vendor_id');
        $product = Product::query()
            ->where('id', $validated['product_id'])
            ->where('status', 'Active')
            ->where('deleted', '0')
            ->whereIn('directory_id', $enabledVendorIds)
            ->firstOrFail();

        do {
            $code = Str::random(16);
        } while (AffiliateLink::where('code', $code)->exists());

        $link = $affiliate->links()->create([
            'product_id' => $product->id,
            'vendor_id' => $product->directory_id,
            'code' => $code,
            'url' => route('affiliate.link.redirect', ['code' => $code]),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['url' => $link->url]);
        }

        return redirect()->route('affiliate.dashboard', $request->only('product_search'))
            ->with('success', 'Your affiliate link is ready.')
            ->with('generated_link', $link->url);
    }

    public function redirectLink(Request $request, $code)
    {
        $link = AffiliateLink::where('code', $code)->firstOrFail();
        abort_unless(optional($link->affiliate)->status === 'approved', 404);
        abort_unless(VendorAffiliateSetting::where('vendor_id', $link->vendor_id)->where('enabled', true)->exists(), 404);

        $product = Product::query()
            ->where('id', $link->product_id)
            ->where('directory_id', $link->vendor_id)
            ->where('status', 'Active')
            ->where('deleted', '0')
            ->firstOrFail();

        AffiliateClick::create([
            'affiliate_id' => $link->affiliate_id,
            'affiliate_link_id' => $link->id,
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId(),
            'created_at' => now(),
        ]);

        $link->increment('clicks');
        $request->session()->put([
            'affiliate_id' => $link->affiliate_id,
            'affiliate_link_id' => $link->id,
        ]);

        return redirect()->route('shop-details', [
            'id' => $product->id,
            'slug' => $product->seo_url,
        ]);
    }

    public function create()
    {

        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $affiliate = Affiliate::where('user_id', Auth::id())->first();
        return view('account.affiliate.register', compact('affiliate'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'store_type' => ['required', 'in:online,store_only'],
            'commission_type' => ['required', 'in:percentage,fixed'],
        ]);

        $user = auth()->user();
        // Prevent duplicate affiliate registration
        $existingAffiliate = Affiliate::where('user_id',$user->id)->first();
        if ($existingAffiliate) {
            return redirect()->route('account')->with('info','You are already registered as an affiliate.');
        }

        $affiliate = Affiliate::firstOrNew(['user_id' => Auth::id()]);

        $affiliate->store_type = $validated['store_type'];
        $affiliate->commission_type = $validated['commission_type'];
        $affiliate->commission_rate = 0;

        if (empty($affiliate->affiliate_code)) {
            $affiliate->affiliate_code = $this->generateAffiliateCode();
        }
        $affiliate->status = 'pending';
        $affiliate->save();

        return redirect()->route('account')->with('success', 'Affiliate registration submitted successfully.Please wait for approval.');

    }

    protected function generateAffiliateCode(): string
    {
        do {
            $code = 'AFF-' . strtoupper(Str::random(8));
        } while (Affiliate::where('affiliate_code', $code)->exists());

        return $code;
    }
}
