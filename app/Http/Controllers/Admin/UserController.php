<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
//use Illuminate\Http\Request;
use App\Http\Requests\AdminUserStoreRequest;
use App\Http\Requests\AdminUserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Редактирование пользователей';
        $users = User::paginate(10);

        return view('admin.users.users_content')
            ->with(['title'=>$title, 'users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.users_create')->with(['title' => 'Добавить пользователя']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminUserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUserStoreRequest $request)
    {
        //dd($request);
        $data = $request->except('_token', 'password_confirmation');
        $data['password'] = bcrypt($data['password']);

        if (User::create($data)) {
            return redirect()->route('admin.user.index')
                ->with(['status' => 'Пользователь успешно добавлен']);
        }

        $request->flash();
        return redirect()->route('admin.user.index')
            ->with(['error' => 'Ошибка добавления пользователя']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $title = 'Редактирование пользователя';

        return view('admin.users.users_create')
            ->with(['title'=>$title, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AdminUserUpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUserUpdateRequest $request, User $user)
    {
        //dd($request);
        $data = $request->except('_token', '_method' ,'password_confirmation');

        // ввели пароль - меняем, не ввели - оставляем старый
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $user->password;
        }

        if($user->update($data)) {
            return redirect()->route('admin.user.index')
                ->with(['status' => 'Пользователь был успешно изменен']);
        }

        return redirect()->route('admin.user.index')
            ->with(['error' => 'Ошибка измения пользователя']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        if($user->delete()) {
            return redirect()->route('admin.user.index')
                ->with(['status' => 'Пользователь успешно удален']);
        }

        return redirect()->route('admin.user.index')
            ->with(['error' => 'Ошибка удаления данных.']);
    }
}
