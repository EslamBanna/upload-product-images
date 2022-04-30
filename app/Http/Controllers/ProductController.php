<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImages;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use GeneralTrait;
    public function insertProduct(Request $request)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'user_id' => 'required',
                'product_name' => 'required|string',
                'product_description' => 'required',
                'product_images' => 'required|array',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            // insert product data here and get the id of the new product
            $product_id = Product::insertGetId([
                'user_id' => $request->user_id,
                'product_name' => $request->product_name,
                'product_description' => $request->product_description,
                // and so on in all product data......
            ]);

            foreach ($request->product_images as $product_image) {
                $path = '';
                if (!is_file($product_image['image'])) {
                    return $this->returnError(202, 'data type is required, is only files');
                }
                $path = $this->saveImage($product_image['image'], 'products');
                ProductImages::create([
                    'product_id' => $product_id,
                    'path' => $path
                ]);
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError(201, $e->getMessage());
        }
    }

    public function updateProduct(Request $request, $product_id)
    {
        try {
            DB::beginTransaction();
            $rules = [
                'product_name' => 'required|string',
                'product_description' => 'required',
                'product_images' => 'required|array',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $product = Product::find($product_id);
            if (!$product) {
                return $this->returnError(202, 'product does not exist');
            }
            // update product main data
            $product->update([
                'product_name' => $request->product_name ?? $product->product_name,
                'product_description' => $request->product_description ?? $product->product_description,
                // and so on in all product data......
            ]);
            $product->productImages()->delete();
            foreach ($request->product_images as $product_image) {
                $path = '';
                if (!is_file($product_image['image'])) {
                    $path = $product_image['image'];
                } else {
                    $path = $this->saveImage($product_image['image'], 'products');
                    // or if you use cloudinary system
                    // $path = cloudinary()->upload($product_image['image']->getRealPath())->getSecurePath();
                }
                ProductImages::create([
                    'product_id' => $product['id'],
                    'path' => $path
                ]);
            }
            DB::commit();
            return $this->returnSuccessMessage('success');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->returnError(201, $e->getMessage());
        }
    }
}
