<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Blog;
use App\Helpers\Helper;
use App\Models\BlogTranslation;
use App\Models\DirectorySeo;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $cat_id = $request->input('category') ? $request->input('category') : "";
        $parent_category   = Helper::getAllParentCategory();      /* get Category */
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['serchcategory_id'] = $cat_id;
            $data['table_name'] = 'blog';
            if ($cat_id != "") {
                $data['columns'] = ['title', 'status', 'category_id'];
            } else {
                $data['columns'] = ['title', 'status'];
            }
            $blogs = Helper::masterSearch($data);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.blog.index', ['blogs' => $blogs, 'catid' => $searchCatId, 'blogsearch' => $searchtext, 'parent_category' => $parent_category, 'query' => $query]);
        } else {
            $blogs = Blog::where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.blog.index', ['blogs' => $blogs, 'catid' => $searchCatId, 'blogsearch' => $searchtext, 'parent_category' => $parent_category]);
        }
    }
    public function create()
    {
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        $data['parent_category']    = Helper::getAllParentCategory();      /* get Category */
        return view('admin.blog.create', ['data' => $data]);
    }

    public function changeBlogStatus(Request $request)
    {
        $product = Blog::find($request->id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function submitBlog(Request $request)
    {
        $this->validate($request, [
            'blog_title_en'     => 'required',
            'category'          => 'required',
            'image'             => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],[
            'blog_title_en.required'     => 'The Blog Title field is required.',
        ]);
        $name_en = $request->input('blog_title_en') ?? '';
        $seo_en = $request->input('seo_en');
        $description_en = $request->input('blog_content_en');
        $blog_inst = new Blog;
        $imgfile = $request->file('image');
        $ext = $imgfile->getClientOriginalExtension();
        $name2 = $imgfile->getClientOriginalName();
        $imageName2 = time() . $name2;
        $imageName = Helper::getSeoUrl($imageName2);

        $convertext = "webp";
        
        $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);        
        Storage::disk('images')->put('blog/'.$imageName.'.'.$convertext,$image_resize);
                
        //Storage::put('public/image/'.$imageName.'.'.$convertext,$image_resize);
        
        $blog_inst->title            = (isset($name_en)) ? $name_en : "";
        $blog_inst->slug             = (isset($seo_en)) ? $seo_en : "";
        $blog_inst->category_id      = $request->input('category');
        $blog_inst->content          = isset($description_en) ? $description_en : "";
        $blog_inst->created_by       = $request->input('createdby');
        $blog_inst->blog_image       = $imageName;
        $blog_inst->latitude         = $request->input('lati');
        $blog_inst->longitude        = $request->input('long');
        $blog_inst->save();
        $blog_id = $blog_inst->id;

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {
            $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
            $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($description_en);
            $title_name                 = isset($title_name) ? $title_name : $name_en;
            $blogtrans                  = new BlogTranslation;
            $blogtrans->blog_id         = $blog_id;
            $blogtrans->category_id     = $request->input('category');
            $blogtrans->language_id     = $lang->id;
            $blogtrans->title           = isset($title_name) ? $title_name : $name_en;
            $blogtrans->description     = isset($description_code) ? $description_code : $description_en;
            $blogtrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
            $blogtrans->save();
        }

         /* Seo Entries start */
         $seo_title_en = $request->input('meta_keywords_en');
         $seo_meta_description_en = $request->input('meta_description_en');
         $dirtseo = new DirectorySeo;
         $dirtseo->module_id     = $blog_id;
         $dirtseo->page_type     = 'blog';
         $dirtseo->meta_keyword  = (isset($seo_title_en)) ? $seo_title_en : "";
         $dirtseo->meta_description  = (isset($seo_meta_description_en)) ? $seo_meta_description_en : "";
         $dirtseo->save();

        return redirect('admin/blog')->with('msg', 'Blog Added Successfully!');
    }
    public function editBlog($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $data['blogDetails'] = Blog::findOrFail($id);
        $data['parent_category']    = Helper::getAllParentCategory();      /* get Category */
        $data['languagesData']      = Helper::getAllLanguages();             /* get languages */

        $data['blogAndTraslationData'] = DB::table('blog as b')
            ->select('*')
            ->join('blog_translation as bt', 'b.id', '=', 'bt.blog_id')
            ->where('bt.blog_id',  $id)->get();
        $data['seoAndTraslationData'] = DB::table('directory_seo as dse')->select('*')
            ->where('dse.module_id',  $id)->where('page_type','Blog')->get();
        return view('admin.blog.edit', ['data' => $data]);
    }
    public function updateblog(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'blog_title_en'     => 'required',
            'category'          => 'required',
            // 'image'             => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ],[
            'blog_title_en.required'     => 'The Blog Title field is required.',
        ]);
        $name_en = $request->input('blog_title_en');
        $seo_en = $request->input('seo_en');
        $description_en = $request->input('blog_content_en');
        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            //$imagepath = 'files/blog/' . $imageName;
            //$imgfile->move(public_path('files/blog/'), $imageName);

            $convertext = "webp";
            $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);        
            Storage::disk('images')->put('blog/'.$imageName.'.'.$convertext,$image_resize);

            if ($imgfile != "") {
                Blog::where('id', $id)->update(['blog_image' => $imageName,]);
            }
        }
        Blog::where('id', $id)
            ->update([
                'title'         => $name_en ? $name_en : "",
                'slug'          => $seo_en ? $seo_en : "",
                'category_id'   => $request->input('category'),
                'content'       => $description_en,
                'latitude'      => $request->input('lati'),
                'longitude'     => $request->input('long')
            ]);

        $is_not_change_en_language = BlogTranslation::where('title',$name_en)->where('description', $description_en)->where('language_id',1)->where('blog_id',$id)->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

        if($is_not_change_en_language){
            
            foreach ($languages as $lang) {
                
                $title_name = $request->input('blog_title_' . $lang->code);
                $description_code = $request->input('blog_content_' . $lang->code);
                $blogTranslation_id = $request->input('blogtranlation_' . $lang->code);
                
                $blogtrans                  =  BlogTranslation::find($blogTranslation_id);
                $blogtrans->title           = isset($title_name) ? $title_name : "";
                $blogtrans->description     = isset($description_code) ? $description_code : "";
                $blogtrans->seo_url         = Helper::getSeoUrl($title_name);
                $blogtrans->save();
            }
        }else{
            BlogTranslation::where('blog_id', $id)->delete();
            foreach ($languages as $lang) {
                $title_name                     = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
                $description_code               = $tr->setSource('en')->setTarget($lang->code)->translate($description_en??'');
                    
                $blogtrans                      = new BlogTranslation;
                $blogtrans->blog_id             = $id;
                $blogtrans->category_id         = $request->input('category');
                $blogtrans->language_id         = $lang->id;
                $blogtrans->title               = isset($title_name) ? $title_name : $name_en;
                $blogtrans->description         = isset($description_code) ? $description_code : $description_en;
                $blogtrans->seo_url             = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
                $blogtrans->save();
            }

        }

        $module_id = $request->input('module_id');
        $page_type = $request->input('page_type');
        $seo_title_en = $request->input('meta_keywords_en');
        $seo_meta_description_en = $request->input('meta_description_en');

        if (!empty($module_id) && (!empty($page_type))) {
            DirectorySeo::where('module_id', $id)->where('page_type', 'blog')
                ->update([
                    'page_type'         => 'blog',
                    'meta_keyword'      => (isset($seo_title_en)) ? $seo_title_en : "",
                    'meta_description'  => (isset($seo_meta_description_en)) ? $seo_meta_description_en : ""
            ]);
        }else{
            $dirtseo                    = new DirectorySeo;
            $dirtseo->module_id         = $id;
            $dirtseo->page_type         = 'blog';
            $dirtseo->meta_keyword      = (isset($seo_title_en)) ? $seo_title_en : "";
            $dirtseo->meta_description  = (isset($seo_meta_description_en)) ? $seo_meta_description_en : "";
            $dirtseo->save();
        }

        return redirect('admin/blog')->with('msg', 'Blog has been updated');
    }
    public function destroy($id)
    {
        Blog::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/blog')->with('msg', 'Blog deleted successfully');
    }

    public function checkBlogSlug(Request $request)
    {
        $data = array();
        $data['seoChangeData'] = Helper::getSeoUrl($request->catname);
        if($request->directory=="blog"){
            $blogTypeDetails = DB::table('blog')->where('slug',$data['seoChangeData'])->orderBy('id', 'desc')->get()->toArray();
        }
        $data['blogTypeDetails'] = "";
        if(!empty($blogTypeDetails)){
            $datainmatch = $blogTypeDetails[0]->slug;
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

            $data['blogTypeDetails'] = $checkdata;
        }
         return response()->json($data);
    }
}
