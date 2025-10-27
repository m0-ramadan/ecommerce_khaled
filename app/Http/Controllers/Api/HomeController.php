<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Offer;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Service;
use App\Models\Shipment;
use App\Models\PlaceType;
use App\Models\WalletDetail;

use App\Models\ShipmentsCompany;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\PlaceResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

use App\Traits\FirebaseNotificationTrait;

class HomeController extends Controller
{
    use TranslatableTrait;

    public function home()
    {

        $user = Auth::guard('sanctum')->user();
        $offers = Offer::all();
        $sliders = Slider::where('type', 1)->orderBy('item_order')->get();
        $products = Product::orderBy('created_at', 'asc')->get();
        $restaurants = PlaceType::where('type_name', 'Restaurant')->first();
        $notifications = Notification::where('notifiable_type', get_class($user))
            ->where('notifiable_id', $user->id)
            ->latest()
            ->get();
        $services = Service::whereNotNull('parent_id')->get();

        return response()->json([
            'status' => true,
            'message' => $this->translate('home_data_retrieved'),
            'sliders' => SliderResource::collection($sliders),
            'offers' => OfferResource::collection($offers),
            'products' => ProductResource::collection($products),
            'restaurants' => PlaceResource::collection($restaurants->places ?? collect()),
            'services' => ServiceResource::collection($services),
            'notification_count' => $notifications->count(),
        ], 200);
    }



    public function home_pers()
    {
        $user = auth()->user();
        $tasks = Task::where('person_id', $user->id)->take(4)->get();
        $sliders = Slider::where('type', 1)->orderBy('item_order')->get();

        $statusCounts = Shipment::where('person_id', $user->id)->whereIn('status_id', range(0, 8))->groupBy('status_id')->selectRaw('status_id, count(*) as count')->get()->keyBy('status_id');
        $totalStatusCount = $statusCounts->filter(function ($item) {
            return in_array($item->status_id, range(1, 8));
        })->sum('count');

        $statusCountsArray = collect(range(0, 8))->map(function ($status) use ($statusCounts, $totalStatusCount) {
            // if ($status === 0) {
            //     return [
            //         'status' => $status,
            //         'count' => $totalStatusCount,
            //     ];
            // }

            return [
                'status' => $status,
                'count' => $statusCounts->has($status) ? $statusCounts->get($status)->count : 0,
            ];
        });


        $shipments = Shipment::where('status_id', '!=', 5)->where('person_id', Auth::id())->get();
        //    $totalPrice = max(0, $shipments->where('status_id', 2)->sum('price'));
        $totalPrice = max(0, $shipments->sum('price'));

        //$amount=0;
        $wailt = WalletDetail::where('model_id', $user->id)->where('model_type', get_class($user))->first();
        if ($wailt)
            $amount = $wailt->amount;
        return response()->json([
            'status' => true,
            'message' => $this->translate('home_pers_data_retrieved'),
            'sliders' => SliderResource::collection($sliders),
            'tasks' => TaskResource::collection($tasks),
            'status_counts' => $statusCountsArray,
            'total_price' => $totalPrice,
            'currency' => $user->country->currency_id,
        ], 200);
    }

    public function shipmentdetails()
    {
        $shipments = Shipment::where('person_id', auth()->user()->id)->count();

        $statusCounts = Shipment::where('person_id', auth()->user()->id)
            ->groupBy('status_id')
            ->selectRaw('status_id, count(*) as count')
            ->get()
            ->keyBy('status_id');

        $statusCountsArray = collect(range(1, 8))->map(function ($status) use ($statusCounts) {
            return [
                'status' => $status,
                'count' => $statusCounts->has($status) ? $statusCounts->get($status)->count : 0,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => $this->translate('shipment_details_retrieved'),
            'shipments_count' => $shipments,
            'status_counts' => $statusCountsArray,
        ], 200);
    }

    public function getOffer($id)
    {
        try {
            $offer = Offer::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => $this->translate('offer_retrieved'),
                'data' => new OfferResource($offer),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $this->translate('offer_not_found'),
            ], 404);
        }
    }

    public function getService($id)
    {
        try {
            $service = Service::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => $this->translate('service_retrieved'),
                'data' => new ServiceResource($service),
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => $this->translate('service_not_found'),
            ], 404);
        }
    }

    public function valueWallet()
    {
        $user = auth()->user();

        $wallet = WalletDetail::where('model_id', $user->id)
            ->where('model_type', get_class($user))
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'تم استرجاع البيانات بنجاح',
            'total_price' => $wallet?->amount ?? 0,
        ], 200);
    }





    public function updateNotifactions(Request $request, $id)
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => $this->translate('unauthorized'),
                ], 401);
            }

            $notification = $user->notifications()->find($id);
            if (!$notification) {
                return response()->json([
                    'status' => false,
                    'message' => $this->translate('notification_not_found'),
                ], 404);
            }

            DB::beginTransaction();

            $notification->update([
                'is_active' => 1
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "تم تحديث الحالة بنجاح"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_deleting_notification', ['error' => $e->getMessage()]),
            ], 500);
        }
    }


    public function totalnotifactions(Request $request)
    {
        try {
            //$user= auth()->user();
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $notifications = $user->notifications()->where('is_active', 0)->count();

            return response()->json([
                'message' => 'اجمالى عدد الاشعارات',

                'total_notifications' => $notifications,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving transfer statistics: ' . $e->getMessage(),

            ], 500);
        }
    }
}
