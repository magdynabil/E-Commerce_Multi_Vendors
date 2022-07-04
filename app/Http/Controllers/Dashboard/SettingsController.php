<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingMethodsRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class SettingsController extends Controller
{
    public function editShippingMethods($type)
    {
        //free , inner , outer for shipping methods

        if ($type === 'free')
            $shippingMethod = Setting::where('key', 'free_shipping_label')->first();


        elseif ($type === 'inner')
            $shippingMethod = Setting::where('key', 'local_label')->first();

        elseif ($type === 'outer')
            $shippingMethod = Setting::where('key', 'outer_label')->first();
        else
            $shippingMethod = Setting::where('key', 'free_shipping_label')->first();


        return view('dashboard.settings.shipping.edit', compact('shippingMethod'));
    }

    public function updateShippingMethods(ShippingMethodsRequest $request, $id)
    {
        try {
            $shippingMethod = Setting::findOrfail($id);
            DB::beginTransaction();
           $shippingMethod->update(['plain_value'=>$request->plain_value]);
           $shippingMethod->value=$request->value;
           $shippingMethod->save();
            DB::commit();
            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
        } catch (Exception $ex) {
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
            DB::rollback();
        }

    }

}
