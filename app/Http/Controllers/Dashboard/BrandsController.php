<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandsController extends Controller
{
    public function index()
    {
        $brands=Brand::paginate(PAGINATION_COUNT);
        return view('dashboard.brands.index')->with(['brands'=>$brands]);
    }
    public function create()
    {
        return view('dashboard.brands.create');
    }
    public function store(BrandRequest $request)
    {
        try
        {
            DB::beginTransaction();
        if (!$request->has('is_active'))
        {
            $request->request->add(['is_active'=>0]);
        }
        $fileName='';
        if ($request->has('photo'))
        {
            $fileName = uploadImage('brands', $request->photo);
        }
        $category=Brand::create($request->except('_token'));
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
        $brand=Brand::find($id);
        if (!$brand)
        {
            return redirect()->route('admin.brands')->with('error','هذا القسم غير موجود');

        }
        return view('dashboard.brands.edit')->with(['brand'=>$brand]);
    }
    public function update($id,BrandRequest $request)
    {
        try
        {
        DB::beginTransaction();
        $brand=Brand::findOrfail($id);
        if (!$request->has('is_active'))
        {
            $request->request->add(['is_active'=>0]);
        }
        $fileName='';
        if ($request->has('photo'))
        {
            $fileName = uploadImage('brands', $request->photo);
            deleteOldImage('brands',$brand->photo);
        }
        $brand->update($request->except('_token'));
        $brand->name = $request->name;
        $brand->photo = $fileName;
        $brand->save();
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
            $brand = Brand::find($id);

            if (!$brand)
                return redirect()->route('admin.brands')->with(['error' => 'هذا الماركة غير موجود ']);

            $brand->delete();

            return redirect()->route('admin.brands')->with(['success' => 'تم  الحذف بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.brands')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

}
