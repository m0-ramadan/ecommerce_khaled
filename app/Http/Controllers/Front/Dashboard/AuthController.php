<?php

namespace App\Http\Controllers\Front\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Front\Dashboard\LoginRequest;
use App\Http\Interfaces\Front\Dashboard\AuthInterface;
use App\Http\Requests\Front\Dashboard\RegisterRequest;

class AuthController extends Controller
{
    protected $authInterface;

    public function __construct(AuthInterface $authInterface)
    {
      $this->authInterface = $authInterface;
    }

    public function registerPage(){
        return $this->authInterface->registerPage();
    }

public function register( RegisterRequest $request){

    return $this->authInterface->register($request);
}



public function loginPage(){
    return $this->authInterface->loginPage();


}
public function login( LoginRequest $request){
    return $this->authInterface->login($request);
  }




public function logout(){
return $this->authInterface->logout();
}
}
