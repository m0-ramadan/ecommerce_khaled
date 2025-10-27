<?php

namespace App\Http\Controllers\Front\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Front\Dashboard\DashboardInterface;


class DashboardController extends Controller
{


    protected $dashboardInterface;
    public function __construct(DashboardInterface $dashboardInterface)
    {
      $this->dashboardInterface = $dashboardInterface;
    }

    public function index(){
        return $this->dashboardInterface->index();
    }


    public function home(){
        return $this->dashboardInterface->home();
    }
    public function codes(){
        return $this->dashboardInterface->codes();

    }

    public function notifications(){
        return $this->dashboardInterface->notifications();
    }


    public function markReadNotification($id){
        return $this->dashboardInterface->markReadNotification($id);
    }


    public function markAllRead(){
        return $this->dashboardInterface->markAllRead();
    }

    public function subscribe(Request $request){
        return $this->dashboardInterface->subscribe($request);

    }

    public function subscribeCallBack(Request $request){
        return $this->dashboardInterface->subscribeCallBack($request);
    }

    public  function  subscriptions(){
        return $this->dashboardInterface->subscriptions();
    }

    public function addWeightToPackage(Request $request){
        return $this->dashboardInterface->addWeightToPackage($request);
    }


    public  function  subscribeFromWallet(Request $request){
        return $this->dashboardInterface->subscribeFromWallet($request);
    }
}
