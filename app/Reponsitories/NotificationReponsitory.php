<?php 
namespace App\Reponsitories;

use App\Models\Notification;

class NotificationReponsitory{

    protected $perPage = 15;

    public function getNotification($page){
        return Notification::with('user')->orderBy('created_at', 'desc')->paginate($this->perPage,['*'], 'page', $page);
    }

    public function addAllNotification($content){
        $notification = new Notification();
        $notification->content = $content;
        $notification->save();
    }
    

    public function addArrayNotification($item){

    }

    public function insert($data): void 
    {
        Notification::insert($data);
    }
}