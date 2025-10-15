<?php

namespace App\Http\Controllers\Admin;

use App\Traits\FcmNotificationTrait;
use App\Traits\SendNotificationTrait;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientsToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\City;
class ClientController extends Controller
{

    use FcmNotificationTrait;
    use SendNotificationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $clients = \App\Models\Client::get();
        $cities = City::get();
        return view('admin.clients.index', compact('clients','cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'state_id' => 'nullable|integer',
            'address' => 'nullable|string|max:255',
        ]);
    
        try {
            $client = Client::findOrFail($id);
    
            $client->name = $request->name;
            $client->email = $request->email;
            $client->phone = $request->phone ?? $client->phone;
            $client->state_id = $request->state_id ?? $client->state_id;
            $client->address = $request->address ?? $client->address;
    
            if ($request->filled('password')) {
                $client->password = Hash::make($request->password);
            }
    
            $client->save();
             toastr()->success('تم تحديث بيانات العميل بنجاح.');
            return back();
        } catch (\Exception $e) {
             toastr()->success('حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $client = \App\Models\Client::findOrFail($request->id)->delete();
        return back();
    }

    public function sendNotification(Request $request)
    {
        $clients_token = ClientsToken::where("client_id", $request->id)->get();

        foreach ($clients_token as $app_token) {
            $this->sendNotify($request->title, $request->body, $app_token->firebase_id);
        }
        toastr()->success('تم ارسال الاشعار بنجاح');
        return back();
    }
}
