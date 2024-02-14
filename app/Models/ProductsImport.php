<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Models\Designs;
use App\Models\GoldPrices;
use App\Models\DesignCategories;
use App\Models\Products\ProductTabs;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Auth;
use Carbon\Carbon;
use File;
use Image;
use Mpdf\Tag\Tr;
use Storage;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;

    private $year = 0;
    private $month = 0;

    public function __construct()
    {
        $this->year = Carbon::now()->year;
        $this->month =  Carbon::now()->format('m');
    }

    public function collection(Collection $rows)
    {
        // echo '<pre>';
        $designs = Designs::all();
        $designCategories = DesignCategories::all();
        $categories = Category::all();

        $today_gold_rate    = GoldPrices::first()->toArray();
        $gold_purity        = [18 => '18_k', 21 => '21_k', 22 => '22_k', 24 => '24_k'];
        
        foreach ($rows as $row) {
            
            // print_r($row);
            $imageArray = array_filter($row->toArray(), function($value,$key) {
                return (strpos($key, 'url') === 0 && trim($value) !== '' );
            }, ARRAY_FILTER_USE_BOTH);
            // print_r($imageArray);
            // // print_r($row);
            // echo '******************************************************************************************';
            $tabArray = array_filter($row->toArray(), function($key) {
                return strpos($key, 'tab') === 0;
            }, ARRAY_FILTER_USE_KEY);
            // print_r($tabArray);
            
            $productTabs = [];
            $productDescription = '';
        
            $design = $design_category = null;
            $parent_id = 0;
            $main_category_id = 0;

            if (isset($row['design'])) {
                $newdesign = trim($row['design']);
                $design = $designs->where('name',$newdesign)->first();
                if($design){
                    $design->id;
                }else{
                    $slug = \Str::slug($newdesign);
                    $design = Designs::firstOrNew(array('name' => $newdesign,'slug' => $slug));
                    $design->name = $newdesign;
                    $design->slug = $slug;
                    $design->save();
                }
            }

            if (isset($row['design_category'])) {
                $newdesign_category = trim($row['design_category']);
                $design_category = $designCategories->where('name',$newdesign_category)->first();
                if($design_category){
                    $design_category->id;
                }else{
                    $type = \Str::slug($newdesign_category);
                    $design_category = DesignCategories::firstOrNew(array('name' => $newdesign_category,'type' => $type));
                    $design_category->name = $newdesign_category;
                    $design_category->type = $type;
                    $design_category->save();
                }
            }

            if (isset($row['category'])) {
                $category = explode(':', $row['category']);
                foreach ($category as $key => $cat) {
                    $cat = trim($cat);
                    $c = $categories->where('name', 'LIKE', $cat)->where(
                        'parent_id',
                        $parent_id
                    )->first();

                    if ($c) {
                        $parent_id = $c->id;
                    } else {
                        $c_new = Category::create([
                            'name' => $cat,
                            'parent_id' => $parent_id,
                            'level' => $key + 1,
                            'slug' => $this->categorySlug($cat),
                        ]);
                        $categories->push($c_new);
                        $parent_id = $c_new->id;
                    }

                    if($key == 0){
                        $main_category_id = $parent_id;
                    }
                }
            }

            $parent_sku = $this->cleanSKU(trim($row['parent_sku']));
            $current_sku = $this->cleanSKU(trim($row['sku']));


            $price = $goldRate = 0;
            $offertag       = '';
            if($parent_sku != null){
                $productId = Product::where(['sku' => $parent_sku])->get()->first();
                $purity         = $productId->purity;
            }else{
                $purity         = trim($row['purity']);
            }
            
            $metal_weight   = trim($row['metal_weight']);
            $stone_price    = trim($row['stone_price']);
            $making_charge  = trim($row['making_charge']);

            if(array_key_exists($purity, $gold_purity)){
                $goldRate = isset($today_gold_rate[$gold_purity[$purity]]) ? $today_gold_rate[$gold_purity[$purity]] : 0;
            }

            $making_price_type = 0;
            $making_type = trim($row['making_price_type']);
            if(strtolower($making_type) == 'gram amount'){
                $making_price_type = 1;
            }else if(strtolower($making_type) == 'gram percentage'){
                $making_price_type = 2;
            }else if(strtolower($making_type) == 'pc rate'){
                $making_price_type = 3;
            }

            $total_making_charge = 0; 
            $metalPrice = 0;
            if($goldRate != 0){
                $metalPrice         = $metal_weight * $goldRate;
               
                if($making_price_type == 1){       // Per gram amount
                    $total_making_charge = $metal_weight * $making_charge;
                }elseif($making_price_type == 2){       // Per gram percentage
                    $total_making_charge = ($metalPrice / 100) * $making_charge;
                }elseif($making_price_type == 3){       // PC Rate
                    $total_making_charge = $making_charge;
                }
            }

            $productOrgPrice = (float)$metalPrice + (float)$stone_price + (float)$total_making_charge;
            $discountPrice = $productOrgPrice;

            // echo '<br><br><br> metalPrice =  '.$metalPrice;
            // echo '<br> stone_price =  '.$stone_price;
            // echo '<br> total_making_charge =  '.$total_making_charge;
            // echo '<br> productOrgPrice =  '.$productOrgPrice;
            // echo '<br> discountPrice =  '.$discountPrice;
            // die;
            $mainImage = $galleryImage = $mainImageUploaded = $galleryImageUploaded ='';

            if($parent_sku != null){
                $productId = Product::where(['sku' => $parent_sku])->get()->first();
                if($productId){

                    $productId->product_type        = 1;
                    $productId->save();

                    if (strtotime(date('d-m-Y H:i:s')) >= $productId->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $productId->discount_end_date) {
                        if ($productId->discount_type == 'percent') {
                            $discountPrice = $productOrgPrice - (($productOrgPrice * $productId->discount) / 100);
                            $offertag = $productId->discount . '% OFF';
                        } elseif ($productId->discount_type == 'amount') {
                            $discountPrice = $productOrgPrice - $productId->discount;
                            $offertag = 'AED '.$productId->discount.' OFF';
                        }
                    }
                    
                    $productStock = ProductStock::where(['product_id' => $productId->id,'sku' => $current_sku])->get()->first();
                    if(!$productStock){
                        $productStock                       = new ProductStock;
                        $productStock->product_id           = $productId->id;
                        $productStock->sku                  = $current_sku;
                    }
                    
                    $productStock->description          = trim($row['description']);
                    $productStock->metal_weight         = $metal_weight;
                    $productStock->stone_available      = ($row['stone_count'] == 0 || $row['stone_count'] == '') ? 0 : 1;
                    $productStock->stone_type           = trim($row['stone_type']);
                    $productStock->stone_count          = trim($row['stone_count']);
                    $productStock->stone_weight         = trim($row['stone_weight']);
                    $productStock->stone_price          = $stone_price;
                    $productStock->making_price_type    = $making_price_type;
                    $productStock->making_charge        = $making_charge;
                    $productStock->price                = $productOrgPrice;
                    $productStock->offer_price          = $discountPrice;
                    $productStock->metal_price_break    = $metalPrice;
                    $productStock->making_price_break   = $total_making_charge;
                    $productStock->offer_tag            = $offertag;
                    $productStock->qty                  = trim($row['quantity']);
                    // $productStock->image= 0;
                    $productStock->save();

                    if(!empty($imageArray)){
                        if(isset($imageArray['url_1'])){
                            $mainImage = $imageArray['url_1'];
                            unset($imageArray['url_1']);
                        }
                        $galleryImage = $imageArray;
                    }

                    if($mainImage != ''){
                        // $mainImage = base_path('product_images').'/'.$mainImage;
                        $mainImageUploaded = $this->downloadAndResizeImage('varient', $mainImage, $current_sku, true);
                    }

                    if ($mainImageUploaded) {
                        $productStock->image = $mainImageUploaded;
                    }
                    $productStock->save();
                }
            } else{
                $productId = Product::where(['sku' => $current_sku])->get()->first();

                $discount_price = $discount_type = $discount_start_date = $discount_end_date = NULL;
                if (isset($row['discount_price']) && isset($row['discount_type']) && isset($row['discount_start_date']) && isset($row['discount_end_date'])) {
                    $discount_price = $row['discount_price'];

                    if(strtolower($row['discount_type']) == 'percentage'){
                        $discount_type = 'percent';
                    }elseif(strtolower($row['discount_type']) == 'fixed'){
                        $discount_type = 'amount';
                    }
                    $start = Date::excelToDateTimeObject($row['discount_start_date'])->format('Y-m-d 00:00:00');
                    $end = Date::excelToDateTimeObject($row['discount_end_date'])->format('Y-m-d 23:59:00');

                    $discount_start_date = strtotime($start);
                    $discount_end_date = strtotime($end);
                }
            
                if($productId){
                    if (isset($row['product_name'])) {
                        $productId->name = trim($row['product_name']);
                    }

                    $productId->slug                = $this->productSlug(trim($row['product_name']).' '.$current_sku);
                    $productId->product_type        = 0;
                    $productId->discount            = $discount_price;
                    $productId->discount_type       = $discount_type;
                    $productId->discount_start_date = $discount_start_date;
                    $productId->discount_end_date   = $discount_end_date;

                    if (isset($row['category'])) {
                        $productId->category_id = $parent_id;
                    }
                    if (isset($design_category)) {
                        $productId->design_category_id = $design_category->type;
                    }
                    if (isset($design)) {
                        $productId->design_id = $design->id;
                    }
                    if (isset($row['keywords'])) {
                        $productId->tags = trim($row['keywords']);
                    }
                    if (isset($row['metal_type'])) {
                        $productId->metal_type = trim($row['metal_type']);
                    }
                    if (isset($row['purity'])) {
                        $productId->purity = $purity;
                    }
                    $productId->updated_by = Auth::user()->id;
                    $productId->save();
                }else{
                    $productId = Product::create([
                        'sku' => $current_sku,
                        'name' => trim($row['product_name']) ?? '',
                        'slug' => $this->productSlug(trim($row['product_name']).' '.$current_sku),
                        'product_type' => 0,
                        'discount' => $discount_price,
                        'discount_type' => $discount_type,
                        'discount_start_date' => $discount_start_date,
                        'discount_end_date' => $discount_end_date,
                        'category_id' => $parent_id,
                        'design_category_id' => $design_category->type,
                        'design_id' => $design->id,
                        'tags' => trim($row['keywords']) ?? NULL,
                        'metal_type' => trim($row['metal_type']),
                        'purity' => $purity,
                        'published' => $row['status'] ?? 0,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id, 
                    ]);
                }

                if($productId){
                    if (strtotime(date('d-m-Y H:i:s')) >= $productId->discount_start_date && strtotime(date('d-m-Y H:i:s')) <= $productId->discount_end_date) {
                        if ($productId->discount_type == 'percent') {
                            $discountPrice = $productOrgPrice - (($productOrgPrice * $productId->discount) / 100);
                            $offertag = $productId->discount . '% OFF';
                        } elseif ($productId->discount_type == 'amount') {
                            $discountPrice = $productOrgPrice - $productId->discount;
                            $offertag = 'AED '.$productId->discount.' OFF';
                        }
                    }

                    $productStock = ProductStock::where(['product_id' => $productId->id,'sku' => $current_sku])->get()->first();
                    if(!$productStock){
                        $productStock                       = new ProductStock;
                        $productStock->product_id           = $productId->id;
                        $productStock->sku                  = $current_sku;
                    }
                    
                    $productStock->description          = trim($row['description']);
                    $productStock->metal_weight         = $metal_weight;
                    $productStock->stone_available      = ($row['stone_count'] == 0 || $row['stone_count'] == '') ? 0 : 1;
                    $productStock->stone_type           = trim($row['stone_type']);
                    $productStock->stone_count          = trim($row['stone_count']);
                    $productStock->stone_weight         = trim($row['stone_weight']);
                    $productStock->stone_price          = $stone_price;
                    $productStock->making_price_type    = $making_price_type;
                    $productStock->making_charge        = $making_charge;
                    $productStock->price                = $productOrgPrice;
                    $productStock->offer_price          = $discountPrice;
                    $productStock->metal_price_break    = $metalPrice;
                    $productStock->making_price_break   = $total_making_charge;
                    $productStock->offer_tag            = $offertag;
                    $productStock->qty                  = trim($row['quantity']);
                    // $productStock->image= 0;
                    $productStock->save();


                    if(!empty($tabArray)){
                        foreach($tabArray as $key=>$tba){
                            $key = Str::after($key,'tab');
                            if($tba != null && $tba != ''){
                                $productTabs[] = [
                                    'product_id' => $productId->id,
                                    'heading'      => ucfirst(str_replace('_', ' ',$key)),
                                    'content'   => $tba,
                                ];
                            }
                        }
                    }
                    if(!empty($productTabs)){
                        ProductTabs::where('product_id', $productId->id)->delete();
                        ProductTabs::insert($productTabs);
                    }

                    if(!empty($imageArray)){
                        if(isset($imageArray['url_1'])){
                            $mainImage = $imageArray['url_1'];
                            unset($imageArray['url_1']);
                        }
                        $galleryImage = $imageArray;
                    }

                    if($mainImage != ''){
                        // $mainImage = base_path('product_images').'/'.$mainImage;
                        $mainImageUploaded = $this->downloadAndResizeImage('main_product', $mainImage, $current_sku, true);
                    }

                    if (!empty($galleryImage)) {
                        $galleryImage = $this->downloadGallery($galleryImage, $current_sku);
                        $galleryImageUploaded = implode(',', $galleryImage);
                    }
        
                    if ($mainImageUploaded) {
                        $productId->thumbnail_img = $mainImageUploaded;
                    }
                    if ($galleryImageUploaded) {
                        $productId->photos = $galleryImageUploaded;
                    }
                    $productId->save();
                }
            }
        }
        // die;
        flash(translate('Products imported successfully'))->success();
    }

    public function model(array $row)
    {
        ++$this->rows;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function productSlug($name)
    {
        $slug = Str::slug($name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        return $slug;
    }
    public function categorySlug($name)
    {
        $slug = Str::slug($name, '-');
        $same_slug_count = Category::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        return $slug;
    }

    public function rules(): array
    {
        return [
            // 'product_code' => function ($attribute, $value, $onFailure) {
            //     if (!is_numeric($value)) {
            //         $onFailure('Unit price is not numeric');
            //     }
            // }
            // 'sku' => 'required',
        ];
    }

    public function downloadGallery($urls, $sku)
    {
        $i = 0;
        $data = [];
        foreach ($urls as $index => $url) {
            // $url = base_path('product_images').'/'.$url;
            $response = Http::head($url);
            if ($response->ok()) {
                $data[] = $this->downloadAndResizeImage('main_product', $url, $sku, false, $i + 1);
                $i++;
            }
        }
        return $data;
    }

    public function downloadAndResizeImage($product_type, $imageUrl, $sku, $mainImage = false, $count = 1, $update = false)
    {                                                   
        $data_url = '';

        try {
            // $ext = $imageUrl->getClientOriginalExtension();
            $ext = Str::of($imageUrl)->afterLast('.');
            if($product_type == 'main_product'){
                $path = 'products/' . Carbon::now()->year . '/' . Carbon::now()->format('m') . '/' . $sku . '/main/';
            }else{
                $path = 'products/' . Carbon::now()->year . '/' . Carbon::now()->format('m') . '/' . $sku . '/';
            }

            if ($mainImage) {
                $filename = $path . $sku . '.' . $ext;
            } else {
                $n = $sku . '_gallery_' .  $count;
                $filename = $path . $n . '.' . $ext;
            }

            $response = Http::head($imageUrl);
            if ($response->ok()) {
                // Download the image from the given URL
                $imageContents = file_get_contents($imageUrl);

                // Save the original image in the storage folder
                Storage::disk('public')->put($filename, $imageContents);
                $data_url = Storage::url($filename);
                // Create an Intervention Image instance for the downloaded image
                $image = Image::make($imageContents);

                // Resize and save three additional copies of the image with different sizes
                $sizes = config('app.img_sizes'); // Specify the desired sizes in pixels

                foreach ($sizes as $size) {
                    $resizedImage = $image->resize($size, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    if ($mainImage) {
                        $filename2 = $path . $sku . "_{$size}px" . '.' . $ext;
                    } else {
                        $n = $sku . '_gallery_' .  $count . "_{$size}px";
                        $filename2 = $path . $n . '.' . $ext;
                    }

                    // Save the resized image in the storage folder
                    Storage::disk('public')->put($filename2, $resizedImage->encode('jpg'));

                    // $data_url[] = Storage::url($filename2);
                }
            }
        } catch (Exception $e) {
        }

        return $data_url;
    }

 

    // public function downloadImage($url, $sku, $mainImage = false, $count = 1)
    // {
    //     // File path = products/YEAR/MONTH/SKU/

    //     $path = 'products/' . $this->year . '/' . $this->month . '/' . $sku . '/';
    //     if ($mainImage) {
    //         $name = $path . $sku . '.' . substr($url, strrpos($url, '.') + 1);
    //     } else {
    //         $n = $sku . '_gallery_' .  $count;
    //         $name = $path . $n . '.' . substr($url, strrpos($url, '.') + 1);
    //     }

    //     $contents = file_get_contents($url);

    //     $img = Storage::disk('public')->put($name, $contents);

    //     $og_img = Storage::url($name);


    //     // resize 
    //     // 300*300
    //     // 500*500

    //     // dd(storage_path('app/public/'.$name));

    //     $sizes = config('app.img_sizes');

    //     foreach ($sizes as $size) {

    //         if ($mainImage) {
    //             $r_name = $path . $sku . '_' . $size . '.' . substr($url, strrpos($url, '.') + 1);
    //         } else {
    //             $n = $sku . '_gallery_' .  $count;
    //             $r_name = $path . $n . '_' . $size . '.' . substr($url, strrpos($url, '.') + 1);
    //         }

    //         $r_img = Image::make(storage_path('app/public/' . $name))->resize($size, $size, function ($constraint) {
    //             $constraint->aspectRatio();
    //         });

    //         $img = Storage::disk('public')->put($r_name, $r_img->__toString());
    //     }

    //     return $og_img;
    // }

    // // public function downloadGalleryImages($urls)
    // // {
    // //     $data = array();
    // //     foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
    // //         $data[] = $this->downloadThumbnail($url);
    // //     }
    // //     return implode(',', $data);
    // // }

    public function cleanSKU($sku)
    {
        $sku = trim($sku);
        $sku = preg_replace('/[^a-zA-Z0-9\-\_]/i', '', $sku);
        return $sku;
    }
}
