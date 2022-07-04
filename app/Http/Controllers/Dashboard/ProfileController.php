<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Admin;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function editProfile()
    {
        $admin=Admin::find(auth()->guard('admin')->user()->id);
        return view('dashboard.profile.edit',compact('admin'));

    }
    public function updateProfile(ProfileRequest $request)
    {
        try {
            $admin=Admin::find(auth()->guard('admin')->user()->id);
            if ($request->filled('passwerd'))
            {
                $request->merge('password',bcrypt($request->password));
            }
            $admin->update($request->only(['name','email']));
            return redirect()->back()->with(['success' => 'تم التحديث بنجاح']);
        }
        catch (\Exception $ex){
            return redirect()->back()->with(['error' => 'هناك خطا ما يرجي المحاولة فيما بعد']);
        }


    }

}
