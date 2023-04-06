<?php

namespace App\Http\Controllers\Owner;

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
        $this->middleware('auth:owners');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::select('id', 'name', 'nickname', 'email', 'created_at')
        ->orderByDesc('updated_at')
        ->orderByDesc('created_at');

        if ($request->has('search')) {
            $query->where('users.name', 'like', '%' . $request->input('search') . '%')
                ->orWhere('users.nickname', 'like', '%' . $request->input('search') . '%');
        }

        $users = $query->paginate(10);
        return view('owner.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.users.create');
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
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('owner.users.index')
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
        //
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
        return view('owner.users.edit', compact('user'));
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
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('owner.users.index')
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
        User::findOrFail($id)->delete(); // ソフトデリート
        return redirect()
            ->route('owner.users.index')
            ->with([
                'message' => 'ユーザーを削除しました',
                'status' => 'alert',
            ]);
    }

    public function expiredUserIndex(Request $request)
    {
        $query= User::onlyTrashed()
        ->orderByDesc('deleted_at');
        
        if ($request->has('search')) {
            $query->where('users.name', 'like', '%' . $request->input('search') . '%')
            ->orWhere('users.nickname', 'like', '%' . $request->input('search') . '%');
        }
        
        $expiredUsers = $query->paginate(10);
        return view('owner.expired-users', compact('expiredUsers'));
    }

    public function expiredUserDestroy($id)
    {
        User::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('owner.expired-users.index');
    }

    public function expiredUserRestore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('owner.expired-users.index');
    }
}
