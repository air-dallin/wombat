<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Models\City;
use App\Models\Image;
use App\Models\Region;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PharIo\Manifest\ElementCollectionException;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $role = $request->has('role') ? $request->role : 1;

        $users = User::with(['image'=>function($q){$q->where(['type'=>'users']);}]/*,'info'*/)->notDeletedOnly()/*active()*/->where(['role'=>$role])->order()->paginate(100);
        return view('admin.user.index',compact('users','role'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {

        /* $role = $request->role ? $request->role : 1;

        $users = UserController::with('image','info')->where(['role'=>$role,'status'=>UserController::STATUS_CREATE])->order()->paginate(100);
        return view('admin.user.inactive',compact('users')); */
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::get();
        $cities = City::get();
        $roles = User::getRoles();
        return view('admin.user.form',compact('regions','cities','roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        unset($params['image']);
        $params['password'] = Hash::make($params['password']);
        $phone = correct_phone($params['phone']);
        if(!$user = User::where(['phone'=>$phone])->first()){
            $params['role'] = User::ROLE_MODERATOR;
            $user = User::create($params);
            $params['user_id'] = $user->id;
            UserInfo::create($params);

        }else{
            return redirect()->route('admin.user.create', app()->getLocale())
                ->withInput()
                ->with('error', __('main.user_exist'));
        }

        if ($request->has('image')) {
            Image::add($request,'users',$user->id);
        }

        return redirect()->route('admin.user.index',['role'=>User::ROLE_MODERATOR,app()->getLocale()])->with('success','UserController create success');
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
        $user->with(['info','image'=>function($q){$q->where(['type'=>'users']);}]);
        $regions = Region::get();
        $cities = City::get();
        $roles = User::getRoles();

        return view('admin.user.form',compact('user','regions','cities','roles'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $params = $request->all();
        unset($params['image']);

        $params['phone'] = correct_phone($params['phone']);


        if(is_null($params['password'])) unset($params['password']);

        $user->update($params);

        if($user->info) {
            $user->info->update($params);
        }else{
            $params['user_id'] = $user->id;
            UserInfo::create($params);
        }

        if ($request->has('image')) {
            Image::add($request->file('image'),'users',$user->id);
        }

        return redirect()->route('admin.user.index',['role'=>User::ROLE_MODERATOR,app()->getLocale()])->with('success','UserController create success');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            if($user->role==User::ROLE_MODERATOR) {
                $user->info->delete();
                $user->delete();
            }
            /*$user->status = 0;
            $user->save(); */
            return redirect()->route('admin.user.index',['role'=>User::ROLE_MODERATOR,app()->getLocale()])->with('success','User delete success!');
        }catch (\Exception $e){
            return redirect()->route('admin.user.index',['role'=>User::ROLE_MODERATOR,app()->getLocale()])->with('error','Cannot delete user has relations!');
        }

    }

    public function export(Request $request,$role){

        $export = new UsersExport();
        $export->role = $role;

        $title = User::getRoleLabel($role);
        return Excel::download($export,'users_'.$title.'.xlsx');
    }

}
