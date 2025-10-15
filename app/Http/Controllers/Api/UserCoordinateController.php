<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Contact;
use App\Models\ClientsToken;
use Illuminate\Http\Request;
use App\Models\UserCoordinate;
use App\Http\Controllers\Controller;
use App\Traits\FirebaseNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Api\Coordinate\StoreUserCoordinateRequest;

class UserCoordinateController extends Controller
{
    use FirebaseNotification;

    public function store(StoreUserCoordinateRequest $request)
    {
        $userCoordinate = UserCoordinate::updateOrCreate([
            'firebase_token' => $request->firebase_token
        ], $this->getGoogleMapInfo($request->latitude, $request->longitude));


        if (
            !auth('api')->check()
            &&
            ($userCoordinate->registered_notification_count < 2
                || (Carbon::parse($userCoordinate->last_registered_notification)->diffInHours(now()) > 24
                    && $userCoordinate->registered_notification_count >= 2))
        ) {
            if ($userCoordinate->registered_notification_count >= 2) {
                $userCoordinate->update([
                    'registered_notification_count' => 1,
                    'last_registered_notification' => now(),
                ]);
            } else {
                $userCoordinate->update([
                    'registered_notification_count' => $userCoordinate->registered_notification_count + 1,
                    'last_registered_notification' => now(),
                ]);
            }
            $settings = Contact::first();
            $this->sendFirebaseNotification([$request->firebase_token], $settings->registered_notification_title, $settings->registered_notification_content);
        }

        if (!auth('api')->check()) {
            ClientsToken::create(['firebase_id' => $request->firebase_token]);
        }
    }

    public function getGoogleMapInfo($lat, $lng)
    {
        $key = 'AIzaSyAK4fMcwTnw-wui5HKSkz5aBg44dMNEDlw';
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';

        $endpoint = $url . "?latlng=$lat,$lng" . "&key=$key";
        $response_data = json_decode(Http::get($endpoint))->results[0];
        return [
            'latitude' => $lat,
            'longitude' => $lng,
            'country_name' => $response_data->address_components[5]->long_name,
            'state_name' => $response_data->address_components[4]->long_name,
            'city_name' => $response_data->address_components[3]->long_name,
            'address' => $response_data->formatted_address,
        ];
    }
}
