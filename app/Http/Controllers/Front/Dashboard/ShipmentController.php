<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Interfaces\Front\Dashboard\ShipmentInterface;
use App\Http\Requests\Front\Dashboard\Shipment\CreateShipmentRequest;

class ShipmentController extends Controller
{
    protected $shipmentInterface;
    public function __construct(ShipmentInterface $shipmentInterface)
    {
        $this->shipmentInterface = $shipmentInterface;
    }

    public function index(Request $request)
    {
        return $this->shipmentInterface->index($request);
    }

    public function chooseCompany()
    {
        return $this->shipmentInterface->chooseCompany();
    }

    public function create($id)
    {
        return $this->shipmentInterface->create($id);
    }

    public function store(CreateShipmentRequest $request)
    {
        return $this->shipmentInterface->store($request);
    }

    public function verifyCode(Request $request)
    {
        return $this->shipmentInterface->verifyCode($request);

    }
    public function cost(Request $request)
    {
        return $this->shipmentInterface->cost($request);
    }

    public function cancel(Request $request)
    {
        return $this->shipmentInterface->cancel($request);
    }

    public function track($id)
    {
        return $this->shipmentInterface->track($id);
    }

    public function getShipments($status)
    {
        return $this->shipmentInterface->getShipments($status);
    }


    public function export()
    {
        return $this->shipmentInterface->export();
    }

    public function exportPdf()
    {
        return $this->shipmentInterface->exportPdf();
    }

    public function client(Request $request)
    {
        return $this->shipmentInterface->client($request);
    }

    public function calcPackageInfo(Request $request)
    {
        return $this->shipmentInterface->calcPackageInfo($request);
    }

    public function printPdf($id)
    {
        $shipment = Shipment::with(['client', 'branchFrom', 'branchTo', 'region', 'countryReceived', 'images'])->findOrFail($id);

        Pdf::setOptions(['defaultFont' => 'Times New Roman']);
        Pdf::getFontCache()->registerFont(public_path('fonts/TimesNewRoman.ttf')); // Ensure this font is available

        $pdf = Pdf::loadView('Admin.shipment.print', compact('shipment'))
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('shipment_details_' . ($shipment->code ?? 'shipment') . '.pdf');
    }
}