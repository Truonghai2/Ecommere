<?php 
namespace App\Reponsitories;

use App\Models\Device;
use Google\Service\BinaryAuthorization\Check;

class DeviceReponsitory{

    public function add($user_id = null, $subscription_id){
        $device = new Device();

        $device->user_id = $user_id;
        $device->subcription_id = $subscription_id;
        $device->save();
    }

    public function updateUser_id($user_id, Device $device):void
    {
        $device->user_id = $user_id;
        $device->save();
        
    }


    public function selectUserID(string $subscription_id){
        $check = Device::where('subcription_id', $subscription_id)->first();

        if($check){
            return $check;
        }
        return null;
    }

    
    public function getSubscriptionID($user_id){
        return Device::where('user_id', $user_id)->first() ?? null;
    }

    public function getAllSubscription(){
        return Device::all();
    }
}