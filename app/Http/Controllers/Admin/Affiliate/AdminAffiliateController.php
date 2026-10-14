<?php

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Support\Facades\DB;

class AdminAffiliateController extends Controller
{
    /**
     * Display affiliate applications.
     */
    public function index()
    {
        $affiliates = Affiliate::with('user')->latest()->paginate(20);
        return view( 'admin.affiliates.index',compact('affiliates'));
    }

    /**
     * Approve affiliate.
     */
    public function approve(Affiliate $affiliate) {
        DB::transaction(function () use ($affiliate) {

            $affiliate = Affiliate::whereKey($affiliate->id)->lockForUpdate()->firstOrFail();
            if ($affiliate->status === 'approved') {
                return;
            }
            $affiliate->status = 'approved';
            $affiliate->save();
        });
        return back()->with('success','Affiliate approved successfully.');
    }

    /**
     * Reject affiliate.
     */
    public function reject( Affiliate $affiliate) {
        $affiliate->update([
            'status' => 'rejected',
        ]);
        return back()->with('success','Affiliate application rejected.');
    }
}
