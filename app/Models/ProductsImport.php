<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
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
        $brands = Brand::all();
        $categories = Category::all();
        foreach ($rows as $row) {
            $sku = $this->cleanSKU($row['product_code']);

            $brand = null;
            $parent_id = 0;

            if (isset($row['brand'])) {
                $brand = $brands->where('name', $row['brand'])->first();
            }

            if (isset($row['category'])) {
                $category = explode('>', $row['category']);
                foreach ($category as $key => $cat) {
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
                }
            }


            $mainImage = null;
            $galleryImage = null;

            if (isset($row['main_image'])) {
                $mainImage = $this->downloadAndResizeImage($row['main_image'], $sku, true);
            }

            if (isset($row['gallery_images'])) {
                $galleryImage = $this->downloadGallery($row['gallery_images'], $sku);
                $galleryImage = implode(',', $galleryImage);
            }

            $productId = Product::where([
                'sku' => $sku
            ])->get()->first();

            if ($productId) {
                if (isset($row['product_name'])) {
                    $productId->name = $row['product_name'];
                }
                if (isset($row['part_number'])) {
                    $productId->part_number = $row['part_number'];
                }
                if (isset($row['description'])) {
                    $productId->description = $row['description'];
                }
                if (isset($row['short_description'])) {
                    $productId->short_description = $row['short_description'];
                }
                if (isset($row['category'])) {
                    $productId->category_id = $parent_id;
                }
                if (isset($brand)) {
                    $productId->brand_id = $brand->id;
                }
                if (isset($row['price'])) {
                    $productId->unit_price = $row['price'];
                    $productId->purchase_price = $row['price'];
                }
            } else {
                $productId = Product::create([
                    'sku' => $sku,
                    'name' => $row['product_name'],
                    'description' => $row['description'],
                    'short_description' => $row['short_description'],
                    'category_id' => $parent_id,
                    'brand_id' => $brand ? $brand->id : 0,

                    'video_provider' => '',
                    'video_link' => '',
                    'unit_price' => $row['price'] ?? 1,
                    'purchase_price' => $row['price'],
                    'part_number' => $row['part_number'],
                    'unit' => '',

                    'slug' => $this->productSlug($row['product_name']),
                    // 'thumbnail_img' => $this->downloadThumbnail($row['thumbnail_img']),
                    // 'photos' => $this->downloadGalleryImages($row['photos']),

                    'thumbnail_img' => $mainImage ?? '',
                    'photos' => $galleryImage ?? '',

                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
                ]);
            }

            if ($mainImage) {
                $productId->thumbnail_img = $mainImage;
            }
            if ($galleryImage) {
                $productId->photos = $galleryImage;
            }

            $productId->save();

            // $productId = Product::updateOrCreate([

            // ], [
            //     'name' => $row['product_name'],
            //     'description' => $row['description'],
            //     'short_description' => $row['short_description'],
            //     'category_id' => $parent_id,
            //     'brand_id' => $brand ? $brand->id : 0,

            //     'video_provider' => '',
            //     'video_link' => '',
            //     'unit_price' => $row['price'] ?? 1,
            //     'purchase_price' => $row['price'],
            //     'unit' => '',

            //     'slug' => $this->productSlug($row['product_name']),
            //     // 'thumbnail_img' => $this->downloadThumbnail($row['thumbnail_img']),
            //     // 'photos' => $this->downloadGalleryImages($row['photos']),

            //     'thumbnail_img' => $mainImage ?? '',
            //     'photos' => $galleryImage ?? '',

            //     'created_by' => Auth::user()->id,
            //     'updated_by' => Auth::user()->id,
            // ]);

            if ($productId) {
                ProductStock::updateOrCreate([
                    'product_id' => $productId->id,
                    'sku' => $sku,
                ], [
                    'qty' => (isset($row['quantity']) && $row['quantity'] !== NULL) ? $row['quantity'] : 1,
                    'price' => $row['price'] ?? 1,
                    'variant' => '',
                ]);
            }
        }

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
            'product_code' => 'required',
        ];
    }

    public function downloadGallery($urls, $sku)
    {
        foreach (explode(',', str_replace(' ', '', $urls)) as $index => $url) {
            $data[] = $this->downloadAndResizeImage($url, $sku, false, $index + 1);
        }

        return $data;
    }


    public function downloadAndResizeImage($imageUrl, $sku, $mainImage = false, $count = 1)
    {
        $data_url = '';

        try {
            $ext = substr($imageUrl, strrpos($imageUrl, '.') + 1);
            $path = 'products/' . $this->year . '/' . $this->month . '/' . $sku . '/';

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
