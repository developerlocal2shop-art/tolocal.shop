<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Offer extends Controller
{
    public function offer($id=0){
        $offerData          = DB::table('offer')->select('*')->where('deleted','0')->where('id',$id)->get()->toArray();
        $productids         = explode(",",$offerData[0]->offer_product_id);
        $directoryids       = explode(",",$offerData[0]->offer_directory_link);
        if($offerData[0]->offer_type_selectedvalue=="product"){
            $data['productDirctoryData'] = DB::table('product')->select('*')->where('deleted','0')->whereIn('id', $productids)->get()->toArray();
        }else{
            $data['dirctoryData'] = DB::table('directory')->select('*')->where('deleted','0')->whereIn('id', $directoryids)->get()->toArray();
        }
    	return view('client.offer', ['data' => $data]);
    }
    
}
