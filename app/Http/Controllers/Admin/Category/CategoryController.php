<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Category;
use App\Models\Categorytraslation;
use App\Models\CategoryConfigguration;
use App\Models\CategoryUploads;
use App\Helpers\Helper;
use App\Models\DirectoryType;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CategoryController extends Controller
{
    public function index(Request $request){
        $catType ='';

        if($request->input('search')){
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $cat_id = $request->input('category')?$request->input('category'):"";
            $cat_type = $request->input('categorytype')?$request->input('categorytype'):"";
            $data['searchQuery']=$request->input('searchtext');
            if($cat_type!=""){
                if($searchQuery!="" && $cat_type=="Sale"){
                    $categories = Category::with('parent')->query()->where('deleted','0')->where('name', 'like', '%'. $searchQuery .'%')->where('category_type_id', 1 )->orderBy('sort_value')->paginate(30);
                    $parent_category    = Helper::getAllParentCategory();
                    $searchQuery = $request->input('searchtext');
                    $data = isset($searchQuery)?$searchQuery:"";
                    $searchCatType=isset($cat_type)?$cat_type:'';
                    $searchCatId=isset($cat_id)?$cat_id:'';
                    return view('admin.categories.index', ['categories'=>$categories,'data'=> $data,'catid'=> $searchCatId,'parent_category' => $parent_category,'searchCatType'=>$searchCatType,'query'=> $query,'catType'=>$cat_type]);
                }
                else if($searchQuery!="" && $cat_type=="Listing"){
                    $categories = Category::with('parent')->query()->where('deleted','0')->where('name', 'like', '%'. $searchQuery .'%')->where('category_type_id', 2 )->orderBy('sort_value')->paginate(30);
                    $parent_category    = Helper::getAllParentCategory();
                    $searchQuery = $request->input('searchtext');
                    $data = isset($searchQuery)?$searchQuery:"";
                    $searchCatType=isset($cat_type)?$cat_type:'';
                    $searchCatId=isset($cat_id)?$cat_id:'';
                    return view('admin.categories.index', ['categories'=>$categories,'data'=> $data, 'catid'=> $searchCatId,'searchCatType'=>$searchCatType,'parent_category' => $parent_category,'query'=> $query,'catType'=>$cat_type]);
                }else{
                    $cat_type ='All';
                    $categories = Category::with('parent')->query()->where('deleted','0')->orderBy('sort_value')->paginate(40);
                    $parent_category    = Helper::getAllParentCategory();
                    $searchQuery = $request->input('searchtext');
                    $data = isset($searchQuery)?$searchQuery:"";
                    $searchCatType=isset($cat_type)?$cat_type:'';
                    $searchCatId=isset($cat_id)?$cat_id:'';
                    return view('admin.categories.index', ['categories'=>$categories,'data'=> $data, 'catid'=> $searchCatId, 'parent_category' => $parent_category,'searchCatType'=>$searchCatType, 'query'=> $query, 'catType'=>$cat_type]);
                }
            }else{
                if($searchQuery!="" && $cat_id!=""){
                $cat = Category::with('parent')->where('deleted','0')
                ->where('name', 'like', '%'. $searchQuery .'%')
                ->Where('parent_id',  $cat_id );
                $categories = $cat->where('deleted','0')->orderBy('sort_value')->paginate(40);
                $parent_category    = Helper::getAllParentCategory();      /* get Category */
                $searchQuery = $request->input('searchtext');
                $data = isset($searchQuery)?$searchQuery:"";
                $searchCatId=isset($cat_id)?$cat_id:'';
                $searchCatType=isset($cat_type)?$cat_type:'';
                return view('admin.categories.index', ['categories' => $categories,'data'=> $data,'catid'=> $searchCatId, 'searchCatType'=>$searchCatType,'parent_category' => $parent_category, 'query'=>$query, 'catType' =>$catType]);
            }else if($cat_id!=""){
                if($cat_id!="allparentcategories"){
                    $cat = Category::with('parent')->where('deleted','0')->Where('parent_id',  $cat_id );
                    $categories = $cat->where('deleted','0')->orderBy('sort_value')->paginate(40);
                }else{
                    $cat = Category::with('parent')->where('deleted','0')->Where('parent_id',  0 );
                    $categories = $cat->where('deleted','0')->orderBy('sort_value')->paginate(40);
                }
                $parent_category    = Helper::getAllParentCategory();      /* get Category */
                $searchQuery = $request->input('searchtext');
                $data = isset($searchQuery)?$searchQuery:"";
                $searchCatId=isset($cat_id)?$cat_id:'';
                $searchCatType=isset($cat_type)?$cat_type:'';
                return view('admin.categories.index', ['categories' => $categories,'data'=> $data,'catid'=> $searchCatId,'parent_category' => $parent_category, 'searchCatType'=>$searchCatType,'query'=>$query, 'catType' =>$catType]);
            }else{
                $cat = Category::with('parent')->where('deleted','0')
                ->where('name', 'like', '%'. $searchQuery .'%');
                $categories = $cat->where('deleted','0')->orderBy('sort_value')->paginate(30);

                $parent_category    = Helper::getAllParentCategory();      /* get Category */
                $searchQuery = $request->input('searchtext');
                $data = isset($searchQuery)?$searchQuery:"";
                $searchCatId=isset($cat_id)?$cat_id:'';
                $searchCatType=isset($cat_type)?$cat_type:'';
                return view('admin.categories.index', ['categories' => $categories,'data'=> $data,'catid'=> $searchCatId,'parent_category' => $parent_category, 'searchCatType'=>$searchCatType, 'query'=>$query, 'catType' =>$catType]);
                }
            }
        }else{
            
            $categories = Category::with('parent')->where('deleted','0')
            ->orderBy('parent_id')
            ->orderBy('sort_value')
            ->paginate(30);

            $parent_category    = Helper::getAllParentCategory();      /* get Category */
            $searchQuery = $request->input('searchtext')?$request->input('searchtext'):'';
            $data = isset($searchQuery)?$searchQuery:"";
            $searchCatId=isset($cat_id)?$cat_id:'';
            $searchCatType=isset($cat_type)?$cat_type:'';
            return view('admin.categories.index', ['categories' => $categories,'data'=> $data,'catid'=> $searchCatId,'searchCatType'=>$searchCatType,'parent_category' => $parent_category, 'catType' =>$catType]);

        }
    }

    public function changeDirectoryCatStatus(Request $request)
    {
        $product = Category::find($request->id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function create(){
        $data['categoriestype']     = Helper::getAllCategoryType();        /* get Category Type */
        $data['parent_category']    = Helper::getAllParentCategory();      /* get Category */
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        $data['getCusFields']      = Helper::getcatcutfields();            /* get custom Fields */
       return view('admin.categories.create', ['data'=>$data]);
    }
    public function submitCategory(Request $request){
        $name_en = $request->input('name_en');
        $description_en = $request->input('description_en');
        $seo_en = $request->input('seo_en');
        $this->validate($request, [
            'name_en'           => 'required',
            'categorytype'      => 'required',
            'image'             => 'mimes:jpeg,png,jpg,gif,svg|required',
        ]);

        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/category/' . $imageName;
            $imgfile->move(public_path('files/category/'), $imageName);
            $cate_image_insert                   = new CategoryUploads;
            $cate_image_insert->name_thumbnail   = $imageName;
            $cate_image_insert->name_large       = $imageName;
            $cate_image_insert->extension        = $ext;
            $cate_image_insert->path             = $imagepath;
            $cate_image_insert->created_by       = $request->input('createdby');
            $cate_image_insert->save();
            $uploads_id = $cate_image_insert->id;
        }
        $cat = new Category;
        $cat->parent_id = $request->input('pcategory');
        $cat->upload_id = $uploads_id;
        $cat->name = (isset($name_en))?$name_en:"";
        $cat->slug = Helper::getSeoUrl((isset($name_en))?$name_en:"");
        $cat->category_type_id = $request->input('categorytype');
        $cat->feature = $request->input('cat_feature');
        $cat->description = isset($description_en)?$description_en:"";
        $cat->icon_class = $request->input('icon_class');
        $cat->sort_value = $request->input('sort_value')==null ? 0:$request->input('sort_value');
        $cat->save();
        $category_id=$cat->id;
        $languages = Helper::getAllLanguages();
        foreach($languages as $lang){
            $title_name = $request->input('name_'.$lang->code);
            $seo_val = $request->input('seo_'.$lang->code);
            $description_code = $request->input('description_'.$lang->code);
            $cattrans = new Categorytraslation;
            $cattrans->category_id  = $category_id;
            $cattrans->language_id  = $lang->id;
            $cattrans->title        = isset($title_name)?$title_name:$name_en;
            $cattrans->description  = isset($description_code)?$description_code:$description_en;
            $cattrans->seo_url      = isset($seo_val)?$seo_val:$seo_en;
            $cattrans->save();
        }

        $catfield = $request->input('catfields');//echo "<pre>"; print_r($catfield);
        if(isset($catfield)){
            $catconfig = new CategoryConfigguration;
            $catconfig->category_id  = $category_id;
            $catconfig->fields  = implode(",", (isset($catfield)?$catfield:""));
            $catconfig->created_by  = $request->input('createdby');
            $catconfig->save();
        }
        return redirect('admin/categories')->with('msg', 'Category Added Successfully!');
    }
    public function editCategory($id=0){

        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false){
            abort(404);
        }
        $data['singleCategoryData'] = Category::findOrFail($id);
        $data['categoriestype']     = Helper::getAllCategoryType();         /* get Category Type */
        $data['parent_category']    = Helper::getAllParentCategory();      /* get Category */
        $data['languagesData']      = Helper::getAllLanguages();             /* get languages */
        $data['getCusFields']      = Helper::getcatcutfields();            /* get custom Fields */
        $data['languageAndTraslationData'] = DB::table('category as cat')
        ->select('*')
        ->join('category_translations as cat_tr', 'cat.id', '=', 'cat_tr.category_id')
        ->where('cat_tr.category_id',  $id )->get();
        $fieldChecked = DB::table('category_configurations')->select('*')->where('category_id',$id)->get()->toArray();
        $data['getCheckedField'] = isset($fieldChecked[0]->fields)?$fieldChecked[0]->fields:"";
        return view('admin.categories.edit',['data'=>$data]);
    }
    function gettypeCoulmnName($id)
    {
        $getfeildstypevalueData = DB::table('category_fields')->select('*')->whereIn('id',$id??'')->get();
        return $getfeildstypevalueData;
    }

    public function updateCategory(Request $request,$id){

        $updateData = $this->validate($request, [
            'name_en'       => 'required',
            'categorytype'  => 'required',
            'image'         => 'mimes:jpeg,jpg,png,gif,svg',
        ]);
        $uploads_id = 0;
/*-------------------------------------------------------------------------------*/
/*        $catfield = $request->input('catfields');
        $insertedDirectoryData = DB::table('directory_type')->select('*', DB::raw('MAX(block_num) as max_block_num'))->Where('category_id', $id)->groupBy('directory_id')->get()->toArray();
        $typefielddata = DB::table('category_configurations')->select('fields')->Where('category_id', $id)->get()->first();
        $typefielddataAfterExplode = explode(",", $typefielddata->fields);
        $catid = array_merge(array_diff($typefielddataAfterExplode, $catfield), array_diff($catfield, $typefielddataAfterExplode));
        $coulnmname = $this->gettypeCoulmnName($catid);
        echo "<pre>"; print_r($coulnmname);
        foreach($insertedDirectoryData as $dirtypedata){
            echo "value of type block : ".$dirtypedata->max_block_num."<br>";
            foreach ($coulnmname as $rowdata) {
                for ($i = 0; $i <= $dirtypedata->max_block_num; $i++) {
                
                    echo "BLOCK NUMBER : ".$i."<br>";
                    echo "Directory id : ".$dirtypedata->directory_id."<br>";
                    echo "Category id : ".$dirtypedata->category_id."<br>";
                    echo "TYPE : ".$rowdata->type."<br>";
                    echo "NAME : ".$rowdata->name."<br>";
                    echo "LABEL : ".$rowdata->label."<br>";
                    
                }
                echo "-----------------<br>";
            }
            echo "====================================<br>";
        }
        
die;*/
/*-------------------------------------------------------------------------------*/
        $imgfile = $request->file('image');
        if ($imgfile) {
            $uplaodId = $request->input('uplaodId');
            CategoryUploads::where('id', $uplaodId)->delete();
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/category/' . $imageName;
            $imgfile->move(public_path('files/category/'), $imageName);
            $cate_image_edit                   = new CategoryUploads;
            $cate_image_edit->name_thumbnail   = $imageName;
            $cate_image_edit->name_large       = $imageName;
            $cate_image_edit->extension        = $ext;
            $cate_image_edit->path             = $imagepath;
            $cate_image_edit->save();
            $uploads_id = $cate_image_edit->id;
        }
        if($uploads_id!=0)
        $uploads_id = $uploads_id;
        else
        $uploads_id = $request->input('uplaodId');

        $name_en = $request->input('name_en');
        $description_en = $request->input('description_en');
        Category::where('id', $id)
        ->update([
            'parent_id'             => $request->input('pcategory'),
            'upload_id'             => $uploads_id,
            'name'                  => (isset($name_en))?$name_en:"",
            'slug'                  => Helper::getSeoUrl((isset($name_en))?$name_en:""),
            'category_type_id'      => $request->input('categorytype'),
            'feature'               => $request->input('cat_feature'),
            'description'           => isset($description_en)?$description_en:"",
            'icon_class'            => $request->input('icon_class'),
            'sort_value'            => $request->input('sort_value'),
         ]);

        $is_not_change_en_language = Categorytraslation::where('title',$name_en)->where('description', $description_en)->where('language_id',1)->where('category_id',$id)->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

        if($is_not_change_en_language){
            
            foreach ($languages as $lang) {

                $title_name                 = $request->input('name_'.$lang->code);
                $description_code           = $request->input('description_'.$lang->code);
                $categoryTranslation_id     = $request->input('langtranlation_'.$lang->code);
                
                $cattran = Categorytraslation::find($categoryTranslation_id);
                if (!$cattran) {
                    $cattran = new Categorytraslation();
                    $cattran->category_id = $id; // make sure this exists
                    $cattran->language_id = $lang->id;    // adjust based on your column
                }
                $cattran->title = $title_name??$name_en;
                $cattran->description = $description_code??$description_en;
                $cattran->seo_url = Helper::getSeoUrl($name_en);
                $cattran->save();
            }
        }else{
            Categorytraslation::where('category_id', $id)->delete();
            foreach ($languages as $lang) {
                $title_name                     = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'Title');
                $description_code               = $tr->setSource('en')->setTarget($lang->code)->translate($description_en??'Description');
                    
                $cattrans                      = new Categorytraslation;
                $cattrans->category_id         = $id;
                $cattrans->language_id         = $lang->id;
                $cattrans->seo_url             = Helper::getSeoUrl($name_en);
                $cattrans->title               = isset($title_name) ? $title_name : $name_en;
                $cattrans->description         = isset($description_code) ? $description_code : $description_en;
                $cattrans->save();
            }
        }

        $catfield = $request->input('catfields');
        if (!is_array($catfield)) {
            $catfield = [];
        }
            /*-------------------------------------------------------------------------------*/
            $insertedDirectoryData = DB::table('directory_type')->select('*', DB::raw('MAX(block_num) as max_block_num'))->Where('category_id', $id)->groupBy('directory_id')->get()->toArray();
            $typefielddata = DB::table('category_configurations')->select('fields')->Where('category_id', $id)->get()->first();

            if($typefielddata){
                $typefielddataAfterExplode = explode(",", $typefielddata->fields);
            }else{
                $typefielddataAfterExplode = [];
            }

            $catid = array_merge(array_diff($typefielddataAfterExplode, $catfield), array_diff($catfield, $typefielddataAfterExplode));
            $coulnmname = $this->gettypeCoulmnName($catid);
            // DB::enableQueryLog();
            if (count($insertedDirectoryData) > 0) {
                foreach($insertedDirectoryData as $dirtypedata){
                    if (count($coulnmname) > 0) {
                        foreach ($coulnmname as $rowdata) {
                            //echo "before second loop start";
                            for ($i = 0; $i <= $dirtypedata->max_block_num; $i++) {
                                //echo "test for loop";
                                $dirctType = new DirectoryType;
                                $dirctType->directory_id    =   $dirtypedata->directory_id;
                                $dirctType->category_id     =   $dirtypedata->category_id;
                                $dirctType->input_type      =   $rowdata->type;
                                $dirctType->column_name     =   $rowdata->name;
                                $dirctType->column_value    =   "";
                                $dirctType->label           =   $rowdata->label;
                                $dirctType->block_num       =   $i;
                                $dirctType->image_name      =   "";
                                $dirctType->save();
                            }
                        }
                    }
                }
            }
            // $queries = DB::getQueryLog();
            // echo "<pre>"; print_r($queries); die;
            /*-------------------------------------------------------------------------------*/


        CategoryConfigguration::where('category_id', $id)->delete();
        if(isset($catfield)){
            $catconfig = new CategoryConfigguration;
            $catconfig->category_id  = $id;
            $catconfig->fields  = implode(",", (isset($catfield)?$catfield:""));
            $catconfig->created_by  = $request->input('createdby');
            $catconfig->save();
        }
        
        

        return redirect('admin/category-edit/'.$id)->with('msg', 'category has been updated');
    }
    public function destroy($id)
    {
        Category::where('id', $id)->update([ 'deleted' => '1' ]);
        return redirect('admin/categories')->with('msg','Category deleted successfully');
    }
    public static function getCategoryType($id)
    {
        $categoryTypeName = DB::table('category_type')->select('title')->where('id',$id)->first();
        return $categoryTypeName->title;
    }
    public function parentcat_categoryType(Request $request)
    {
        $categoryTypeDetails = Category::where("category_type_id",$request->cat_type_id)->where('parent_id',0)->get(["name","id"]);
         return response()->json($categoryTypeDetails);
    }
    public function checkCategorySeo(Request $request)
    {
        $data = array();
        $data['seoChangeData'] = Helper::getSeoUrl($request->catname);
        if($request->directory=="directory"){
            $categoryTypeDetails = DB::table('directory_basic_translation')->select('*')->where('seo_url',$data['seoChangeData'])->orWhere('title',$request->catname)->orderBy('id', 'desc')->get()->toArray();
        }
        if($request->category=="cate"){
            $categoryTypeDetails = DB::table('category_translations')->select('*')->where('seo_url',$data['seoChangeData'])->orWhere('title',$request->catname)->orderBy('id', 'desc')->get()->toArray();
        }
        $data['categoryTypeDetails'] = "";
        if(!empty($categoryTypeDetails)){
            $datainmatch = $categoryTypeDetails[0]->seo_url;
            $checkdata = explode("-",$datainmatch);
            $sliced = array_slice($checkdata, 0, -1);
            $string = implode("-", $sliced);
            $checkdata = end($checkdata);

            $num = 0;
            if(is_numeric($checkdata)){
                $checkdata = $string."-".++$checkdata;
            }else{
                $checkdata = $data['seoChangeData']."-".++$num;
            }

            $data['categoryTypeDetails'] = $checkdata;
        }
         return response()->json($data);
    }

    public function checkDirectorySeo(Request $request)
    {
        $data = array();
        $data['seoChangeData'] = Helper::getSeoUrl($request->catname);
        if($request->directory=="directory"){
            $categoryTypeDetails = DB::table('directory')->where('slug',$data['seoChangeData'])->orderBy('id', 'desc')->get()->toArray();
        }
        if($request->category=="cate"){
            $categoryTypeDetails = DB::table('category_translations')->select('*')->where('seo_url',$data['seoChangeData'])->orWhere('title',$request->catname)->orderBy('id', 'desc')->get()->toArray();
        }
        $data['categoryTypeDetails'] = "";
        if(!empty($categoryTypeDetails)){
            $datainmatch = $categoryTypeDetails[0]->slug;
            $checkdata = explode("-",$datainmatch);
            $sliced = array_slice($checkdata, 0, -1);
            $string = implode("-", $sliced);
            $checkdata = end($checkdata);

            $num = 0;
            if(is_numeric($checkdata)){
                $checkdata = $string."-".++$checkdata;
            }else{
                $checkdata = $data['seoChangeData']."-".++$num;
            }

            $data['categoryTypeDetails'] = $checkdata;
        }
         return response()->json($data);
    }

    public function tree()
    {
        $data['parent_category']    = Helper::getAllParentCategory(); 
        return view('admin.categories.tree',['data'=>$data]);
    }

}
