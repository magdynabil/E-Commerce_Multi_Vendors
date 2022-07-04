<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\TagsRequest;
use App\Models\Brand;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    public function index()
    {
        $tags=Tag::paginate(PAGINATION_COUNT);
        return view('dashboard.tags.index')->with(['tags'=>$tags]);
    }
    public function create()
    {
        return view('dashboard.tags.create');
    }
    public function store(TagsRequest $request)
    {
        try
        {
            DB::beginTransaction();
        $category=Tag::create($request->except('_token'));
        $category->name = $request->name;
        $category->save();
            DB::commit();
            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
        }
    }
    public function edit($id)
    {
        $tag=Tag::find($id);
        if (!$tag)
        {
            return redirect()->route('admin.tags')->with('error','هذا القسم غير موجود');

        }
        return view('dashboard.tags.edit')->with(['tag'=>$tag]);
    }
    public function update($id,TagsRequest $request)
    {
        try
        {
        DB::beginTransaction();
        $tag=Tag::findOrfail($id);
        $tag->update($request->except('_token'));
        $tag->name = $request->name;
        $tag->save();
        DB::commit();
        return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
        }
        catch (\Exception $ex)
        {
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
            DB::rollBack();
        }
    }
    public function destroy($id)
    {
        try {
            $brand = Tag::find($id);

            if (!$brand)
                return redirect()->route('admin.tags')->with(['error' => 'هذا الماركة غير موجود ']);

            $brand->delete();

            return redirect()->route('admin.tags')->with(['success' => 'تم  الحذف بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.tags')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

}
