<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaincCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $categories= Category::parent()->paginate(PAGINATION_COUNT);
        return view('dashboard.categories.index')->with(['categories'=>$categories]);
    }

    public function create()
    {
        $categories = Category::select('id','parent_id')->get();
        return view('dashboard.categories.create')->with(['categories'=>$categories]);
    }
    public function store(MaincCategoryRequest $request)
    {
        try
        {
            DB::beginTransaction();
        if (!$request->has('is_active'))
        {
            $request->request->add(['is_active'=>0]);
        }
            //if user choose main category then we must remove paret id from the request

            if($request -> type == 1) //main category
            {
                $request->request->add(['parent_id' => null]);
            }
            $fileName='';
            //if he choose child category we mus t add parent id
            if ($request->has('photo'))
            {
                $fileName = uploadImage('category', $request->photo);
            }
        $category=Category::create($request->except('_token'));
        $category->name = $request->name;
            $category->photo = $fileName;
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
        $category=Category::find($id);
        if (!$category)
        {
            return redirect()->route('admin.maincategories')->with('error','هذا القسم غير موجود');

        }
        return view('dashboard.categories.edit')->with(['category'=>$category]);
    }
    public function update($id,MaincCategoryRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $category=Category::findOrfail($id);
            if (!$request->has('is_active'))
            {
                $request->request->add(['is_active'=>0]);
            }

            if ($request->has('photo')) {
                $fileName = uploadImage('category', $request->photo);
                deleteOldImage('category',$category->photo);
                Category::where('id', $id)->update([
                    'photo' => $fileName,
                ]);
            }

            $category->update($request->all());
            $category->name = $request->name;
            $category->save();
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
        $category=Category::findOrfail($id);
        $category->delete();
        return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);

    }
}
