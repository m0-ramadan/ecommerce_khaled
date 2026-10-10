<?php

namespace App\Http\Repositories\Admin;

use App\Http\Interfaces\Admin\SubscribeInterface;

class SubscribeRepository implements SubscribeInterface {
    public function __construct()
    {
    }
    public function index(){
        $subscribes = collect();
        return view('Admin.subscribe.index', compact('subscribes'));
    }
}
