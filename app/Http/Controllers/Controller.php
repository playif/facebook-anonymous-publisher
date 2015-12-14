<?php namespace App\Http\Controllers;

use Facebook;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use DispatchesCommands, ValidatesRequests;

    public function __construct()
    {
        \App::setLocale(\Configer::get('lang'));
    }

    protected function searchUrl($text)
    {
        $regex = '/https?\:\/\/[^\" \r\n]+/i';
        preg_match($regex, $text, $matches);
        if (count($matches)) {
            return $matches[0];
        } else {
            return null;
        }
    }

    protected function randString($length = 10)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < $length; $i++) {
            @$string .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $string;
    }

    protected function base62($num)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $r = $num % 62;
        $res = $chars[$r];
        $q = floor($num / 62);
        while ($q) {
            $r = $q % 62;
            $q = floor($q / 62);
            $res = $chars[$r] . $res;
        }
        return $res;
    }

    protected function getUserIP()
    {
        if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ip[0];
        }
        return $ip;
    }

    public function link($text)
    {
        $text = preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\">$3</a>", $text);
        $text = preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" target=\"_blank\">$3</a>", $text);
        $text = preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\" target=\"_blank\">$2@$3</a>", $text);
        return ($text);
    }

    public function publishPostToFacebook($post)
    {
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        $fb = new Facebook(\Configer::get('fb_app_setting'));
        $fb->setDefaultAccessToken(\Configer::get('fb_page_token'));

        $page_name = str_replace(' ', '_', \Configer::get('page_name'));
        $message = "#" . $page_name . $post->id . " " . \Configer::get('custom_message');
        $target = 'feed';
        switch ((int) $post->post_type) {
            case 2:
                $message .= " \n\n " . $post->post_message;
                $data['link'] = $post->post_url;
                break;
            case 3:
                $target = 'photos';
                $data['url'] = url() . '/i/' . $post->post_key . '.png';
                break;
            default:
                $message .= " \n\n " . $post->post_message;
                break;
        }
        $data['message'] = $message;
        $post_state = 9;
        $facebook_url = '0';

        try {
            $callback = $fb->post('/me/' . $target . '/', $data);
            $callback = $callback->getDecodedBody();

            if ($callback['id']) {
                $post_state = 1;
                if ((int) $post->post_type == 3) {
                    $facebook_url = $callback['post_id'];
                } else {
                    $facebook_url = $callback['id'];
                }
            }

            \DB::table('post')->where('id', $post->id)->update(['post_state' => $post_state, 'facebook_url' => $facebook_url]);
            $response = ['state' => 'success', 'message' => 'Publish success.'];

        } catch (Exception $e) {
            \DB::table('post')->where('id', $post->id)->update(['post_state' => $post_state]);
        }

        return $response;
    }

    public function unpublishPostFromFacebook($post)
    {
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        $fb = new Facebook(\Configer::get('fb_app_setting'));
        $fb->setDefaultAccessToken(\Configer::get('fb_page_token'));

        try {
            $callback = $fb->delete('/' . $post->facebook_url);
            $callback = $callback->getDecodedBody();

            if ($callback['success'] == 1) {
                \DB::table('post')->where('id', $post->id)->update(['post_state' => 2]);
                $response = ['state' => 'success', 'message' => 'Post unpublished.'];
            } else {
                $response = ['state' => 'failed', 'message' => 'Post unpublish failed.'];
            }
        } catch (Exception $e) {
            $response = ['state' => 'failed', 'message' => 'Post unpublish failed.'];
        }

        return $response;
    }
}
