<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Frontendsetting;
use App\Helpers\Helper;

class AboutController extends Controller
{
	public function index()
	{
		Helper::countwebsite();
		$about = Frontendsetting::all();
		return view('client.about', compact('about'));
	}

	public function faqindex()
	{
		$faq = Frontendsetting::all();
		return view('client.faq', compact('faq'));
	}

	public function privacyindex()
	{
		$privacy = Frontendsetting::all();
		return view('client.privacypolicy', compact('privacy'));
	}

	public function sitemap()
	{
		$language_id = Helper::getselectedLanguage();
		
		$procategories   = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
		->where('feature','yes')->where('deleted','0')->where('parent_id', 0)->orderby('sort_value','asc')
		->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();
        
		$prosubcategories   = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
		->where('feature','yes')->where('deleted','0')->where('parent_id','!=',0)->orderby('id','desc')
		->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();

		return view('client.sitemap', compact('procategories','prosubcategories'));
	}

	public function termsindex()
	{
		$terms = Frontendsetting::all();
		return view('client.termsandcondition', compact('terms'));
	}
	public function cookiepolicy()
	{
		$cookiedata = Frontendsetting::all();
		return view('client.cookiepolicy', compact('cookiedata'));
	}

}
