<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\About_us;
use App\Models\Setting;
use App\Models\ContactUs;
use App\Http\Resources\AboutUsResource;

class DetailsController extends Controller
{
    use TranslatableTrait;

    public function faqs()
    {
        $faqs = Faq::all();
        return response()->json([
            'status' => true,
            'message' => $this->translate('faqs_retrieved'),
            'faqs' => $faqs,
        ], 200);
    }

    public function about_us(Request $request)
    {
        $lang = $request->header('lang', 'ar');

        $firstRow = About_us::find(1);
        $otherRows = About_us::where('id', '!=', 1)->get();

        return response()->json([
            'status' => true,
            'message' => $this->translate('about_us_retrieved'),
            'header' => new AboutUsResource($firstRow),
            'body' => AboutUsResource::collection($otherRows),
        ], 200);
    }

    public function privacy(Request $request)
    {
        $setting = Setting::first();
        $lang = $request->header('lang', 'ar');

        if ($setting) {
            $policy = $lang === 'ar' ? $setting->privacy_policy : $setting->privacy_policy_en;
            if ($policy) {
                return response()->json([
                    'status' => true,
                    'message' => $this->translate('privacy_policy_retrieved'),
                    'setting' => $policy,
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'error' => $this->translate('privacy_policy_not_found'),
        ], 404);
    }

    public function terms_conditions(Request $request)
    {
        $setting = Setting::first();
        $lang = $request->header('lang', 'ar');

        if ($setting) {
            $terms = $lang === 'ar' ? $setting->terms_conditions : $setting->terms_conditions_en;
            if ($terms) {
                return response()->json([
                    'status' => true,
                    'message' => $this->translate('terms_conditions_retrieved'),
                    'setting' => $terms,
                ], 200);
            }
        }

        return response()->json([
            'status' => false,
            'error' => $this->translate('terms_conditions_not_found'),
        ], 404);
    }

    public function contact_us(Request $request)
    {
        $language = $request->header('lang', 'ar');

        $contactUs = [
            'emails' => ContactUs::all()->map(function ($contact) use ($language) {
                $nameJson = json_decode($contact->name, true);
                $name = $nameJson && isset($nameJson[$language]) ? $nameJson[$language] : $contact->name;
                return ['name' => $name, 'email' => $contact->email];
            })->toArray(),
            'addresses' => ContactUs::all()->map(function ($contact) use ($language) {
                $nameJson = json_decode($contact->name, true);
                $name = $nameJson && isset($nameJson[$language]) ? $nameJson[$language] : $contact->name;
                return ['name' => $name, 'address' => $contact->address];
            })->toArray(),
            'phones' => ContactUs::all()->map(function ($contact) use ($language) {
                $nameJson = json_decode($contact->name, true);
                $name = $nameJson && isset($nameJson[$language]) ? $nameJson[$language] : $contact->name;
                return ['name' => $name, 'phone' => $contact->phone];
            })->toArray(),
        ];

        $setting = Setting::first()->only(['whatsapp', 'messenger', 'linkedin', 'twitter', 'facebook', 'tiktok', 'snapchat', 'instagram']);

        return response()->json([
            'status' => true,
            'message' => $this->translate('contact_us_retrieved'),
            'ContactUs' => $contactUs,
            'social_media' => $setting,
        ], 200);
    }
}