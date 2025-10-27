<?php

namespace App\Http\Controllers\Front\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Front\Dashboard\ShipmentBagInterface;
use App\Http\Requests\Front\Dashboard\CreateShipmentBagRequest;

class ShipmentBagController extends Controller
{
    protected $shipmentBagInterface;
    public function __construct(ShipmentBagInterface $shipmentBagInterface)
    {
      $this->shipmentBagInterface = $shipmentBagInterface;
    }

    public function index(){
        return $this->shipmentBagInterface->index();
    }

    public function create(){
        return $this->shipmentBagInterface->create();
    }

    public function store(CreateShipmentBagRequest  $request){
        return $this->shipmentBagInterface->store($request);
    }

}
