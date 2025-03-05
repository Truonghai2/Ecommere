<?php
namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller{

    protected $notification;

    /**
     * dependency injection
     *
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notification = $notificationService;
    }


    /**
     * function add a notification
     *
     * @param Request $request
     * @return void
     */
    public function addNotificaion(Request $request){

        $content = $request->input('content');
        $type = $request->input('type');
        $array = $request->input('array', []);

        return $this->notification->addNotificaion($content, $type, $array);
    }

    /**
     * function get notifications
     *
     * @param Request $request
     * @return void
     */
    public function getNotification(Request $request){
        return $this->notification->getNotification($request->page);
    }


    public function notificationUser(Request $request){

        return view('layout.Notification');
    }

    public function getUserNotificationUser(Request $request){
        $page = $request->page;
        $type = $request->type;
        return $this->notification->handleNotificationUser($type, $page);
    }

    public function countNotification(Request $request){
        return $this->notification->countNotification();
    }


    public function markSeeNotification(Request $request){
        $this->notification->markAsRead($request->id);

        return true;
    }

}
