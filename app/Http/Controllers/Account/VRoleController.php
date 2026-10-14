<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\VModule;
use App\Models\VPermission;
use App\Models\VRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VRoleController extends Controller
{
    public function index(Request $request)
    {
        if (Helper::getFeatureUses('role') == false) {
            return abort(404);
        }

        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $role = VRole::where('vid', Auth::user()->id)->orderBy('id', 'desc')->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('account.company.role.index', ['role' => $role, 'data' => $data, 'query' => $query]);
        } else {
            $role = VRole::where('vid', Auth::user()->id)->where('id', '!=', 1)->orderBy('id', 'desc')->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('account.company.role.index', ['role' => $role, 'data' => $data]);
        }
    }

    public function create()
    {
        if (Helper::getFeatureUses('role') == false) {
            return abort(404);
        }
        $permissions = VPermission::orderBy('id', 'desc')->get();
        $modules = VModule::where('id', '!=', 11)->orderBy('id', 'desc')->get();
        return view('account.company.role.create', compact('modules', 'permissions'));
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
        ]);
        $data = new VRole;
        $data->vid = Auth::user()->id;
        $data->name = $request->input('name');
        $data->slug = Str::slug($request->input('name'));
        $data->save();
        foreach ($request->modules as $mval) {
            $data->modules()->attach($mval);
        }
        foreach ($request->permissions as $val) {
            $data->permissions()->attach($val);
        }
        return redirect('company/role')->with('msg', 'Role Added Successfully!');
    }

    public function edit($id = 0)
    {
        if (Helper::getFeatureUses('role') == false) {
            return abort(404);
        }
        $data = VRole::with('modules')->with('permissions')->findOrFail($id);
        $permissions = VPermission::orderBy('id', 'desc')->get();
        $modules = VModule::where('id', '!=', 11)->orderBy('id', 'desc')->get();
        return view('account.company.role.edit', compact('data', 'modules', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required'
        ]);
        $data = VRole::find($id);
        $data->vid = Auth::user()->id;
        $data->name = $request->input('name');
        $data->slug = Str::slug($request->input('name'));
        $data->save();
        DB::table('v_roles_permissions')->where(['v_role_id' => $id])->delete();
        foreach ($request->permissions as $val) {
            $data->permissions()->attach($val);
        }
        DB::table('v_role_modules')->where(['role_id' => $id])->delete();
        foreach ($request->modules as $mval) {
            $data->modules()->attach($mval);
        }
        return redirect('company/role')->with('msg', 'Role permission has been updated but you need to reupdate staf role for reflect changes. <a href="' . url('company/permission') . '"> Staff permissions </a>');
    }

    public function changeStatus(Request $request)
    {
        $user = VRole::find($request->id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        VRole::find($id)->delete();
        return redirect('company/role')->with('msg', 'Deleted successfully');
    }
}
