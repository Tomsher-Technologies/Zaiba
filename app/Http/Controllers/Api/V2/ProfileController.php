<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\City;
use App\Models\Country;
use App\Http\Resources\V2\AddressCollection;
use App\Models\Address;
use App\Http\Resources\V2\CitiesCollection;
use App\Http\Resources\V2\CountriesCollection;
use App\Models\Order;
use App\Models\Upload;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\OrderTracking;
use Illuminate\Http\Request;
use App\Models\Cart;
use Hash;
use Illuminate\Support\Facades\File;
use Storage;

class ProfileController extends Controller
{
    public function counters()
    {
        return response()->json([
            'cart_item_count' => Cart::where('user_id', auth()->user()->id)->count(),
            'wishlist_item_count' => Wishlist::where('user_id', auth()->user()->id)->count(),
            'order_count' => Order::where('user_id', auth()->user()->id)->count(),
        ]);
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if(!$user){
            return response()->json([
                'result' => false,
                'message' => translate("User not found.")
            ]);
        }
        $user->name = $request->name;

        if ($request->password != "") {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate("Profile information updated")
        ]);
    }

    public function update_device_token(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if(!$user){
            return response()->json([
                'result' => false,
                'message' => translate("User not found.")
            ]);
        }

        $user->device_token = $request->device_token;


        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate("device token updated")
        ]);
    }

    public function updateImage(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if(!$user){
            return response()->json([
                'result' => false,
                'message' => translate("User not found."),
                'path' => ""
            ]);
        }

        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
        );

        try {
            $image = $request->image;
            $request->filename;
            $realImage = base64_decode($image);

            $dir = public_path('uploads/all');
            $full_path = "$dir/$request->filename";

            $file_put = file_put_contents($full_path, $realImage); // int or false

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "File uploading error",
                    'path' => ""
                ]);
            }


            $upload = new Upload;
            $extension = strtolower(File::extension($full_path));
            $size = File::size($full_path);

            if (!isset($type[$extension])) {
                unlink($full_path);
                return response()->json([
                    'result' => false,
                    'message' => "Only image can be uploaded",
                    'path' => ""
                ]);
            }


            $upload->file_original_name = null;
            $arr = explode('.', File::name($full_path));
            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }

            //unlink and upload again with new name
            unlink($full_path);
            $newFileName = rand(10000000000, 9999999999) . date("YmdHis") . "." . $extension;
            $newFullPath = "$dir/$newFileName";

            $file_put = file_put_contents($newFullPath, $realImage);

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "Uploading error",
                    'path' => ""
                ]);
            }

            $newPath = "uploads/all/$newFileName";

            if (env('FILESYSTEM_DRIVER') == 's3') {
                Storage::disk('s3')->put($newPath, file_get_contents(base_path('public/') . $newPath));
                unlink(base_path('public/') . $newPath);
            }

            $upload->extension = $extension;
            $upload->file_name = $newPath;
            $upload->user_id = $user->id;
            $upload->type = $type[$upload->extension];
            $upload->file_size = $size;
            $upload->save();

            $user->avatar_original = $upload->id;
            $user->save();



            return response()->json([
                'result' => true,
                'message' => translate("Image updated"),
                'path' => api_asset($upload->id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage(),
                'path' => ""
            ]);
        }
    }

    // not user profile image but any other base 64 image through uploader
    public function imageUpload(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if(!$user){
            return response()->json([
                'result' => false,
                'message' => translate("User not found."),
                'path' => "",
                'upload_id' => 0
            ]);
        }

        $type = array(
            "jpg" => "image",
            "jpeg" => "image",
            "png" => "image",
            "svg" => "image",
            "webp" => "image",
            "gif" => "image",
        );

        try {
            $image = $request->image;
            $request->filename;
            $realImage = base64_decode($image);

            $dir = public_path('uploads/all');
            $full_path = "$dir/$request->filename";

            $file_put = file_put_contents($full_path, $realImage); // int or false

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "File uploading error",
                    'path' => "",
                    'upload_id' => 0
                ]);
            }


            $upload = new Upload;
            $extension = strtolower(File::extension($full_path));
            $size = File::size($full_path);

            if (!isset($type[$extension])) {
                unlink($full_path);
                return response()->json([
                    'result' => false,
                    'message' => "Only image can be uploaded",
                    'path' => "",
                    'upload_id' => 0
                ]);
            }


            $upload->file_original_name = null;
            $arr = explode('.', File::name($full_path));
            for ($i = 0; $i < count($arr) - 1; $i++) {
                if ($i == 0) {
                    $upload->file_original_name .= $arr[$i];
                } else {
                    $upload->file_original_name .= "." . $arr[$i];
                }
            }

            //unlink and upload again with new name
            unlink($full_path);
            $newFileName = rand(10000000000, 9999999999) . date("YmdHis") . "." . $extension;
            $newFullPath = "$dir/$newFileName";

            $file_put = file_put_contents($newFullPath, $realImage);

            if ($file_put == false) {
                return response()->json([
                    'result' => false,
                    'message' => "Uploading error",
                    'path' => "",
                    'upload_id' => 0
                ]);
            }

            $newPath = "uploads/all/$newFileName";

            if (env('FILESYSTEM_DRIVER') == 's3') {
                Storage::disk('s3')->put($newPath, file_get_contents(base_path('public/') . $newPath));
                unlink(base_path('public/') . $newPath);
            }

            $upload->extension = $extension;
            $upload->file_name = $newPath;
            $upload->user_id = $user->id;
            $upload->type = $type[$upload->extension];
            $upload->file_size = $size;
            $upload->save();

            return response()->json([
                'result' => true,
                'message' => translate("Image updated"),
                'path' => api_asset($upload->id),
                'upload_id' => $upload->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => $e->getMessage(),
                'path' => "",
                'upload_id' => 0
            ]);
        }
    }

    public function checkIfPhoneAndEmailAvailable()
    {


        $phone_available = false;
        $email_available = false;
        $phone_available_message = translate("User phone number not found");
        $email_available_message = translate("User email  not found");

        $user = User::find(auth()->user()->id);

        if ($user->phone != null || $user->phone != "") {
            $phone_available = true;
            $phone_available_message = translate("User phone number found");
        }

        if ($user->email != null || $user->email != "") {
            $email_available = true;
            $email_available_message = translate("User email found");
        }
        return response()->json(
            [
                'phone_available' => $phone_available,
                'email_available' => $email_available,
                'phone_available_message' => $phone_available_message,
                'email_available_message' => $email_available_message,
            ]
        );
    }

    public function orderList(Request $request){
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        $user = User::find($user_id);
        if($user){
            $sort_search = null;
            $delivery_status = null;
            $limit = $request->limit ? $request->limit : 10;
            $offset = $request->offset ? $request->offset : 0;
            // $date = $request->date;

            $orders = Order::with(['orderDetails'])->select('id','code','delivery_status','payment_type','coupon_code','grand_total','created_at')->orderBy('id', 'desc')->where('user_id',$user_id);
            if ($request->has('search')) {
                $sort_search = $request->search;
                $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
            }
            if ($request->delivery_status != null) {
                $orders = $orders->where('delivery_status', $request->delivery_status);
                $delivery_status = $request->delivery_status;
            }
            // if ($date != null) {
            //     $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
            // }
            
            $total_count = $orders->count();
            $orderList = $orders->skip($offset)->take($limit)->get();
            if($orderList){
                foreach($orderList as $ol){
                    $order_data = [];
                    $ol->order_date = date('M d,Y', strtotime($ol->created_at));
                    foreach($ol->orderDetails as $key => $oDetails){
                        $order_data[] = [
                            "product_id" => $oDetails->product->id,
                            "name" => $oDetails->product->name,
                            "slug" => $oDetails->product->slug,
                            "sku" => $oDetails->product_stock->sku,
                            "image" =>  ($oDetails->product_stock->image != NULL && $oDetails->product_stock->image != '0') ? get_product_image($oDetails->product_stock->image,'300') : get_product_image($oDetails->product->thumbnail_img,'300'),
                            "attributes" => json_decode($oDetails->variation),
                            "price" => $oDetails->price
                        ];
                    }
        
                    unset($ol->orderDetails);
                    $ol->products = $order_data;
                }
            }

            $data['orders'] = $orderList;
            
            $data['next_offset'] = $offset + $limit;
            $data['total_count'] = $total_count;

            return response()->json(['status' => true,'message' => 'Data fetched successfully','data' => $data]);   
        }else{
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }
    }

    public function orderDetails(Request $request){
        $order_code = $request->order_code ?? '';
        $user_id = (!empty(auth('sanctum')->user())) ? auth('sanctum')->user()->id : '';
        $default_return_time = get_setting('default_return_time') ?? 0;
        if($order_code != ''){
            $order = Order::where('code',$order_code)->where('user_id',$user_id)->first();
            if($order){
                $details['id']                      = $order->id ?? '';
                $details['code']                    = $order->code ?? '';
                $details['user_id']                 = $order->user_id ?? '';
                $details['shipping_address']        = json_decode($order->shipping_address ?? '');
                $details['billing_address']         = json_decode($order->billing_address ?? '');
                $details['order_notes']             = $order->order_notes ?? '';
                $details['shipping_type']           = ($order->shipping_type == 'free_shipping') ? 'Free Shipping' : 'Paid Shipping';
                $details['shipping_cost']           = $order->shipping_cost ?? '';
                $details['delivery_status']         = $order->delivery_status ?? '';
                $details['payment_type']            = $order->payment_type ?? '';
                $details['payment_status']          = $order->payment_status ?? '';
                $details['tax']                     = $order->tax ?? '';
                $details['coupon_code']             = $order->coupon_code ?? '';
                $details['sub_total']               = $order->sub_total ?? '';
                $details['coupon_discount']         = $order->coupon_discount ?? '';
                $details['offer_discount']          = $order->offer_discount ?? '';
                $details['grand_total']             = $order->grand_total ?? '';
                $details['delivery_completed_date'] = $order->delivery_completed_date ?? '';
                $details['order_date']                    = date('d-m-Y h:i A', $order->date);
                $details['cancel_request']          = $order->cancel_request;
                $details['estimated_delivery_date'] = ($order->delivery_status != 'delivered' && $order->delivery_status != 'cancelled' && $order->estimated_delivery != NULL && $order->estimated_delivery != '0000-00-00') ? date('d-m-Y', strtotime($order->estimated_delivery)) : '';
                $details['products'] = [];
                if($order->orderDetails){
                    foreach($order->orderDetails as $product){
                        $requestCount = ($product->refund_request) ? 1 : 0 ;
                        $return_expiry = null;
                        if($product->delivery_date != null && $default_return_time != 0 ){
                            $return_expiry = getDatePlusXDays($product->delivery_date, $default_return_time);
                        }

                        $details['products'][] = array(
                            'id' => $product->id ?? '',
                            'product_id' => $product->product_id ?? '',
                            'name' => $product->product->name ?? '',
                            'sku' => $product->product_stock->sku ?? '',
                            'slug' => $product->product->slug ?? '',
                            'original_price' => $product->og_price ?? '',
                            'offer_price' => $product->offer_price ?? '',
                            'quantity' => $product->quantity ?? '',
                            'total_price' => $product->price ?? '',
                            'thumbnail_img' => ($product->product_stock->image != NULL && $product->product_stock->image != '0') ? get_product_image($product->product_stock->image,'300') : get_product_image($product->product->thumbnail_img,'300'),
                            "attributes" => json_decode($product->variation),
                            // 'return_refund' => $product->product->return_refund ?? ''
                        );
                    }
                }

                $tracks = OrderTracking::where('order_id', $order->id)->orderBy('id','ASC')->get();
                $track_list = [];
                if ($tracks) {
                    foreach ($tracks as $key=>$value) {
                        $temp = array();
                        $temp['id'] = $value->id;
                        $temp['status'] = $value->status;
                        $temp['date'] = date("d-m-Y H:i a", strtotime($value->status_date));
                        $track_list[] = $temp;
                    }
                }    
                $details['timeline'] = $track_list;
                
                return response()->json([
                    'status' => true,
                    'message' => 'Order found',
                    'data'=> $details
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'No Order Found!',
                ], 200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ]);
        }
    }
}
