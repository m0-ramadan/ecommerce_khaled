<?php
// database/seeders/PlaceSeeder.php

namespace Database\Seeders;

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        // مدن السعودية متوافقة مع OTO API
        $cities = [
            // المنطقة الوسطى
            ['label' => 'الرياض', 'name' => 'Riyadh'],
            
            // المنطقة الغربية
            ['label' => 'جدة', 'name' => 'Jeddah'],
            ['label' => 'مكة المكرمة', 'name' => 'Makkah'],
            ['label' => 'المدينة المنورة', 'name' => 'Madinah'],
            ['label' => 'الطائف', 'name' => 'Taif'],
            ['label' => 'ينبع', 'name' => 'Yanbu'],
            ['label' => 'العلا', 'name' => 'AlUla'],
            ['label' => 'رابغ', 'name' => 'Rabigh'],
            ['label' => 'القنفذة', 'name' => 'Al Qunfudhah'],
            ['label' => 'الليث', 'name' => 'Al Lith'],
            ['label' => 'خليص', 'name' => 'Khulays'],
            ['label' => 'الكامل', 'name' => 'Al Kamil'],
            ['label' => 'بدر', 'name' => 'Badr'],
            ['label' => 'الحناكية', 'name' => 'Al Hanakiyah'],
            ['label' => 'خيبر', 'name' => 'Khaybar'],
            ['label' => 'المهد', 'name' => 'Al Mahd'],
            
            // المنطقة الشرقية
            ['label' => 'الدمام', 'name' => 'Dammam'],
            ['label' => 'الخبر', 'name' => 'Khobar'],
            ['label' => 'الظهران', 'name' => 'Dhahran'],
            ['label' => 'القطيف', 'name' => 'Qatif'],
            ['label' => 'الأحساء', 'name' => 'Al Ahsa'],
            ['label' => 'الجبيل', 'name' => 'Jubail'],
            ['label' => 'حفر الباطن', 'name' => 'Hafar Al Batin'],
            ['label' => 'الخفجي', 'name' => 'Khafji'],
            ['label' => 'رأس تنورة', 'name' => 'Ras Tanura'],
            ['label' => 'بقيق', 'name' => 'Buqayq'],
            ['label' => 'النعيرية', 'name' => 'Nuayriyah'],
            ['label' => 'قرية العليا', 'name' => 'Qaryat Al Ulya'],
            
            // المنطقة الجنوبية
            ['label' => 'أبها', 'name' => 'Abha'],
            ['label' => 'خميس مشيط', 'name' => 'Khamis Mushait'],
            ['label' => 'بيشة', 'name' => 'Bisha'],
            ['label' => 'النماص', 'name' => 'Al Namas'],
            ['label' => 'محايل عسير', 'name' => 'Muhayil Aseer'],
            ['label' => 'ظهران الجنوب', 'name' => 'Dhahran Al Janub'],
            ['label' => 'الجرشي', 'name' => 'Al Jarashi'],
            ['label' => 'تثليث', 'name' => 'Tathleeth'],
            ['label' => 'رجال ألمع', 'name' => 'Rijal Alma'],
            ['label' => 'أحد رفيدة', 'name' => 'Ahad Rafidah'],
            ['label' => 'الحرجة', 'name' => 'Al Harjah'],
            
            // منطقة نجران
            ['label' => 'نجران', 'name' => 'Najran'],
            ['label' => 'شرورة', 'name' => 'Sharurah'],
            ['label' => 'حبونا', 'name' => 'Hubuna'],
            ['label' => 'بدر الجنوب', 'name' => 'Badr Al Janub'],
            ['label' => 'يدمة', 'name' => 'Yadamah'],
            ['label' => 'ثار', 'name' => 'Thar'],
            ['label' => 'خباش', 'name' => 'Khabash'],
            
            // منطقة جازان
            ['label' => 'جازان', 'name' => 'Jazan'],
            ['label' => 'صبيا', 'name' => 'Sabya'],
            ['label' => 'أبو عريش', 'name' => 'Abu Arish'],
            ['label' => 'صامطة', 'name' => 'Samtah'],
            ['label' => 'الحديدة', 'name' => 'Al Hidaydah'],
            ['label' => 'بيش', 'name' => 'Baysh'],
            ['label' => 'الدرب', 'name' => 'Al Darb'],
            ['label' => 'العارضة', 'name' => 'Al Aridah'],
            ['label' => 'الريث', 'name' => 'Al Rayth'],
            ['label' => 'الطوال', 'name' => 'Al Tuwal'],
            ['label' => 'أحد المسارحة', 'name' => 'Ahad Al Musarihah'],
            ['label' => 'العيدابي', 'name' => 'Al Aydabi'],
            ['label' => 'فرسان', 'name' => 'Farasan'],
            
            // منطقة حائل
            ['label' => 'حائل', 'name' => 'Hail'],
            ['label' => 'بقعاء', 'name' => 'Baqaa'],
            ['label' => 'الشنان', 'name' => 'Al Shinan'],
            ['label' => 'الغزالة', 'name' => 'Al Ghazalah'],
            ['label' => 'الحائط', 'name' => 'Al Hait'],
            ['label' => 'موقق', 'name' => 'Mawqaq'],
            ['label' => 'سميراء', 'name' => 'Samira'],
            
            // منطقة تبوك
            ['label' => 'تبوك', 'name' => 'Tabuk'],
            ['label' => 'الوجه', 'name' => 'Al Wajh'],
            ['label' => 'ضباء', 'name' => 'Duba'],
            ['label' => 'أملج', 'name' => 'Umluj'],
            ['label' => 'حقل', 'name' => 'Haql'],
            ['label' => 'البدع', 'name' => 'Al Bada\''],
            ['label' => 'المويلح', 'name' => 'Al Muwaylih'],
            
            // منطقة الجوف
            ['label' => 'سكاكا', 'name' => 'Sakaka'],
            ['label' => 'دومة الجندل', 'name' => 'Dumat Al Jandal'],
            ['label' => 'القريات', 'name' => 'Qurayyat'],
            ['label' => 'طبرجل', 'name' => 'Tubarjal'],
            
            // منطقة الحدود الشمالية
            ['label' => 'عرعر', 'name' => 'Arar'],
            ['label' => 'طريف', 'name' => 'Turaif'],
            ['label' => 'رفحاء', 'name' => 'Rafha'],
            ['label' => 'العويقيلة', 'name' => 'Al Uwayqilah'],
            
            // منطقة القصيم
            ['label' => 'بريدة', 'name' => 'Buraidah'],
            ['label' => 'عنيزة', 'name' => 'Unaizah'],
            ['label' => 'الرس', 'name' => 'Al Rass'],
            ['label' => 'المذنب', 'name' => 'Al Midhnab'],
            ['label' => 'البكيرية', 'name' => 'Al Bukayriyah'],
            ['label' => 'البدائع', 'name' => 'Al Badai'],
            ['label' => 'الأسياح', 'name' => 'Al Asyah'],
            ['label' => 'النبهانية', 'name' => 'Al Nabhaniyah'],
            ['label' => 'عيون الجواء', 'name' => 'Uyun Al Jiwa'],
            ['label' => 'رياض الخبراء', 'name' => 'Riyad Al Khabra'],
            
            // منطقة الباحة
            ['label' => 'الباحة', 'name' => 'Al Baha'],
            ['label' => 'بلجرشي', 'name' => 'Baljurashi'],
            ['label' => 'المندق', 'name' => 'Al Mandaq'],
            ['label' => 'المخواة', 'name' => 'Al Mikhwah'],
            ['label' => 'العقيق', 'name' => 'Al Aqiq'],
            ['label' => 'قلوة', 'name' => 'Qilwah'],
            ['label' => 'الحجرة', 'name' => 'Al Hajrah'],
            
            // المزيد من المدن الرئيسية
            ['label' => 'الدوادمي', 'name' => 'Al Duwadimi'],
            ['label' => 'الخرج', 'name' => 'Al Kharj'],
            ['label' => 'المجمعة', 'name' => 'Al Majmaah'],
            ['label' => 'وادي الدواسر', 'name' => 'Wadi Al Dawasir'],
            ['label' => 'الدلم', 'name' => 'Al Dilam'],
            ['label' => 'الحوطة', 'name' => 'Al Hawtah'],
            ['label' => 'الحريق', 'name' => 'Al Hariq'],
            ['label' => 'ثادق', 'name' => 'Thadiq'],
            ['label' => 'الغاط', 'name' => 'Al Ghat'],
            ['label' => 'شقراء', 'name' => 'Shaqra'],
            ['label' => 'عفيف', 'name' => 'Afif'],
            ['label' => 'القويعية', 'name' => 'Al Quwayiyah'],
            ['label' => 'حوطة بني تميم', 'name' => 'Hotat Bani Tamim'],
        ];

        // إدراج المدن
        foreach ($cities as $cityData) {
            $city = Place::create([
                'label' => $cityData['label'],
                'name' => $cityData['name'],
                'parent_id' => null
            ]);

            // إضافة الأحياء للمدن الكبيرة فقط
            $this->seedImportantDistricts($city);
        }
    }

    /**
     * إضافة الأحياء للمدن الكبيرة
     */
    private function seedImportantDistricts($city)
    {
        $districts = [];

        switch ($city->name) {
            case 'Riyadh':
                $districts = [
                    'Al Malaz', 'Al Olaya', 'Al Sulaimaniyah', 'Al Rabwah',
                    'Al Rawdah', 'Al Narjis', 'Al Yasmin', 'Hittin',
                    'Al Quds', 'Al Nuzhah', 'Al Woroud', 'Al Safarat',
                    'Al Murabba', 'Al Dirah', 'Al Manfuhah', 'Al Shifa',
                    'Al Batha', 'Al Aziziyah', 'Al Khalidiyah', 'Al Wadi'
                ];
                break;
                
            case 'Jeddah':
                $districts = [
                    'Al Hamra', 'Al Shati', 'Al Rawdah', 'Al Nahdah',
                    'Al Salamah', 'Al Faysaliyah', 'Al Zahra', 'Al Andalus',
                    'Al Khalidiyah', 'Al Sharafiyyah', 'Al Balad', 'Al Murjan',
                    'Al Tayebah', 'Al Naeem', 'Al Mohammadiyah', 'Al Basateen'
                ];
                break;
                
            case 'Makkah':
                $districts = [
                    'Al Aziziyah', 'Al Zahir', 'Al Shoqiyah', 'Al Jamiah',
                    'Al Khalidiyah', 'Al Rawdah', 'Al Nuzhah', 'Al Hindawiyah',
                    'Al Sharayie', 'Al Awali', 'Al Tundubawi', 'Al Iskan'
                ];
                break;
                
            case 'Dammam':
                $districts = [
                    'Al Khalidiyah', 'Al Faysaliyah', 'Al Rawdah', 'Al Shifa',
                    'Al Nuzhah', 'Al Zuhur', 'Al Dana', 'Al Lulu', 'Al Bandariyah',
                    'Al Mazruiyah', 'Al Salam', 'Al Jawharah', 'Al Manar'
                ];
                break;
                
            case 'Khobar':
                $districts = [
                    'Al Khobar North', 'Al Khobar South', 'Al Olaya', 'Al Rakah',
                    'Al Dawhah', 'Al Lulu', 'Al Aqrabiyah', 'Al Thuqbah',
                    'Al Budur', 'Al Yasmin', 'Al Mohammadiyah', 'Al Danah'
                ];
                break;
        }

        if (!empty($districts)) {
            foreach ($districts as $districtName) {
                Place::create([
                    'label' => $districtName,
                    'name' => $districtName, // الأحياء بالإنجليزي فقط للتوافق
                    'parent_id' => $city->id
                ]);
            }
        }
    }
}