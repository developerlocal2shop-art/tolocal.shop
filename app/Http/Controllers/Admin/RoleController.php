<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
use DB;
use App\Helpers\Helper;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'roles';
            $data['columns'] = ['name'];
            $role = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.role.index', ['role' => $role, 'data' => $data, 'query' => $query]);
        } else {
            $role = Role::orderBy('id', 'desc')->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.role.index', ['role' => $role, 'data' => $data]);
        }
    }

    public function create()
    {
        return view('admin.role.create');
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
        ]);
        $data = new Role;
        $data->name = $request->input('name');
        $data->slug = Str::slug($request->input('name'));
        $data->save();
        return redirect('admin/role')->with('msg', 'Role Added Successfully!');
    }

    public function edit($id = 0)
    {
        $singleUserData = Role::findOrFail($id);
        return view('admin.role.edit', compact('singleUserData'));
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required'
        ]);
        $data = Role::find($id);
        $data->name = $request->input('name');
        $data->slug = Str::slug($request->input('name'));
        $data->save();
        return redirect('admin/role')->with('msg', 'Role has been updated');
    }

    public function changeStatus(Request $request)
    {
        $user = Role::find($request->id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        Role::find($id)->delete();
        return redirect('admin/role')->with('msg', 'Deleted successfully');
    }
}
