<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return redirect()->action('Admin\AdminController@queue');
    }

    public function queue()
    {
        return view('admin.queue', ['config' => \Configer::get()]);
    }

    public function blockGuest()
    {
        return view('admin.block-guest', ['config' => \Configer::get()]);
    }

    public function blockKeyword()
    {
        return view('admin.block-keyword', ['config' => \Configer::get()]);
    }

    public function setting()
    {
        $setting = [];
        $result = \DB::table('setting')->get();
        foreach ($result as $item) {
            $setting[$item->name] = $item->value;
        }

        $langs = [
            'en' => 'English',
            'zh-hant' => '正體中文',
            'zh-hans' => '简体中文',
        ];

        $themes = [
            "cerulean", "cosmo", "cyborg", "darkly", "flatly",
            "journal", "paper", "readable", "sandstone", "simplex", "slate",
            "united", "yeti", "lumen", "superhero", "spacelab",
        ];

        $frequency = [60, 120, 180, 240, 300];

        return view('admin.setting', [
            'config' => \Configer::get(),
            'setting' => $setting,
            'langs' => $langs,
            'themes' => $themes,
            'frequency' => $frequency,
        ]);
    }

    public function settingUpdate()
    {

        $inputs = \Input::get();
        if (!isset($inputs['maintain'])) {
            $inputs['maintain'] = 'off';
        }

        if (!isset($inputs['advance_block_mode'])) {
            $inputs['advance_block_mode'] = 'off';
        }

        if (!isset($inputs['log_guest_ip'])) {
            $inputs['log_guest_ip'] = 'off';
        }

        if (!isset($inputs['encrypt_guest_ip'])) {
            $inputs['encrypt_guest_ip'] = 'off';
        }

        foreach ($inputs as $key => $value) {
            @$check = \DB::table('setting')->where('name', $key)->first();
            if (count($check)) {
                \DB::table('setting')->where('name', $key)->update(['value' => $value]);
            }
        }

        return redirect()->action('Admin\AdminController@setting');
    }
}
