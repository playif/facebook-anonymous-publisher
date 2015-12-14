<?php namespace App\Http\Extensions;

class Configer
{
    public static function get($param = null)
    {
        $default_config = \Lang::get('default');
        $main_config = \Config::get('publisher');
        $custom_setting = \Configer::getSetting();
        \App::setLocale($custom_setting['lang']);
        $config = array_merge(array_filter($default_config), array_filter($custom_setting), array_filter($main_config));
        @$license = explode("\n", $config['license']);
        if (count($license)) {
            $config['license'] = $license;
        }

        if (isset($param)) {
            if (isset($config[$param])) {
                return $config[$param];
            } else {
                return false;
            }
        } else {
            return $config;
        }
    }

    public static function check()
    {
        $main_config = \Config::get('publisher');
        $custom_setting = \Configer::getSetting();
        $config = array_merge(array_filter($main_config), array_filter($custom_setting));
        @$license = explode("\n", $config['license']);
        if (count($license)) {
            $config['license'] = $license;
        }
        $result = [];

        foreach (\Lang::get('alert') as $key => $value) {
            if (count(explode('.', $key)) == 2) {
                $exp = explode('.', $key);
                $check = $config[$exp[0]][$exp[1]];
            } else {
                $check = $config[$key];
            }
            if (empty($check)) {
                array_push($result, $value);
            }
        }

        return $result;
    }

    public static function getSetting()
    {
        $default_setting = [
            "maintain" => "off",
            "lang" => "en",
            "bootstrap_theme" => "cyborg",
            "site_name" => "",
            "site_title" => "",
            "site_description" => "",
            "page_id" => "",
            "page_name" => "",
            "say_hello" => "",
            "license" => "",
            "publish_frequency" => 120,
            "custom_message" => "",
            "advance_block_mode" => "on",
            "log_guest_ip" => "on",
            "encrypt_guest_ip" => "on",
        ];

        $result = \DB::table('setting')->get();

        if (count($result) != count($default_setting)) {
            foreach ($default_setting as $name => $value) {
                \DB::table('setting')->insert([
                    'name' => $name,
                    'value' => $value,
                ]);
            }
            return $default_setting;
        } else {
            $setting = [];
            foreach ($result as $item) {
                $setting[$item->name] = $item->value;
            }
            return $setting;
        }
    }
}
