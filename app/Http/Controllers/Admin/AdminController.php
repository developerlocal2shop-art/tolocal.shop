<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
use DB;
use App\Helpers\Helper;
use App\Models\Admin;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasModule('permission_managment') == true) {

            if ($request->input('search')) {
                $query = $request->all();
                $searchQuery = $request->input('searchtext');
                $user = Admin::orderBy('id', 'desc')->orWhere('email', 'like', '%' . $searchQuery . '%')->paginate(20);
                $data = isset($searchQuery) ? $request->input('searchtext') : "";
                return view('admin.permision.index', ['user' => $user, 'data' => $data, 'query' => $query]);
            } else {
                $user = Admin::orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery) ? $request->input('searchtext') : "";
                return view('admin.permision.index', ['user' => $user, 'data' => $data]);
            }
        } else {
            return abort(404);
        }
    }

    public function create()
    {
        $role = Role::where('id', '!=', 1)->get();
        $permissions = Permission::orderBy('id', 'desc')->get();
        $modules = Module::where('id', '!=', 19)->orderBy('id', 'desc')->get();
        return view('admin.permision.create', compact('role', 'modules', 'permissions'));
    }

    public function submit(Request $request)
    {

        $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email|unique:admin',
            'password' => 'required|string|min:6'
        ]);
        $user = new Admin();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = 1;
        $user->save();
        $user->roles()->attach($request->role);
        foreach ($request->modules as $mval) {
            $user->modules()->attach($mval);
        }
        foreach ($request->permissions as $val) {
            $user->permissions()->attach($val);
        }
        return redirect('admin/permission-edit/' . $user->id)->with('msg', 'Permisions Added Successfully!');
    }

    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasRole('administrator')) {
            $role = Role::where('id', '!=', 1)->get();
            $data = Admin::with('roles')->with('modules')->with('permissions')->findOrFail($id);
            $permissions = Permission::orderBy('id', 'desc')->get();
            $modules = Module::where('id', '!=', 19)->orderBy('id', 'desc')->get();
            return view('admin.permision.edit', compact('data', 'modules', 'permissions', 'role'));
        } else {
            return abort(404);
        }
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'email' => 'required|email'
        ]);
        $data = Admin::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        if (!empty($request->password)) {
            $data->password = Hash::make($request->password);
        }
        $data->role_id = 1;
        $data->save();
        $data->roles()->newPivotStatement()->where('admin_id', '=', $id)->update(array('role_id' => $request->role));
        DB::table('admin_permissions')->where(['admin_id' => $id])->delete();
        foreach ($request->permissions as $val) {
            $data->permissions()->attach($val);
        }
        DB::table('admin_modules')->where(['admin_id' => $id])->delete();
        foreach ($request->modules as $mval) {
            $data->modules()->attach($mval);
        }
        return redirect('admin/permission-edit/' . $id)->with('msg', 'Permisions has been updated');
    }

    public function changeStatus(Request $request)
    {
        $user = Admin::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        Admin::find($id)->delete();
        return redirect('admin/permission')->with('msg', 'User deleted successfully');
    }
}
