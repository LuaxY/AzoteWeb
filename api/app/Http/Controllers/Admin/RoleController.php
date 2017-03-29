<?php

namespace App\Http\Controllers\Admin;

use App\Announce;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\World;
use App\Role;
use Illuminate\Validation\Rule;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Kamaln7\Toastr\Facades\Toastr;

class RoleController extends Controller
{
    public function index()
    {
        $roles =  Role::orderBy('id', 'asc')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $id = Role::max('id');
        $id = (int)$id + 1;
        return view('admin.roles.create', compact('id'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Role::$rules['store']);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // INSERT INTO DB //
        $role = new Role;
        if($request->id)
            $role->id = $request->id;
        $role->label = $request->name;
        $role->name  = strtolower($request->name);
        $role->save();

        Toastr::success('Role created', $title = null, $options = []);
        return redirect(route('admin.roles'));
    }

    public function destroy($roleid, Request $request)
    {
        $role = Role::findOrFail($request->id);

        if($role->users()->count() > 0)
        {
            return response()->json(['roleid' => ['0' => 'Can\'t delete a role with users']], 422);
        }

        $role->delete();
        return response()->json([], 200);
    }

    public function edit($roleid)
    {
        $role = Role::findOrFail($roleid);
        return view('admin.roles.edit', compact('role'));
    }

    public function update($roleid, Request $request)
    {
        $role = Role::findOrFail($roleid);
        $validator = Validator::make($request->all(), [
                        'id' => [
                            'required',
                            Rule::unique('roles')->ignore($role->id, 'id'),
                        ],
                        'name'               => 'required|min:3|max:20|alpha',
                    ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $role->permissions()->newPivotStatement()->where('role_id', '=', $roleid)->update(array('role_id' => $request->id));
        $role->users()->update(['role_id' => $request->id]);
        $role->update(['id' => $request->id, 'label' => $request->name, 'name' => strtolower($request->name)]);

        Toastr::success('Role updated', $title = null, $options = []);

        return redirect(route('admin.roles'));
    }
}
