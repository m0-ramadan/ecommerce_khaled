<?php

namespace App\Http\Controllers\Front\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Front\Dashboard\UserInterface;
use App\Http\Requests\Front\Clients\CreateClientRequest;
use App\Http\Requests\Front\Clients\UpdateClientRequest;
use App\Http\Requests\Front\Dashboard\User\ChangePasswordRequest;
use App\Http\Requests\Front\Dashboard\User\EditBankRequest;
use App\Http\Requests\Front\Dashboard\User\EditIdNumberRequest;
use App\Http\Requests\Front\Dashboard\User\EditProfileRequest;
use App\Models\Clients;


class UserController extends Controller
{
    protected $userInterface;
    public function __construct(UserInterface $userInterface)
    {
      $this->userInterface = $userInterface;
    }

    public function index(){
        return $this->userInterface->index();
    }

    public function edit(EditProfileRequest $request){
        return $this->userInterface->edit($request);
    }

    public function editBank(EditBankRequest $request){
        return $this->userInterface->editBank($request);
    }

    public function editIdNumber(EditIdNumberRequest $request){
        return $this->userInterface->editIdNumber($request);
    }

    public function changePassword(ChangePasswordRequest $request){
        return $this->userInterface->changePassword($request);

    }
    public function addClient(CreateClientRequest $request){
        return $this->userInterface->addClient($request);

    }
    
    public function deleteClient( $client){
        return $this->userInterface->deleteClient($client);
    }
    
    public function editClient(UpdateClientRequest $request){
        return $this->userInterface->editClient($request);

    }
}
