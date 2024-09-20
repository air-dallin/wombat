<?php

namespace App\Exports;

use App\Models\UserInfo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $lang=app()->getLocale();
        $role = $this->role;
        return UserInfo::select(['users.id','user_infos.firstname','user_infos.middlename','user_infos.lastname',DB::raw('DATE_FORMAT(user_infos.bithdate,"%Y-%m-%d")'), DB::raw('IF(user_infos.gender=1,"Man","Woman")'),'users.phone', 'users.email','regions.title_'.$lang,'cities.title_'.$lang,'user_infos.address','user_infos.company','users.company_id','user_infos.position',
            DB::raw("CASE
                    WHEN users.status = 0 THEN 'wait'
                    WHEN users.status = 1 THEN 'active'
                    WHEN users.status =2 THEN 'phone-confirm'
                    ELSE 'inactive'
                END as status"),
            DB::raw('DATE_FORMAT(user_infos.created_at,"%Y-%m-%d %H:%i:%s")')
        ])
            ->join('users','users.id','=','user_infos.user_id')
            ->join('regions','user_infos.region_id','=','regions.id')
            ->join('cities','cities.id','=','user_infos.city_id')
            ->where(['users.role'=>$role])
            ->orderBy('users.id','ASC')
            ->orderBy('users.company_id','ASC')

            ->get();
    }


    /**
     * @return array
     */
     public function headings(): array
     {

         return [
             'ID',
             __('main.firstname'),
             __('main.middlename'),
             __('main.lastname'),
             __('main.birthdate'),
             __('main.gender'),
             __('main.phone'),
             __('main.email'),
             __('main.region'),
             __('main.city'),
             __('main.address'),
             __('main.company'),
             'ID '.__('main.company'),
             __('main.position'),
             __('main.status'),
             __('main.created_at'),
         ];
     }


}
