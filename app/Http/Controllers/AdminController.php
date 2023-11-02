<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Datatables
use yajra\Datatables\Datatables;
// Builder untuk Query Model
use Illuminate\Database\Eloquent\Builder;

// Storage
use Illuminate\Support\Facades\Storage;
// Uploaded File Instance
use Illuminate\Http\UploadedFile;
// Pion Laravel Chunking
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;

// DB
use DB;

class AdminController extends Controller
{
    public function __construct()
    {
        can_access(\Request::path());
    }

    // Halaman
    public function pageResourceManagement()
    {
        return view('admin.resource-management');
    }

    // Request ambil list User
    public function getListUser(Request $request)
    {
        $model = \App\Models\User::query()->select('id', 'name', 'email', 'active');

        return Datatables::of($model)->setRowId('id')->addColumn(
            'is_active',
            function ($record) {
                return view('components.toggle-switch', ['model' => 'User', 'field' => 'active', 'id' => $record->id, 'value' => $record->active]);
            }
        )->addColumn(
            'tools',
            function ($record) {
                return '<button type="button" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Edit User" onclick="editUser(' . $record->id . ')"><i class="fas fa-user-edit"></i> </button>';
            }
        )->rawColumns(['is_active', 'tools'])->toJson();
    }

    // Request untuk Save User
    public function postSaveUser(Request $request)
    {
        $validation = $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            if ($request->has('id')) {
                $user = \App\Models\User::findOrFail($request->id);
            } else {
                $user = new \App\Models\User;
            }
            $user->name = $request->name;
            $user->email = $request->email;
            // check ada perubahan password atau tidak
            if ($user->password != bcrypt($request->password)) {
                $user->password = bcrypt($request->password);
            }

            $user->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to save User.'], 400);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'User not Found.'], 404);
        }

        return response()->json(['message' => 'success'], 200);
    }

    // Request untuk ambil Informasi User
    public function getInfoUser(Request $request)
    {
        $validation = $this->validate($request, [
            'idUser' => 'required'
        ]);

        try {
            $user = \App\Models\User::select('id', 'name', 'email')->findOrFail($request->idUser);

            return response()->json($user, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found.'], 404);
        }
    }

    // Request ambil list role
    public function getListRole(Request $request)
    {
        $model = \App\Models\Role::query()->select('id', 'role', 'desc', 'active');

        return Datatables::of($model)->setRowId('id')->addColumn(
            'is_active',
            function ($record) {
                return view('components.toggle-switch', ['model' => 'Role', 'field' => 'active', 'id' => $record->id, 'value' => $record->active]);
            }
        )->addColumn(
            'tools',
            function ($record) {
                return '<button type="button" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit Role" onclick="editRole(' . $record->id . ')"><i class="fas fa-wrench"></i> </button>
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Mapping Role" onclick="mappingRole(' . $record->id . ', \'' . $record->role . '\')"><i class="fas fa-sitemap"></i> </button>';
            }
        )->rawColumns(['is_active', 'tools'])->toJson();
    }

    // Request untuk Save Role
    public function postSaveRole(Request $request)
    {
        $validation = $this->validate($request, [
            'role' => 'required',
            'desc' => 'required',
        ]);

        try {
            if ($request->has('id')) {
                $role = \App\Models\Role::findOrFail($request->id);
            } else {
                $role = new \App\Models\Role;
            }
            $role->role = $request->role;
            $role->desc = $request->desc;

            $role->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to save Role.'], 400);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not Found.'], 404);
        }

        return response()->json(['message' => 'success'], 200);
    }

    // Request ambil info role
    public function getInfoRole(Request $request)
    {
        $validation = $this->validate($request, [
            'idRole' => 'required'
        ]);

        try {
            $role = \App\Models\Role::select('id', 'role', 'desc')->findOrFail($request->idRole);

            return response()->json($role, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Role not found'], 404);
        }
    }

    // Request untuk ambil list user yang masuk/tidak masuk ke roles
    public function getListUserRoles(Request $request)
    {
        $validation = $this->validate($request, [
            'role_id' => 'required'
        ]);

        $role_id = $request->role_id;

        $model = \App\Models\User::query()->leftJoin('role_users', function ($join) use ($role_id) {
            $join->on('role_users.user_id', '=', 'users.id')->where('role_users.role_id', '=', $role_id);
        })->where('active', '=', '1')->select('users.id', 'users.name', 'role_users.role_id');

        return Datatables::of($model)->setRowId('id')->addColumn(
            'tools',
            function ($record) {
                return '<button type="button" class="btn btn-' . (!is_null($record->role_id) ? 'danger' : 'success') . '"  data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="' . (!is_null($record->role_id) ? 'Remove from' : 'Add to') . ' this role" onclick="mapUserRole(' . $record->id . ', ' . (!is_null($record->role_id) ? 'false' : 'true') . ')"><i class="fas ' . (!is_null($record->role_id) ? 'fa-user-minus' : 'fa-user-plus') . '"></i> </button>';
            }
        )->rawColumns(['tools'])->toJson();
    }

    // Request untuk assign/remove user dari role
    public function postMapUserRole(Request $request)
    {
        $validation = $this->validate($request, [
            'role_id' => 'required',
            'idUser' => 'required',
            'state' => 'required',
        ]);

        try {
            if ($request->state == 'assign') {
                \App\Models\RoleUser::create([
                    'role_id' => $request->role_id,
                    'user_id' => $request->idUser,
                ]);
            } else {
                \App\Models\RoleUser::where('role_id', '=', $request->role_id)->where('user_id', '=', $request->idUser)->delete();
            }
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to ' . ($request->state ? 'add' : 'remove') . ' mapping user role.'], 400);
        }
        return response()->json(['message' => 'success'], 200);
    }

    // Request untuk ambil list menu untuk Treeview
    public function getListMenu(Request $request)
    {
        return response()->json($this->listAllAccess(), 200);
    }

    // Request untuk ambil list menu yang bisa punya child
    public function getListParentMenus(Request $request)
    {
        $data = \App\Models\AccessList::where('active', '=', '1')->where('child', '=', '1')->select('id', 'name')->get();

        return response()->json($data, 200);
    }

    // Request untuk ambil informasi menu
    public function getInfoMenu(Request $request)
    {
        $validation = $this->validate($request, [
            'idMenu' => 'required'
        ]);

        try {
            $data = \App\Models\AccessList::select('id', 'type', 'parent', 'order', 'icon', 'name', 'link', 'child')->findOrFail($request->idMenu);

            return response()->json($data, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Menu not found.'], 404);
        }
    }

    // Request untuk save menu/request
    public function postSaveMenu(Request $request)
    {
        $validation = $this->validate($request, [
            'type' => 'required|in:page,request',
            'parent' => 'required|integer',
            'order' => 'required|integer',
            'icon' => 'required',
            'name' => 'required',
            'link' => 'required',
            'child' => 'required|in:1,0',
            'active' => 'required|in:1,0',
        ]);

        try {
            if ($request->has('id')) {
                $data = \App\Models\AccessList::findOrFail($request->id);
            } else {
                $data = new \App\Models\AccessList;
            }
            $data->type = $request->type;
            $data->parent = $request->parent;
            $data->order = $request->order;
            $data->icon = $request->icon;
            $data->name = $request->name;
            $data->link = $request->link;
            $data->child = $request->child;
            $data->active = $request->active;

            $data->save();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to save Menu/Request.'], 400);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Menu/Request not Found.'], 404);
        }

        return response()->json(['message' => 'success'], 200);
    }

    // Request untuk mendapatkan list menu berserta akses nya atau tidak
    public function getListAccessableMenu(Request $request)
    {
        $validation = $this->validate($request, [
            'role_id' => 'required',
        ]);

        $data = $this->listAllAccess(0, $request->role_id);

        return response()->json($data, 200);
    }

    // Request untuk save/remove access list suatu role
    public function postSaveAccessListRoles(Request $request)
    {
        $validation = $this->validate($request, [
            'role_id' => 'required',
            'checked' => 'sometimes|required|array',
            'unchecked' => 'sometimes|required|array',
        ]);

        // untuk bikin supaya bisa update or create, perlu dua array, satu array untuk pencari nya, array kedua untuk nimpain value update nya, karna AccessListRole cuma ada 2 field utama, bikin suapaya updated_at nya ke update terus
        $dt = \Carbon\Carbon::now();

        // dd($request->checked);

        try {
            // mulai dari checked untuk insert, lalu ke unchecked untuk delete
            for ($i = 0; $i < count($request->checked); $i++) {
                $data = \App\Models\AccessListRole::where('access_list_id', '=', $request->checked[$i])
                    ->where('role_id', '=', $request->role_id)
                    ->firstOr(function () use ($request, $i) {
                        return \App\Models\AccessListRole::create([
                            'access_list_id' => $request->checked[$i], 'role_id' => $request->role_id
                        ]);
                    });
            }
            // delete unchecked
            $deleted = \App\Models\AccessListRole::whereIn("access_list_id", $request->unchecked)->where("role_id", '=', $request->role_id)->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to save Access List to Role.'], 500);
        }

        return response()->json(['message' => 'success'], 200);
    }

    // Request Toggle Switch untuk Field di Model
    public function postToggleSwitch(Request $request)
    {
        // Validation disini
        $validation = $this->validate(
            $request,
            [
                'model' => 'required',
                'field' => 'required',
                'value' => 'required',
                'id'    => 'required',
            ]
        );

        $modelName = "\App\Models\\" . $request->model;
        $fieldName = $request->field;
        $fieldValue = $request->value;
        $fieldId = $request->id;

        // update query
        $modelName::where('id', '=', $fieldId)->update([$fieldName => $fieldValue]);

        return response()->json(['message' => 'success'], 200);
    }

    // func untuk rekursif list menu dan request
    private function listAllAccess($parent = 0, $role_id = false)
    {
        $model = \App\Models\AccessList::query()->where('parent', '=', $parent)->orderBy('type')->orderBy('order')->select('access_lists.id', 'name as text', 'child', 'active')->selectRaw('IF(type = "request", "fas fa-link", icon) as icon');

        // check ada role id nya atau ga
        if ($role_id) {
            $model->leftJoin('access_list_roles', function ($join) use ($role_id) {
                $join->on('access_list_roles.access_list_id', '=', 'access_lists.id')->where('access_list_roles.role_id', '=', $role_id);
            })->selectRaw('IF(access_list_roles.id IS NULL, false, true) AS checked');
        }

        $data = $model->get();

        // looping di data buat cari child nya
        foreach ($data as $record) {
            // check ada child menu tidak
            if ($record->child == '1') {
                // rekursif panggil fungsi nya untuk bikin child menu
                $record->children = $this->listAllAccess($record->id, $role_id);
            }

            // mainin warna untuk masing masing icon nya
            if ($record->active != '1') {
                $record->icon .= ' text-danger';
            } elseif ($record->child == '1') {
                $record->icon .= ' text-primary';
            } else {
                $record->icon .= ' text-warning';
            }

            if ($role_id) {
                $record->data = ['checked' => $record->checked];
            }
        }

        return $data->toArray();
    }
}
