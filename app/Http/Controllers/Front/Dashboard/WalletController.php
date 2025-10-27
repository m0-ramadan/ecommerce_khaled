<?php

namespace App\Http\Controllers\Front\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Front\Dashboard\WalletInterface;
use App\Http\Requests\Front\Dashboard\CreateWalletChargeRequest;

class WalletController extends Controller
{
    protected $walletInterface;
    public function __construct(WalletInterface $walletInterface)
    {
      $this->walletInterface = $walletInterface;
    }

    public function index(Request $request){
        return $this->walletInterface->index($request);
    }
    
    public function allWallets(){
        return $this->walletInterface->allWallets();
    }

    public function mywallet(){
        return $this->walletInterface->mywallet();
    }
    
    public function store(CreateWalletChargeRequest $request){
        return $this->walletInterface->store($request);
    }

    public function callBack(Request $request){
        return $this->walletInterface->callBack($request);


    }

   
}
