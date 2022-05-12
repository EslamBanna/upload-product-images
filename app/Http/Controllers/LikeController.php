<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use GeneralTrait;
    public function makeLike($product_id){
        try{
            $check_product = Product::find($product_id);
            if(! $check_product){
                return $this->returnError(202, 'product not founded');
            }
            Like::create([
                'product_id' => $product_id,
                'user_id' => Auth()->user()->id
            ]);
            return $this->returnSuccessMessage('success');
        }catch(\Exception $e){
            return $this->returnError(201, $e->getMessage());
        }
    }

    public function makeDislike($like_id){
        try{
            $like = Like::find($like_id);
            if(! $like){
                return $this->returnError(202, 'product not founded');
            }
            $like->delete();
            return $this->returnSuccessMessage('success');
        }catch(\Exception $e){
            return $this->returnError(201, $e->getMessage());
        }
    }

    public function getMyFavorite(){
        try{
            $favorites = Like::with('product')->where('user_id', Auth()->user()->id)->get();
            return $this->returnData('data', $favorites);
        }catch(\Exception $e){
            return $this->returnError(201, $e->getMessage());
        }
    }
}
