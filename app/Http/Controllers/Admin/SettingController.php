<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class SettingController extends Controller
{
        use UploadFileTrait;

    public function settings()
    {
        $settings = Contact::first();
        $setting = Setting::first();
         $promo_codes = PromoCode::where('main',1)->first();
        return view('admin.settings.index',compact('settings','setting','promo_codes'));
    }

    public function settingsSave(Request $request)
    {
        $validatedData = $request->validate([
            'twitter'                  => 'required',
            'instagram'                => 'required',
            'phone'                    => 'required',
            'address'                  => 'required',
            'location'                 => 'required',
            'app_name'                 => 'required',
        ]);
            $setting = Contact::first();
            $settingoffer = Setting::first();
            $promo_codes = PromoCode::where('main',1)->first();
            
 
        $imageoffer['ar'] = $request->hasFile('offermain_ar') ? $this->uploadFile('settings', $request->file('offermain_ar')) : $settingoffer->getTranslation('offer_image', 'ar');
        $imageoffer['en'] = $request->hasFile('offermain_en') ? $this->uploadFile('settings', $request->file('offermain_en')) : $settingoffer->getTranslation('offer_image', 'en');
        $imageoffer['it'] = $request->hasFile('offermain_it') ? $this->uploadFile('settings', $request->file('offermain_it')) : $settingoffer->getTranslation('offer_image', 'it');
    
               // Add new fields
           $settingoffer->cashback_value = $request->cashback_value ?? $settingoffer->cashback_value; 
            $settingoffer->mount_pound = $request->mount_pound ?? $settingoffer->mount_pound; 
            $settingoffer->lower_cashback = $request->lower_cashback ?? $settingoffer->lower_cashback; 

              $settingoffer->offer_image =$imageoffer;
              $settingoffer->promo_code_name =$request->promo_code_name;
                 if($request->key_offer!=""){
                    $settingoffer->key_offer =$request->key_offer;
                 }

                 $settingoffer->keywords                     =$request->keywords;
                 $settingoffer->metadescription              =$request->metadescription;
              $settingoffer->save();
            
            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('settings',$request->image);
                $setting->update([
                    'image'   => $filepath,
                ]);
            }
            
        //     $promo_codes->counts =$request->count;
        //       $promo_codes->value =$request->promo_value;
        //    $promo_codes->end =$request->promo_endate;   
        //    $promo_codes->code =$request->promo_code_name;   
         
           
        //     $promo_codes->main =1;   
        //       $promo_codes->save();

            $setting = Contact::first();
            if ($request->hasfile('video_link')) {
                $filepath = $this->uploadFile('settings',$request->video_link);
                $setting->update([
                    'video_link'   => $filepath,
                ]);
            }

            $setting = Contact::first();
            if ($request->hasfile('head_image')) {
                $filepath = $this->uploadFile('settings',$request->head_image);
                $setting->update([
                    'head_image'   => $filepath,
                ]);
            }

            $setting = Contact::first();
            if ($request->hasfile('bottom_image')) {
                $filepath = $this->uploadFile('settings',$request->bottom_image);
                $setting->update([
                    'bottom_image'   => $filepath,
                ]);
            }

            $setting = Contact::first();
            if ($request->hasfile('offer_image')) {
                $filepath = $this->uploadFile('settings',$request->offer_image);
                $setting->update([
                    'offer_image'   => $filepath,
                ]);
            }

            if ($request->hasfile('insp_mop_img')) {
                $filepath = $this->uploadFile('settings',$request->insp_mop_img);
                $setting->update([
                    'insp_mop_img'   => $filepath,
                ]);
            }

            if ($request->hasfile('insp_web_img')) {
                $filepath = $this->uploadFile('settings',$request->insp_web_img);
                $setting->update([
                    'insp_web_img'   => $filepath,
                ]);
            }
            
            
            
         
                $setting->facebook                     =$request->facebook;
                $setting->twitter                      =$request->twitter;
                $setting->instagram                    =$request->instagram;
                $setting->youtube                      =$request->youtube;
                $setting->tiktok                       =$request->tiktok;
                $setting->phone                        =$request->phone;
                $setting->address                      =$request->address;
                $setting->location                     =$request->location;
                $setting->app_name                     =$request->app_name;
                $setting->about_footer                  =['en' => $request->about_footer_en, 'ar' => $request->about_footer_ar];
                $setting->tax_rate                      =$request->tax_rate;



            $setting->save();
        toastr()->success('success');
        return redirect('admin/setting');
    }
}
