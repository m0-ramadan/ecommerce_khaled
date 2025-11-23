<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\ContactUs;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Mail\ContactUsNotification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Website\ContactUsRequest;
use App\Http\Resources\Website\ContactUsResource;

class ContactUsController extends Controller
{
    use ApiResponseTrait;
    public function store(ContactUsRequest $request)
    {

        $contact = ContactUs::create($request->validated());

        Mail::to(env("EMAIL_RECIEVERED"))->send(new ContactUsNotification($contact));

        return $this->success( new ContactUsResource($contact), 'تم جلب بيانات بنجاح');

    }
}