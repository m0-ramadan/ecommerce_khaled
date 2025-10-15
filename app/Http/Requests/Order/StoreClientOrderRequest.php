<?php

namespace App\Http\Requests\Order;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth('api')->user() ?? auth('clientsWeb')->user();
        return [
            'promo_code' => [
                'nullable',
                Rule::exists('promo_codes', 'code')->where(function ($query) {
                    $today = now()->toDateString();

                    $query->where('start_date', '<=', $today)
                        ->where('end', '>=', $today);
                }),
            ],
            'cash_back' => 'nullable|integer|max:' . ($user->total_point ?? 0),
            'amount' => 'required_with:card_id|integer|max:999',
            'card_id' => [
                'nullable',
                Rule::exists('giftwallet', 'id')->where(function ($query) use ($user) {
                    $query->where('client_id', $user->id);
                    $query->where('remaining', '>=', $this->amount);
                    $query->where('status', 1);
                }),
            ]
        ];
    }

    public function withValidator(Validator  $validator)
    {
        $route_name = $this->route()->getName();
        if ($route_name == 'makeOrder' || $route_name == 'make-order-with-paytabs') {
            $validator->addRules([
                // 'address' => 'required',
                'name' => 'required',
                //  'email' => 'required',
                'phone' => 'required',
               'state_id' => 'required',
                // 'country_id' => 'required',
//                'zip_code' => 'required',
//                'neighborhood' => 'required',
            ]);
        }
    }
}
