<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::select('id', 'name', 'nickname', 'room_number', 'email', 'created_at')
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at');

        if ($request->has('search')) {
            $query->where('users.name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('users.nickname', 'like', '%' . $request->input('search') . '%');
        }

        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'room_number' => $request->room_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with([
                'message' => 'ユーザー登録をしました',
                'status' => 'info'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->room_number = $request->room_number;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with([
                'message' => 'ユーザー情報を更新しました',
                'status' => 'info',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        User::findOrFail($id)->delete(); // ソフトデリート
        return redirect()
            ->route('admin.users.index')
            ->with([
                "message" => "ユーザーが期限切れユーザー管理に移動しました。\n" . $user->name . "は現在ログインできません。",
                'status' => 'alert',
            ]);
    }

    public function expiredUserIndex(Request $request)
    {
        $query = User::onlyTrashed()
            ->orderByDesc('deleted_at');

        if ($request->has('search')) {
            $query->where('users.name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('users.nickname', 'like', '%' . $request->input('search') . '%');
        }

        $expiredUsers = $query->paginate(10);
        return view('admin.expired-users.index', compact('expiredUsers'));
    }

    public function expiredUserDestroy($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        User::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('admin.expired-users.index')
        ->with([
            "message" => $user->name . "を完全に削除しました。",
            'status' => 'alert',
        ]);
    }

    public function expiredUserRestore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('admin.expired-users.index');
    }

    public function expiredUserShow($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        return view('admin.expired-users.show', compact('user'));
    }
}
