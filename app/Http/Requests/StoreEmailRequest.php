<?php

namespace App\Http\Requests;

use App\Models\Client;
use App\Models\Email;
use Illuminate\Foundation\Http\FormRequest;


class StoreEmailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return Email::RULES;
    }

    public function passedValidation()
    {
        if ($this->phone) {
            $reserver_id = Client::where('phone', $this->phone)->first()->id;
            $sender = auth('api')->id();
        } elseif($this->parent_id) {
            $email = Email::findOrFail($this->parent_id);
            if (auth('api')->id() === $email->reserver_id) {
                $reserver_id = $email->sender_id;
            } else {
                $reserver_id = $email->reserver_id;
            }
        }


        $this->merge([
            'reserver_id' => $reserver_id ?? null,
            'sender_id' => auth('api')->id(),
            'email_sender_id' => $sender ?? null,
        ]);
    }
}
