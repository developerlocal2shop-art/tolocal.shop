<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client\LikeCounters;
use Auth;
use App\Helpers\Helper;

class LikeCounterControllers extends Controller
{
    public function addToLikeCounter(Request $request, $id = 0)
    {
        //echo "test like"; echo $type_item;
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        /*$status = LikeCounters::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
        if (isset($status->user_id) and isset($request->id)) {
            if ($status) {
                $arr = array('msg' => 'This item is already in wishlist', 'status' => true);
            }
            $removewislist = LikeCounters::where(['product_id' => $request->id, 'user_id' => Auth::user()->id])->delete();
            if ($removewislist) {
                $arr = array('msg' => 'Removed from the wishlist', 'status' => true);
            }
            return Response()->json($arr);
        } else { */
        $likedunique = LikeCounters::where('ip',$ip)->where('type',$request->type_item)->where('module_id',$request->id)->where('user_id',$request->user_login)->first();
        if(!$likedunique){
            $likecount = new LikeCounters();
            $likecount->ip                          = $ip;
            $likecount->module_id                   = $request->id;
            $likecount->type                        = $request->type_item;
            $likecount->user_id                     = $request->user_login; 
            $likecount->country_code                = $request->countrycode; 
            $likecount->state                       = $request->state; 
            $likecount->city                        = $request->city;            
            $likecount->save();
            if ($likecount) {
                $arr = array('msg' => 'liked', 'status' => true);
                if(count(Helper::CountLikeData($request->type_item, $request->id)) != 0){
                    $totallike = count(Helper::CountLikeData($request->type_item, $request->id));
                }else{
                    $totallike = 0;
                }
                
                $arr['totallike'] = $totallike;
            }
            return Response()->json($arr);
        }
        /* } */
    }
}
