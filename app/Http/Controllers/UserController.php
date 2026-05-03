<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('id', '!=', 4)->with('role');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($row) {
                    return $row->role->name ?? '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-danger">Nonaktif</span>';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('user.edit', $row->id);
                    $deleteUrl = route('user.destroy', $row->id);

                    return '<div class="btn-group">
                                <a href="' . $editUrl . '" class="btn btn-sm btn-warning">Edit</a>
                                <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Hapus data ini?\')" style="display:inline-block;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('user.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255|unique:users,name',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'is_active' => 'nullable|boolean',
            'color' => 'nullable|string|max:50',
        ]);

        User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'is_active' => $request->is_active ?? 1,
            'color' => $request->color,
        ]);

        return redirect()->route('user.index')->with('success', 'pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'name' => 'required|string|max:255|unique:users,name,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'is_active' => 'nullable|boolean',
            'color' => 'nullable|string|max:50',
        ]);

        $data = [
            'role_id' => $request->role_id,
            'name' => $request->name,
            'username' => $request->username,
            'is_active' => $request->is_active ?? 1,
            'color' => $request->color,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('success', 'pengguna berhasil dihapus.');
    }
}
