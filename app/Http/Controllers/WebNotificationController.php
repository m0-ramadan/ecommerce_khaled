<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class WebNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('notification');
    }

    public function sendWebNotification(Request $request)
    {
        $token = 'cZ_ccc21TzmXWEJpLeznPZ:APA91bE_-5Ygm2o1exoX2izNedwBGgFPtC2H3f5vqopNvMAsQU9ZTcDuX7142zy0ungazlYE3k9OEZtpMFK2K9rnE_JqXz0nmEGCPSkCAH--lG50tf3QOEfWZmdaO9PU2EeitIn4glck';
        $from = "AAAAfXEwyJw:APA91bHl1BfrVdn2fq-GXuA3IE8C6B51PEYOlJfggsGd__oS0fNv7BqwfnEt8Gs5vXkYm7f2AtQqQFncsC7Hlg_672RqFOUvEtiYLsr3p2ZADL11TtXntoMdti1q-IIUrso_NZulvSDG";
        $msg = array
        (
            'body'      => $request->body,
            'title'     => $request->title,
            'receiver'  => 'erw',
            'icon'      => 'https://zawdny.amlakyeg.com/public/images/setting/1644508882.png',/*Default Icon*/
            'vibrate'   => 1,
            'sound'     => "http://commondatastorage.googleapis.com/codeskulptor-demos/DDR_assets/Kangaroo_MusiQue_-_The_Neverwritten_Role_Playing_Game.mp3",

        );
        $fields = array
        (
            'to'        => $token,
            'notification'  => $msg
        );
        $headers = array
        (
            'Authorization: key=' . $from,
            'Content-Type: application/json'
        );
        //#Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
//
        return back();
    }
}
