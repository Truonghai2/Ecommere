<?php

namespace App\Reponsitories;

use App\Models\Address;
use App\Models\User;

class UserReponsitory{
    protected $user;
    protected $perPage = 15;
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function search_user($data){
        $users = User::where('first_name', 'like', "%{$data}%")
                     ->orWhere('last_name', 'like', "%{$data}%")
                     ->get()->toArray();

        return $users;
    }
    public function selectUser($page){
        return User::select('id', 'first_name','last_name')->paginate($this->perPage, ['*'], 'page', $page);
    }


    public function PerPage($page){
        $users = User::orderByDesc('created_at')->paginate($this->perPage, ['*'], 'page', $page);

        return $users;
    }
    

    public function Address($province_id, $provinces_name, $district_id, $district_name, $ward_id, $ward_name, $home_number){
        $address = new Address();
        $address->user_id = auth()->id();
        $address->provinces_id = $province_id;
        $address->provinces_name = $provinces_name;
        $address->district_id = $district_id;
        $address->district_name = $district_name;
        $address->ward_id = $ward_id;
        $address->ward_name = $ward_name;
        $address->home_number = $home_number;
        $address->active = 1;
        $address->save();
    }


    public function updateAddress(){

    }

}
