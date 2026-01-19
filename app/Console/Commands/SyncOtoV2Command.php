<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OtoV2ShippingService;

class SyncOtoV2Command extends Command
{
    protected $signature = 'oto:v2-sync 
                           {type? : نوع المزامنة (shipments, tracking, cities, all)}
                           {--order-id= : معرف طلب محدد}
                           {--tracking-number= : رقم تتبع محدد}';
    
    protected $description = 'مزامنة البيانات مع OTO API V2';

    public function handle()
    {
        $type = $this->argument('type') ?? 'all';
        $otoService = new OtoV2ShippingService();
        
        $this->info("🚚 بدء مزامنة OTO API V2: {$type}");
        
        switch ($type) {
            case 'shipments':
                $this->syncShipments($otoService);
                break;
                
            case 'tracking':
                $this->syncTracking($otoService);
                break;
                
            case 'cities':
                $this->syncCities($otoService);
                break;
                
            case 'all':
                $this->syncAll($otoService);
                break;
                
            default:
                $this->error("❌ نوع المزامنة غير صالح: {$type}");
                break;
        }
        
        $this->info("✅ تم الانتهاء من المزامنة.");
    }
    
    private function syncShipments($otoService)
    {
        $this->info('🔄 مزامنة الشحنات الجديدة...');
        
        $orders = \App\Models\Order::whereDoesntHave('shippingOrder')
            ->where('status', '!=', 'cancelled')
            ->with('user.addresses')
            ->get();
            
        foreach ($orders as $order) {
            $this->info("  📦 معالجة الطلب: {$order->order_number}");
            
            if ($order->user->addresses->isNotEmpty()) {
                $address = $order->user->addresses->first();
                
                $result = $otoService->createShipmentFromUserAddress($order->id, $address->id, [
                    'delivery_company' => 'aramex',
                    'service_type' => 'standard',
                    'who_pays' => 'sender'
                ]);
                
                if ($result['success']) {
                    $this->info("    ✅ تم إنشاء الشحنة: {$result['data']['tracking_number']}");
                } else {
                    $this->error("    ❌ فشل: {$result['message']}");
                }
            } else {
                $this->warn("    ⚠️  لا يوجد عنوان للطلب");
            }
            
            sleep(1); // تأخير لتجنب rate limits
        }
    }
    
    private function syncTracking($otoService)
    {
        $this->info('🔄 تحديث تتبع الشحنات...');
        
        $trackingNumber = $this->option('tracking-number');
        
        if ($trackingNumber) {
            $this->info("  🔍 تتبع شحنة محددة: {$trackingNumber}");
            $result = $otoService->trackShipment($trackingNumber);
            
            if ($result['success']) {
                $this->info("    ✅ تم تحديث حالة الشحنة: {$result['data']['status']}");
            } else {
                $this->error("    ❌ فشل: {$result['message']}");
            }
        } else {
            $result = $otoService->updateAllShipments();
            
            if ($result['success']) {
                $this->info("    ✅ تم تحديث {$result['updated_count']} من {$result['total']} شحنة");
            } else {
                $this->error("    ❌ فشل: {$result['message']}");
            }
        }
    }
    
    private function syncCities($otoService)
    {
        $this->info('🏙️  مزامنة المدن والمناطق...');
        
        $result = $otoService->getCities();
        
        if ($result['success']) {
            $cityCount = count($result['data']);
            $this->info("    ✅ تم مزامنة {$cityCount} مدينة");
            
            // حفظ المدن في قاعدة البيانات
            foreach ($result['data'] as $cityData) {
                \App\Models\SaudiCity::updateOrCreate(
                    ['oto_city_code' => $cityData['id']],
                    $cityData
                );
            }
            
            // مزامنة المناطق لكل مدينة
            $cities = \App\Models\SaudiCity::whereNotNull('oto_city_code')->get();
            $totalDistricts = 0;
            
            foreach ($cities as $city) {
                $this->info("    📍 مزامنة مناطق {$city->name_ar}...");
                $districtsResult = $otoService->getDistricts($city->oto_city_code);
                
                if ($districtsResult['success']) {
                    $districtCount = count($districtsResult['data']);
                    $totalDistricts += $districtCount;
                    
                    foreach ($districtsResult['data'] as $districtData) {
                        \App\Models\District::updateOrCreate(
                            ['oto_district_code' => $districtData['id']],
                            array_merge($districtData, ['city_id' => $city->id])
                        );
                    }
                    
                    $this->info("      ✅ تم مزامنة {$districtCount} منطقة");
                }
                
                sleep(0.5); // تأخير لتجنب rate limits
            }
            
            $this->info("    ✅ تم مزامنة {$totalDistricts} منطقة في المجموع");
        } else {
            $this->error("    ❌ فشل: {$result['message']}");
        }
    }
    
    private function syncAll($otoService)
    {
        $this->syncCities($otoService);
        $this->syncShipments($otoService);
        $this->syncTracking($otoService);
    }
}