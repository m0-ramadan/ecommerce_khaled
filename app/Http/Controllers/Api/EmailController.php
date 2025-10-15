<?php

namespace App\Http\Controllers\Api;

use App\Models\Email;
use App\Models\Client;
use App\Events\NewEmail;
use App\Traits\ApiTrait;
use App\Models\ClientsToken;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Traits\FirebaseNotification;
use App\Http\Resources\EmailResource;
use App\Http\Requests\StoreEmailRequest;
use Illuminate\Support\Facades\Notification;

class EmailController extends Controller
{
    use ApiTrait, FirebaseNotification;


    public function inbox()
    {
        $inbox = Email::where('reserver_id', auth('api')->id())->whereNotNull('title')->latest()->get();
        $inbox = EmailResource::collection($inbox);
        return $this->apiResponse(true, '', [], $inbox);
    }

    public function outbox()
    {
        $outbox = Email::where('email_sender_id', auth('api')->id())->latest()->get();
        $outbox = EmailResource::collection($outbox);
        return $this->apiResponse(true, '', [], $outbox);
    }

    public function show(Email $email)
    {
        if ($email->reserver_id === auth('api')->id()) {
            $email->update(['reserver_read_at' => now()]);
        } else {
            $email->update(['sender_read_at' => now()]);
        }
        $emails = new EmailResource($email);
        return $this->apiResponse(true, '', [], $emails);
    }

    public function send(StoreEmailRequest $request)
    {
        $email = Email::create($request->only([
            'sender_id',
            'reserver_id',
            'email_sender_id',
            'parent_id',
            'title',
            'content',
        ]));
        event(new NewEmail($email));
        if ($request->parent_id) {
            $parent_email = Email::findOrFail($request->parent_id);
            if ($parent_email->reserver_id === auth('api')->id()) {
                $parent_email->update(['sender_read_at' => null]);
            } else {
                $parent_email->update(['reserver_read_at' => null]);
            }
        } else {
            $email->update(['sender_read_at' => now()]);
        }
        $email = new EmailResource($email);
        return $this->apiResponse(true, 'Email sent successfully.', [], $email);
    }
}
