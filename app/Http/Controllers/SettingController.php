<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function settings()
    {
        $data = [];

        $data['settingdata'] = Setting::first();

        $data['heading'] = 'Setting';
        $data['breadcrumb'] = ['Setting', 'List'];
        return view('admin.setting.index', compact('data'));
    }

    public function updatesetting(Request $request)
    {
        Setting::where('id', $request->id)->update(['return_bill_time' => $request->return_bill_time]);
        return redirect()->back()->with('success', 'Updated successfully');
    }

    public function databaseSync()
    {
        \Artisan::call('schedule:run');
        return \Response::json(['success' => true], 200);
    }
}
