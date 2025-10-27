<?php

namespace App\Http\Controllers\Front\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Front\Dashboard\DueInterface;
use App\Http\Requests\Front\Due\CreateDueRequest;

class DueController extends Controller
{
    protected $dueInterface;
    public function __construct(DueInterface $dueInterface)
    {
      $this->dueInterface = $dueInterface;
    }

    public function index(){
        return $this->dueInterface->index();
    }

    public function createDue( CreateDueRequest $request){
        return $this->dueInterface->createDue($request);

    }
}
