<?php

namespace App\Http\Controllers\Admin\Claim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use Auth;
use App\Models\Claim;

class ClaimController extends Controller
{

    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['claim'] = DB::table('claim')->join('directory', 'directory.id', '=', 'claim.directory_id')
                ->select('claim.*', 'directory.title')
                ->where('title', 'like', '%' . $searchQuery . '%')->orwhere('subject', 'like', '%' . $searchQuery . '%');
            $claim =    $data['claim']->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.claim.index', ['claim' => $claim, 'textsearch' => $searchtext, 'query' => $query]);
        } else {
            $claim = DB::table('claim')->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.claim.index', ['claim' => $claim, 'textsearch' => $searchtext]);
        }
    }

    public function view($id = 0)
    {
        $claimData = Claim::findOrFail($id);
        $viewData = DB::table('claim')->join('directory_contact', 'directory_contact.directory_id', '=', 'claim.directory_id')
            ->select('claim.*', 'directory_contact.email', 'directory_contact.phone')->where('claim.id', $claimData->id)->get();
        Claim::where('directory_id', $claimData->directory_id)->update(array('status' => '0'));
        return view('admin.claim.view', ['claimData' => $claimData, 'viewData' => $viewData]);
    }
}
