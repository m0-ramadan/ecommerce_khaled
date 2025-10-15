<?php

namespace App\Http\Controllers\Admin\Message;

use App\Models\Client;
use App\Mail\MessageMail;
use App\Models\ClientsToken;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;
use App\Http\Controllers\Controller;
use App\Traits\FirebaseNotification;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewEmailNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Admin\Message\SendMessageRequest;
use App\Http\Requests\Admin\Message\UnRegisteredMessageRequest;

class MessageController extends Controller
{
    use FirebaseNotification, UploadFileTrait;

    public function create()
    {
        $clients = Client::get();
        return view('admin.notification.create', compact('clients'));
    }

    public function send(SendMessageRequest $request)
    {
        $clients = Client::when(
            (isset($request->client_email) && !is_null($request->client_email)),
            fn ($q) => $q->where('email', $request->client_email),
        )
            ->when(
                (isset($request->client_id) && !is_null($request->client_id)),
                fn ($q) => $q->where('id', $request->client_id),
            )
            ->when(
                (isset($request->clients) && !is_null($request->clients)),
                fn ($q) => $q->whereIn('id', $request->clients),

            )
            ->with(['firebaseTokens'])
            ->get();

        $file = $request->has('file') ? $this->uploadFile('uploads/messages', $request->file) : null;

        foreach ($clients as $client) {
            $this->handleSending($client, $request->title, $request->message, $file, $request->email);
        }

        toastr()->success('تم الارسال بنجاح');
        return redirect()->back();
    }

    public function handleSending(Client $client, $title, $message, $file = null, $email = null, $client_email = null)
    {
        $this->sendFirebaseNotification(
            $client->firebaseTokens->pluck('firebase_id')->toArray(),
            $title,
            $message,
            $file
        );

        Notification::send(
            $client,
            new NewEmailNotification($title, $message, $file)
        );

        if ($email) {
            try {
                Mail::to($client->email)->send(new MessageMail($title, $message, $file));
            } catch (\Throwable $th) {
                toastr()->error('حصل خطأ اثناء ارسال الايميل');
            }
        }

        if (isset($client_email) && !is_null($client_email)) {
            try {
                Mail::to($client_email)->send(new MessageMail($title, $message, $file));
            } catch (\Throwable $th) {
                toastr()->error('حصل خطأ اثناء ارسال الايميل');
            }
        }
    }

    public function unregisteredUsers(UnRegisteredMessageRequest $request)
    {
        $tokens = ClientsToken::whereNull('client_id')->pluck('firebase_id')->toArray();

        $this->sendFirebaseNotification(
            $tokens,
            $request->title,
            $request->message,
        );

        toastr()->success('تم الارسال بنجاح');
        return redirect()->back();
    }
}
