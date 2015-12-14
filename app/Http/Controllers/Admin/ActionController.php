<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Facebook;

class ActionController extends AdminController
{
    public function unpublish()
    {
        @$id = (int) \Route::input('id');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        if (!empty($id) && $id > 0) {
            $post = \DB::table('post')->where('id', $id)->first();
            if (count($post) > 0 && $post->post_state == 1) {
                $response = $this->unpublishPostFromFacebook($post);
            }
        }

        echo json_encode($response);
    }

    public function republish()
    {
        @$id = (int) \Route::input('id');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        if (!empty($id) && $id > 0) {
            $post = \DB::table('post')->where('id', $id)->first();
            if (count($post) > 0 && ($post->post_state == 2 || $post->post_state == 4 || $post->post_state == 8 || $post->post_state == 9)) {
                $response = $this->publishPostToFacebook($post);
            }
        }

        echo json_encode($response);
    }

    public function deny()
    {
        @$id = (int) \Route::input('id');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        if (!empty($id) && $id > 0) {
            $post = \DB::table('post')->where('id', $id)->first();
            if (count($post) > 0 && ($post->post_state == 0 || $post->post_state == 5)) {
                try {
                    \DB::table('post')->where('id', $id)->update(['post_state' => 4]);
                    $response = ['state' => 'success', 'message' => 'Update success.'];
                } catch (Exception $e) {
                    $response = ['state' => 'failed', 'message' => 'Update failed.'];
                }
            }
        }

        echo json_encode($response);
    }

    public function allow()
    {
        @$id = (int) \Route::input('id');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        if (!empty($id) && $id > 0) {
            $post = \DB::table('post')->where('id', $id)->first();
            if (count($post) > 0 && $post->post_state == 5) {
                $response = $this->publishPostToFacebook($post);
            }
        }

        echo json_encode($response);
    }

    public function block()
    {
        @$id = (int) \Route::input('id');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        if (!empty($id) && $id > 0) {
            $post = \DB::table('post')->where('id', $id)->first();
            if (count($post) > 0) {
                $ip = $post->post_user_ip;
                $check = \DB::table('block_guest')->where('ip', $ip)->first();
                if (count($check) > 0) {
                    $response = ['state' => 'success', 'message' => 'Data exist.'];
                } else {
                    if (\DB::table('block_guest')->insert(['ip' => $ip])) {
                        $response = ['state' => 'success', 'message' => 'Block success.'];
                    } else {
                        $response = ['state' => 'failed', 'message' => 'Block failed.'];
                    }
                }
            }
        }

        echo json_encode($response);
    }

    public function delete()
    {
        @$table = \Route::input('table');
        @$id = (int) \Route::input('id');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        if (!empty($table) && !empty($id) && $id > 0) {
            $item = \DB::table($table)->where('id', $id)->first();
            if (count($item)) {
                try {
                    \DB::table($table)->where('id', $id)->delete();
                    $response = ['state' => 'success', 'message' => 'Delete success.'];
                } catch (Exception $e) {
                    $response = ['state' => 'failed', 'message' => 'Delete failed.'];
                }
            }
        }

        echo json_encode($response);
    }

    public function addKeyword()
    {
        @$keyword = \Input::get('keyword');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        if ($keyword != '' && $keyword != null) {
            if (\DB::table('block_keyword')->insert(['keyword' => $keyword])) {
                $response = ['state' => 'success', 'message' => 'Add success.'];
            } else {
                $response = ['state' => 'failed', 'message' => 'Add failed.'];
            }
        }

        echo json_encode($response);
    }

    public function updateSetting()
    {
        @$name = \Input::get('name');
        @$value = \Input::get('value');
        $response = ['state' => 'error', 'message' => 'Something wrong.'];

        $check = \DB::table('setting')->where('name', $name)->first();
        if (count($check)) {
            try {
                \DB::table('setting')->where('name', $name)->update(['value' => $value]);
                $response = ['state' => 'success', 'message' => 'Update success.'];
            } catch (Exception $e) {
                $response = ['state' => 'failed', 'message' => 'Update failed.'];
            }
        }

        echo json_encode($response);
    }

}
