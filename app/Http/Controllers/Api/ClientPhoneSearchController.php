<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Traits\ApiTrait;

class ClientPhoneSearchController extends Controller
{
    use ApiTrait;

    public function index(Request $request)
    {
        $clients = Client::where('phone', $request->phone)->first(['name', 'phone']);
        return $this->apiResponse(true, '', [], $clients);
    }
}
