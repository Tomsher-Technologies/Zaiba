<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductStock;
use App\Models\Category;
use App\Models\FlashDealProduct;
use App\Models\ProductTax;
use App\Models\AttributeValue;
use App\Models\Cart;
use App\Models\Color;
use App\Models\FlashDeal;
use App\Models\Products\ProductTabs;
use App\Models\ProductSeo;
use App\Models\User;
use App\Models\ProductAttributes;
use Auth;
use Carbon\Carbon;
use Combinations;
// use CoreComponentRepository;
use Artisan;
use Cache;
use Exception;
use Image;
use Storage;
use Str;
use File;
use Hash;

class ProductController extends Controller
{


    public function all_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc');
        
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            if ($col_name == 'status') {
                $products = $products->where('published', $query);
            } else {
                $products = $products->orderBy($col_name, $query);
            }

            $sort_type = $request->type;
        }
        if ($request->has('category') && $request->category !== '0') {
            $products = $products->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
                ->where('name', 'like', '%' . $sort_search . '%')
                ->orWhereHas('stocks', function ($q) use ($sort_search) {
                    $q->where('sku', 'like', '%' . $sort_search . '%');
                });
        }

       

        $products = $products->paginate(15);
        $type = 'All';

        return view('backend.product.products.index', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.create', compact('categories'));
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    public function get_attribute_values(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();

        $html = '';

        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->id . '">' . $row->value . '</option>';
        }

        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all());
        // die;
        $skuMain = '';
        if($request->has('products')){
            $products = $request->products;
            if(isset($products[0])){
                $skuMain = $products[0]['sku'];
            }
        }
       
        $product = new Product;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->design_id = $request->design_id;
        $product->design_category_id = $request->design_category_id;
        $product->metal_type = $request->metal_type;
        $product->purity = $request->purity;
        $product->min_qty = $request->min_qty;
        $product->sku = cleanSKU($skuMain);
        $product->low_stock_quantity = $request->low_stock_quantity;
        $product->stock_visibility_state = $request->stock_visibility_state;
        
        $product->hide_price = $request->hide_price ? 1 : 0;
        $product_type = ($request->product_type == 'variant') ? 1 : 0;
        $product->product_type = $product_type;

        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->tags = implode(',', $tags);
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;

        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }

        // if ($request->hasFile('pdf')) {
        //     $product->pdf = $request->pdf->store('uploads/products/pdf');
        // }
       
        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;

        // $choice_options = array();

        // if ($request->has('main_attributes')) {
        //     foreach ($request->main_attributes as $key => $no) {
        //         $str = 'choice_options_' . $no;

        //         $item['attribute_id'] = $no;

        //         $data = array();
        //         // foreach (json_decode($request[$str][0]) as $key => $eachValue) {
        //         foreach ($request[$str] as $key => $eachValue) {
        //             // array_push($data, $eachValue->value);
        //             array_push($data, $eachValue);
        //         }

        //         $item['values'] = $data;
        //         array_push($choice_options, $item);
        //     }
        // }

        if (!empty($request->main_attributes)) {
            $product->attributes = json_encode($request->main_attributes);
        } else {
            $product->attributes = json_encode(array());
        }

        // $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        $product->published = 1;
        if ($request->button == 'draft') {
            $product->published = 0;
        }

        if ($request->has('featured')) {
            $product->featured = 1;
        }

        if ($request->has('return_refund')) {
            $product->return_refund = 1;
        }

        $product->save();

        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }

            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = $this->downloadAndResizeImage($file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::exists($product->thumbnail_img)) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                    }
                    Storage::delete($product->thumbnail_img);
                }
            }
            $gallery = $this->downloadAndResizeImage($request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }

        $product->save();

        // SEO
        $seo = ProductSeo::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);

        $seo->meta_title        = $request->meta_title;
        $seo->meta_description  = $request->meta_description;

        $keywords = array();
        if ($request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }
        $seo->meta_keywords = implode(',', $keywords);

        $seo->og_title        = $request->og_title;
        $seo->og_description  = $request->og_description;

        $seo->twitter_title        = $request->twitter_title;
        $seo->twitter_description  = $request->twitter_description;

        if ($request->meta_title == null) {
            $seo->meta_title = $product->name;
        }
        if ($request->og_title == null) {
            $seo->og_title = $product->name;
        }
        if ($request->twitter_title == null) {
            $seo->twitter_title = $product->name;
        }

        $seo->save();

        // Tabs
        if ($request->has('tabs')) {
            foreach ($request->tabs as $tab) {
                $p_tab = $product->tabs()->create([
                    'heading' => $tab['tab_heading'],
                    'content' => $tab['tab_description'],
                ]);
            }
        }

        if($request->has('products')){
            $products = $request->products;
            $product_attributes = array();
            foreach($products as $prod){
                $product_stock = new ProductStock;
                $product_stock->product_id = $product->id;
                $product_stock->sku = $prod['sku'];
                $product_stock->description = $prod['description'];
                $product_stock->metal_weight = $prod['metal_weight'];
                $product_stock->stone_available =  (array_key_exists('stone_available', $prod)) ? 1 : 0;
                $product_stock->stone_type = $prod['stone_type'];
                $product_stock->stone_count = $prod['stone_count'];
                $product_stock->stone_weight = $prod['stone_weight'];
                $product_stock->stone_price = $prod['stone_price'];
                $product_stock->making_price_type = $prod['making_price_type_id'];
                $product_stock->making_charge = $prod['making_charge'];
                $product_stock->qty = $prod['current_stock'];
                
                $variantImage = $this->downloadAndResizeImage($prod['variant_images'], $prod['sku'], false);
                $product_stock->image = $variantImage;

                $product_stock->save();
                
                if ($request->has('main_attributes')) {
                    foreach ($request->main_attributes as $key => $no) {
                        $attrId = 'choice_options_' . $no;
                        $product_attributes[] = [
                            'product_id' => $product->id,
                            'product_varient_id' => $product_stock->id,
                            'attribute_id' => $no,
                            'attribute_value_id' => $prod[$attrId]
                        ];
                    }
                }
               
            }
            if(!empty($product_attributes)){
                ProductAttributes::insert($product_attributes);
            }
        }

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return redirect()->route('products.all');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_product_edit(Request $request, $id)
    {

        $product = Product::with(['tabs', 'seo','stocks'])->findOrFail($id);

        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();
        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function seller_product_edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($product->digital == 1) {
            return redirect('digitalproducts/' . $id . '/edit');
        }
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        // $categories = Category::all();
        $categories = Category::where('parent_id', 0)

            ->with('childrenCategories')
            ->get();

        return view('backend.product.products.edit', compact('product', 'categories', 'tags', 'lang'));
    }


    public function downloadAndResizeImage($imageUrl, $sku, $mainImage = false, $count = 1, $update = false)
    {
        $data_url = '';

        try {
            $ext = $imageUrl->getClientOriginalExtension();
            $path = 'products/' . Carbon::now()->year . '/' . Carbon::now()->format('m') . '/' . $sku . '/';

            if ($mainImage) {
                $filename = $path . $sku . '.' . $ext;
            } else {
                $n = $sku . '_gallery_' .  $count;
                $filename = $path . $n . '.' . $ext;
            }


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
        } catch (Exception $e) {
        }

        return $data_url;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        echo '<pre>';
        print_r($request->all());
        die;

        $product = Product::findOrFail($id);

        $gallery = [];
        if ($request->hasfile('gallery_images')) {
            if ($product->photos == null) {
                $count = 1;
                $old_gallery = [];
            } else {
                $old_gallery = explode(',', $product->photos);
                $count = count($old_gallery) + 1;
            }

            foreach ($request->file('gallery_images') as $key => $file) {
                $gallery[] = $this->downloadAndResizeImage($file, $product->sku, false, $count + $key);
            }
            $product->photos = implode(',', array_merge($old_gallery, $gallery));
        }

        if ($request->hasFile('thumbnail_image')) {
            if ($product->thumbnail_img) {
                if (Storage::exists($product->thumbnail_img)) {
                    $info = pathinfo($product->thumbnail_img);
                    $file_name = basename($product->thumbnail_img, '.' . $info['extension']);
                    $ext = $info['extension'];

                    $sizes = config('app.img_sizes');
                    foreach ($sizes as $size) {
                        $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                        if (Storage::exists($path)) {
                            Storage::delete($path);
                        }
                    }
                    Storage::delete($product->thumbnail_img);
                }
            }
            $gallery = $this->downloadAndResizeImage($request->file('thumbnail_image'), $product->sku, true);
            $product->thumbnail_img = $gallery;
        }


        $product->category_id       = $request->category_id;
        $product->brand_id          = $request->brand_id;
        $product->featured = 0;

        $product->name          = $request->name;
        $product->unit          = $request->unit;
        $product->hide_price          = $request->hide_price ? 1 : 0;
        $product->description   = $request->description;

        $slug = $request->slug ? Str::slug($request->slug, '-') : Str::slug($request->name, '-');
        $same_slug_count = Product::where('slug', 'LIKE', $slug . '%')->count();
        $slug_suffix = $same_slug_count > 1 ? '-' . $same_slug_count + 1 : '';
        $slug .= $slug_suffix;

        $product->slug = $slug;

        // $product->photos                 = $request->photos;
        // $product->thumbnail_img          = $request->thumbnail_img;
        $product->min_qty                = $request->min_qty;
        $product->low_stock_quantity     = $request->low_stock_quantity;
        $product->stock_visibility_state = $request->stock_visibility_state;
        $product->external_link = $request->external_link;
        $product->external_link_btn = $request->external_link_btn;

        $tags = array();
        if ($request->tags[0] != null) {
            foreach (json_decode($request->tags[0]) as $key => $tag) {
                array_push($tags, $tag->value);
            }
        }
        $product->tags           = implode(',', $tags);

        $product->video_provider = $request->video_provider;
        $product->video_link     = $request->video_link;
        $product->unit_price     = $request->unit_price;
        $product->discount       = $request->discount;
        $product->discount_type     = $request->discount_type;

        $product->length = $request->length;
        $product->height = $request->height;
        $product->width = $request->width;
        $product->weight = $request->weight;

        if ($request->date_range != null) {
            $date_var               = explode(" to ", $request->date_range);
            $product->discount_start_date = strtotime($date_var[0]);
            $product->discount_end_date   = strtotime($date_var[1]);
        }

        if ($request->has('featured')) {
            $product->featured = 1;
        }

        $product->pdf = $request->pdf;

        $choice_options = array();

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;

                $item['attribute_id'] = $no;

                $data = array();
                foreach ($request[$str] as $key => $eachValue) {
                    array_push($data, $eachValue);
                }

                $item['values'] = $data;
                array_push($choice_options, $item);
            }
        }

        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }

        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        } else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        // Gallery upload



        //combinations start
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                foreach ($request[$name] as $key => $item) {
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = Combinations::makeCombinations($options);
        if (count($combinations[0]) > 0) {
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $key => $item) {
                    if ($key > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if ($product_stock == null) {
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }
                if (isset($request['price_' . str_replace('.', '_', $str)])) {

                    $product_stock->variant = $str;
                    $product_stock->price = $request['price_' . str_replace('.', '_', $str)];
                    $product_stock->sku = $request['sku_' . str_replace('.', '_', $str)];
                    $product_stock->qty = $request['qty_' . str_replace('.', '_', $str)];
                    $product_stock->image = $request['img_' . str_replace('.', '_', $str)];

                    $product_stock->save();
                }
            }
        } else {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product->id;
            $product_stock->variant     = '';
            $product_stock->price       = $request->unit_price;
            $product_stock->sku         = cleanSKU($request->sku);
            $product_stock->qty         = $request->current_stock;
            $product_stock->save();
        }

        $product->save();

        //Flash Deal
        if ($request->flash_deal_id) {
            $flash_deal = FlashDeal::findOrFail($request->flash_deal_id);
            $product->discount = $request->flash_discount;
            $product->discount_type = $request->flash_discount_type;
            $product->discount_start_date = $flash_deal->start_date;
            $product->discount_end_date   = $flash_deal->end_date;
            $product->save();
        }

        //VAT & Tax
        if ($request->tax_id) {
            ProductTax::where('product_id', $product->id)->delete();
            foreach ($request->tax_id as $key => $val) {
                $product_tax = new ProductTax;
                $product_tax->tax_id = $val;
                $product_tax->product_id = $product->id;
                $product_tax->tax = $request->tax[$key];
                $product_tax->tax_type = $request->tax_type[$key];
                $product_tax->save();
            }
        }


        // SEO
        $seo = ProductSeo::firstOrNew(['lang' => $request->lang, 'product_id' => $product->id]);

        $seo->meta_title        = $request->meta_title;
        $seo->meta_description  = $request->meta_description;

        $keywords = array();
        if ($request->meta_keywords[0] != null) {
            foreach (json_decode($request->meta_keywords[0]) as $key => $keyword) {
                array_push($keywords, $keyword->value);
            }
        }

        $seo->meta_keywords = implode(',', $keywords);

        $seo->og_title        = $request->og_title;
        $seo->og_description  = $request->og_description;

        $seo->twitter_title        = $request->twitter_title;
        $seo->twitter_description  = $request->twitter_description;

        if ($seo->meta_title == null) {
            $seo->meta_title = $product->name;
        }
        if ($seo->og_title == null) {
            $seo->og_title = $product->name;
        }
        if ($seo->twitter_title == null) {
            $seo->twitter_title = $product->name;
        }

        $seo_dec = strip_tags($product->description);
        if ($seo->meta_description == null) {
            $seo->meta_description = $seo_dec;
        }
        if ($seo->og_description == null) {
            $seo->og_description = $seo->meta_description;
        }
        if ($seo->twitter_description == null) {
            $seo->twitter_description = $seo->meta_description;
        }

        $seo->save();

        // Tabs
        if ($request->has('tabs')) {
            ProductTabs::where('product_id', $product->id)->delete();
            foreach ($request->tabs as $tab) {
                $p_tab = $product->tabs()->create([
                    'heading' => $tab['tab_heading'],
                    'content' => $tab['tab_description'],
                ]);
            }
        }

        flash(translate('Product has been updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // foreach ($product->product_translations as $key => $product_translations) {
        //     $product_translations->delete();
        // }

        foreach ($product->stocks as $key => $stock) {
            $stock->delete();
        }

        if (Product::destroy($id)) {
            Cart::where('product_id', $id)->delete();

            flash(translate('Product has been deleted successfully'))->success();

            Artisan::call('view:clear');
            Artisan::call('cache:clear');

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function bulk_product_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $product_id) {
                $this->destroy($product_id);
            }
        }

        return 1;
    }

    /**
     * Duplicates the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate(Request $request, $id)
    {
        $product = Product::find($id);

        if (Auth::user()->id == $product->user_id || Auth::user()->user_type == 'staff') {
            $product_new = $product->replicate();
            $product_new->slug = $product_new->slug . '-' . Str::random(5);
            $product_new->save();

            foreach ($product->stocks as $key => $stock) {
                $product_stock              = new ProductStock;
                $product_stock->product_id  = $product_new->id;
                $product_stock->variant     = $stock->variant;
                $product_stock->price       = $stock->price;
                $product_stock->sku         = $stock->sku;
                $product_stock->qty         = $stock->qty;
                $product_stock->save();
            }

            flash(translate('Product has been duplicated successfully'))->success();
            if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
                if ($request->type == 'In House')
                    return redirect()->route('products.admin');
                elseif ($request->type == 'Seller')
                    return redirect()->route('products.seller');
                elseif ($request->type == 'All')
                    return redirect()->route('products.all');
            } else {
                if (addon_is_activated('seller_subscription')) {
                    $seller = Auth::user()->seller;
                    $seller->remaining_uploads -= 1;
                    $seller->save();
                }
                return redirect()->route('seller.products');
            }
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }

    public function updateTodaysDeal(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->todays_deal = $request->status;
        $product->save();
        Cache::forget('todays_deal_products');
        return 1;
    }

    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $seller = $product->user->seller;
            if ($seller->invalid_at != null && $seller->invalid_at != '0000-00-00' && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0) {
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function updateProductApproval(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->approved = $request->approved;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $seller = $product->user->seller;
            if ($seller->invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->invalid_at), false) <= 0) {
                return 0;
            }
        }

        $product->save();
        return 1;
    }

    public function updateFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->featured = $request->status;
        if ($product->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

    public function updateSellerFeatured(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->seller_featured = $request->status;
        if ($product->save()) {
            return 1;
        }
        return 0;
    }

    public function sku_combination(Request $request)
    {
        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                foreach ($request[$name] as $key => $item) {
                    // array_push($data, $item->value);
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = Combinations::makeCombinations($options);
        return view('backend.product.products.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'));
    }

    public function sku_combination_edit(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $options = array();
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $product_name = $request->name;
        $unit_price = $request->unit_price;

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                // foreach (json_decode($request[$name][0]) as $key => $item) {
                foreach ($request[$name] as $key => $item) {
                    // array_push($data, $item->value);
                    array_push($data, $item);
                }
                array_push($options, $data);
            }
        }

        $combinations = Combinations::makeCombinations($options);
        return view('backend.product.products.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'product'));
    }

    public function delete_thumbnail(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

        $fil_url = str_replace('/storage/', '', $product->thumbnail_img);
        $fil_url = $path = Storage::disk('public')->path($fil_url);

        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                // if (Storage::exists($path)) {
                //     Storage::delete($path);
                // }
                unlink($path);
            }

            // Storage::delete($product->thumbnail_img);1
            unlink($fil_url);
            $product->thumbnail_img = null;
            $product->save();
            return 1;
        }
    }

    public function delete_variant_image(Request $request)
    {
        // $product = ProductStock::where('id', $request->id)->first();

        // $fil_url = str_replace('/storage/', '', $product->image);
        // $fil_url = $path = Storage::disk('public')->path($fil_url);

        // if (File::exists($fil_url)) {
        //     $info = pathinfo($fil_url);
        //     $file_name = basename($fil_url, '.' . $info['extension']);
        //     $ext = $info['extension'];

        //     $sizes = config('app.img_sizes');
        //     foreach ($sizes as $size) {
        //         $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
        //         // if (Storage::exists($path)) {
        //         //     Storage::delete($path);
        //         // }
        //         unlink($path);
        //     }

        //     // Storage::delete($product->thumbnail_img);1
        //     unlink($fil_url);
        //     $product->thumbnail_img = null;
        //     $product->save();
        //     return 1;
        // }
    }

    public function delete_gallery(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        $fil_url = str_replace('/storage/', '', $request->url);
        $fil_url = $path = Storage::disk('public')->path($fil_url);
        if (File::exists($fil_url)) {
            $info = pathinfo($fil_url);
            $file_name = basename($fil_url, '.' . $info['extension']);
            $ext = $info['extension'];

            $sizes = config('app.img_sizes');
            foreach ($sizes as $size) {
                $path = $info['dirname'] . '/' . $file_name . '_' . $size . 'px.' . $ext;
                unlink($path);
            }

            unlink($fil_url);

            $thumbnail_img = explode(',', $product->photos);
            $thumbnail_img =  array_diff($thumbnail_img, [$request->url]);
            if ($thumbnail_img) {
                $product->photos = implode(',', $thumbnail_img);
            } else {
                $product->photos = null;
            }

            $product->save();
            return 1;
        } else {
            return 0;
        }
    }
}
