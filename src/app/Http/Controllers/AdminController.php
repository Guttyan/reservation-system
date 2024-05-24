<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function getAdmin(){
        $admin_role_id = Role::where('name', 'admin')->first()->id;
        $representative_role_id = Role::where('name', 'representative')->first()->id;

        $users = User::whereDoesntHave('roles', function($query) use ($admin_role_id, $representative_role_id) {
            $query->whereIn('role_id', [$admin_role_id, $representative_role_id]);
        })->get();

        return view('admin', compact('users'));
    }

    public function userDetail($user_id){
        $user = User::where('id', $user_id)->first();
        return view('user_detail', compact('user'));
    }

    public function createRepresentative(Request $request){
        $user = User::where('id', $request->id)->first();

        $representativeRole = Role::where('name', 'representative')->first();
        $user->assignRole($representativeRole);

        return redirect('/admin');
    }
}
