<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
use DB;
use App\Helpers\Helper;
use App\Models\VModule;
use App\Models\VPermission;
use App\Models\VRole;
use Illuminate\Support\Facades\Auth;
use Dotunj\LaraTwilio\Facades\LaraTwilio;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        if (Helper::getFeatureUses('permission') == false) {
            return abort(404);
        }
        $user = auth()->user();
        if ($user->hasModule('permission') == true) {
            if ($request->input('search')) {
                $query = $request->all();
                $searchQuery = $request->input('searchtext');
                $user = User::where('directory_id', Auth::user()->directory_id)->where('parent_id', '!=', 0)->where('role_id', 1)->orderBy('id', 'desc')->orWhere('email', 'like', '%' . $searchQuery . '%')->paginate(20);
                $data = isset($searchQuery) ? $request->input('searchtext') : "";
                return view('account.company.permision.index', ['user' => $user, 'data' => $data, 'query' => $query]);
            } else {
                $user = User::where('directory_id', Auth::user()->directory_id)->where('parent_id', '!=', 0)->where('role_id', 1)->orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery) ? $request->input('searchtext') : "";
                return view('account.company.permision.index', ['user' => $user, 'data' => $data]);
            }
        } else {
            return abort(404);
        }
    }

    public function create()
    {
        if (Helper::getFeatureUses('permission') == false) {
            return abort(404);
        }
        $role = VRole::where('vid', Auth::user()->id)->where('id', '!=', 1)->get();
        $permissions = VPermission::orderBy('id', 'desc')->get();
        $modules = VModule::where('id', '!=', 11)->orderBy('id', 'desc')->get();
        return view('account.company.permision.create', compact('role', 'modules', 'permissions'));
    }

    public function submit(Request $request)
    {
        
        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);
        $countryCode = session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        $user = new User();
        $user->parent_id = Auth::user()->id;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = 1;
        $user->status = 1;
        $user->directory_id = Auth::user()->directory_id;
        $user->profile_status = 1;
        $user->save();
        $user->roles()->attach($request->role);
        if($request->modules){
            foreach ($request->modules as $mval) {
                $user->modules()->attach($mval);
            }    
        }
        if($request->permissions){
            foreach ($request->permissions as $val) {
                $user->permissions()->attach($val);
            }
        }
        $msg = "Permisions Added Successfully! ";
        if (!empty($countryCodeData->phonecode)) {
            $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . '7678409612', $msg);
        } else {
            $sendSms = LaraTwilio::notify('+' . '91' . '7678409612', $msg);
        }
        return redirect('company/permission-edit/' . $user->id)->with('msg', 'Permisions Added Successfully!');
    }

    public function edit($id = 0)
    {
        if (Helper::getFeatureUses('permission') == false) {
            return abort(404);
        }
        $role = VRole::where('vid', Auth::user()->id)->where('id', '!=', 1)->get();
        $data = User::where('directory_id', Auth::user()->directory_id)->with('roles')->with('modules')->with('permissions')->findOrFail($id);
        $permissions = VPermission::orderBy('id', 'desc')->get();
        $modules = VModule::where('id', '!=', 11)->orderBy('id', 'desc')->get();
        return view('account.company.permision.edit', compact('data', 'modules', 'permissions', 'role'));
    }

    public function getModulePermissionByRoleId($roleid)
    {
        $rdata = VRole::with('modules')->with('permissions')->findOrFail($roleid);
        echo json_encode($rdata);
        exit;
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email'
        ]);
        $data = User::find($id);
        $data->parent_id = Auth::user()->id;
        $data->name = $request->name;
        $data->email = $request->email;
        if (!empty($request->password)) {
            $data->password = Hash::make($request->password);
        }
        $data->role_id = 1;
        $data->status = 1;
        $data->directory_id = Auth::user()->directory_id;
        $data->save();
        $data->roles()->newPivotStatement()->where('user_id', '=', $id)->update(array('role_id' => $request->role));

        if($request->permissions){
            DB::table('users_permissions')->where(['user_id' => $id])->delete();
            foreach ($request->permissions as $val) {
                $data->permissions()->attach($val);
            }
        }
        if($request->permissions){
            DB::table('users_modules')->where(['user_id' => $id])->delete();
            foreach ($request->modules as $mval) {
                $data->modules()->attach($mval);
            }
        }
        
        return redirect('company/permission-edit/' . $id)->with('msg', 'Permisions has been updated');
    }

    public function changeStatus(Request $request)
    {
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect('company/permission-edit/permission')->with('msg', 'User deleted successfully');
    }
}
