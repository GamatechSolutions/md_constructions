<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;

class AdminController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('admin');
	}

	public function deleteUser(Request $request, $user_id)
	{
		$user = User::findOrFail($user_id);

		if ($user->id == auth()->user()->id)
		{
			return back();
		}

		$user->delete();

		return redirect()->route('admin::list-users')->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izbrisali korisnika!'
			]
		]);
	}

    public function createUserView()
	{
		$data = [
			'route'	=> route('admin::create-user'),
			'mode'	=> 'create',
			'roles'	=> Role::all()
		];

		return view('user.create-edit')->with($data);
	}

	public function editUserView($user_id)
	{
		$user = User::findOrFail($user_id);

		$data = [
			'user'	=> $user,
			'route'	=> route('admin::edit-user', [ $user->id ]),
			'mode'	=> 'edit',
			'roles'	=> Role::all()
		];

		return view('user.create-edit')->with($data);
	}

	public function createUser(CreateUserRequest $request)
	{
		$input = $request->validated();

		$user = User::create([
			'name'			=> $input['name'],
			'email'			=> $input['email'],
			'password'		=> \Hash::make($input['password']),
		]);

		$user->syncRoles($input['role']);

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste dodali korisnika!'
			]
		]);
	}

	public function editUser(EditUserRequest $request, $user_id)
	{
		$input = $request->validated();
		$user = User::findOrFail($user_id);
		$fields = [
			'name'	=> $input['name'],
			'email' => $input['email'],
		];

		if (isset($input['password']) && strlen($input['password']))
		{
			$fields['password'] = \Hash::make($input['password']);
		}

		$user->update($fields);
		$user->syncRoles($input['role']);

		return back()->with([
			'message' => [
				'type'	=> 'success',
				'text'	=> 'Uspešno ste izmenili korisnika!'
			]
		]);
	}

	public function listUsersView()
	{
		$data = [
			'users'	=> User::all()
		];

		return view('user.list')->with($data);
	}
}
