<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::orderBy('created_at')->paginate(15);

        $data = [
            'users' => $users,
        ];
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function levelup(User $user)
    {
        $user->level = 0;
        $user->update();
        return redirect()->back();
    }
    public function leveldown(User $user)
    {
        $user->level = 1;
        $user->update();
        return redirect()->back();
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|string|same:password',
        ]);
//        $file = $request->file('upload');
//        $file = $request->file('image');
        $user = User::create($request->all());
        $user->password = Hash::make($user->password);
        $user->level = 0;
        $user->update();
//        $unique_name = $product->id.'.'.$file->extension();
//        $product->imagename = $unique_name;
//        $product->update();
//        $request->file('image')->move(public_path().'/storage',$unique_name);
        // $path = $request->file->storeAs('路路徑', '');
        return redirect()->route('users.index');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
