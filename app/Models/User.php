<?php

namespace App\Models;

use App\Http\Traits\Order;
use App\Http\Traits\Status;
use App\Http\Traits\Statuses;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Order,Status,Statuses;

    const ROLE_CLIENT = 1;
    const ROLE_COMPANY = 2;
    const ROLE_MODERATOR = 8;
    const ROLE_ADMIN = 9;

    const ROLE_FARMER = 0;
    const ROLE_ADVISER_AKIS = 0;
    const ROLE_ADVISER_COMPANY = 0;
    const ROLE_UNIVERSITY = 0;
    const ROLE_ADVISER_UNIVERSITY = 0;
    const ROLE_RESEARCH = 0;
    const ROLE_DIRECTOR = 0;
    const ROLE_ADVISER_USER = 0;
    const ROLE_EDUCATOR = 0;

    const STATUS_CREATE = 0;
    const STATUS_PHONE_CONFIRM = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'phone',
        'type',
        'status',
        'password',
        'remember_token',
        'email_verified_at',
        'phone_verified_at',
        'company_id',
        'parent_id',
        'login_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function image(){
        return $this->hasOne(Image::class,'object_id');
    }

    public function userLogin(){
        return $this->hasOne(UserLogin::class,'user_id');
    }



    public static function getRoles(){

        return [
            '1' => 'clients',
            '2' => 'company',
            '3' => '',
            '4' => '',
            '5' => '',
            '6' => '',
            '7' => '',
            '8' => 'moderator',
            '9' => 'admin',
        ];

    }

    public function getFullname(){

        $this->load('info');
        return $this->info->firstname . ' ' . $this->info->lastname;
    }


    // получение роли по id
    public function getRole(int $role) {
         $roles = User::getRoles();
         return isset($roles[$role]) ? $roles[$role] : 'undefinded';
    }

    // получение роли по id
    public static function getRoleLabel(int $role) {
        $roles = User::getRoles();
        return isset($roles[$role]) ? $roles[$role] : 'undefinded';
    }

    // проверка роли по id
    public static function isRole(int $role) {
        return Auth::user()->role == $role;
    }

    // проверка на наличие роли из списка ролей по id
    public static function isRoleIn(array $roles) {
        return in_array(Auth::user()->role, $roles);
    }

    // проверка роли по имени
    public static function hasRole(string $role) {
        $roles = User::getRoles();
        $roles = array_flip($roles);
        return Auth::user()->role == (int)$roles[$role];
    }

    // проверка роли по имени из массива
    public static function hasRoleIn(array $roles) {
        $myRole = (new User)->getRole(Auth::user()->role);
        return in_array($myRole, $roles);
    }

    public function getRoleTitle($role){
        switch($role){
            case self::ROLE_ADMIN:
                return 'Admin';
            case self::ROLE_CLIENT:
                return 'Client';
            case self::ROLE_MODERATOR:
                return 'Moderator';
            case self::ROLE_COMPANY:
                return 'Company';

            default:
                return 'Unknown';
        }
    }

    //
    public static function getRoleByType($type){
        $roles = array_flip(self::getRoles());
        return isset($roles[strtolower($type)]) ? $roles[strtolower($type)] : false;
    }




    public function info(){
        return $this->hasOne(UserInfo::class,'user_id','id')->with(['region','city']);
    }


    // текущий активный тариф
    public function tarif(){
        return $this->belongsToMany(Tarif::class,'user_tarifs','user_id','tarif_id')->where(['user_tarifs.status'=>User::STATUS_ACTIVE]);
    }

    // все тарифы
    public function tarifs(){
        return $this->belongsToMany(Tarif::class,'user_tarifs','user_id','tarif_id');

    }

    /*public function tokens(){
        return $this->HasMany(Token::class,'user_id');
    }*/

    public function companies()
    {
        return $this->hasMany(Company::class, 'user_id');
    }

    public static function defaultImage(){
        Storage::url('/favicon.png');
    }

    // фото gender
    public static function getImage($user=false){
        if(!$user) $user = Auth::user();
        $image = $user->gender == 1 ? 'man.svg' : 'woman.svg';
        return isset($user->image) ? Storage::url($user->image->small()) : asset('images/avatar/'.$image);
    }


}
