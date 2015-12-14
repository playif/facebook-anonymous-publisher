<?php namespace App\Http\Controllers;

use App\Http\Extensions\utf8_chinese_str;

class PublisherController extends Controller
{
    public function submit()
    {
        $post_message = \Input::get('message');
        $mode = (int) \Input::get('mode');
        $recaptcha = \Input::get('recaptcha');
        $response = ['state' => 'deny'];

        $recaptcha_check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . \Configer::get('recaptcha_secret') . '&response=' . $recaptcha, false, stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]));
        $recaptcha_check = json_decode($recaptcha_check, true);

        if (@$recaptcha_check['success']) {
            if (mb_strlen($post_message, 'utf-8') > 1024) {
                $post_message = mb_substr($post_message, 0, 1024, 'utf-8');
            }

            $check = \DB::table('post')->where('post_message', $post_message)->where('post_type', $mode)->where('insert_time', '>', time() - 3600)->first();
            if (count($check)) {
                $response = [
                    'state' => 'success',
                    'redirct' => '/' . $check->post_key,
                ];
            } else {
                $post_key = $this->base62(strrev(time())) . $this->randString(3);
                $post_state = 0;
                $post_type = 1;
                $post_url = null;
                $insert_time = time();

                $last = \DB::table('post')->where('post_state', '1')->where('post_type', $mode)->orderBy('id', 'desc')->first();

                if (count($last) > 0) {
                    $query = 'UPDATE post SET post_state = 8 WHERE id < ' . $last->id . ' AND post_state = 0';
                    \DB::update(\DB::raw($query));
                }

                @$posts = \DB::table('post')->where('post_state', 0)->count();

                if (count($last) > 0 && (time() - $last->publish_time >= \Configer::get('publish_frequency')) && $posts == 0) {
                    $publish_time = time();
                } else {
                    $publish_time = time() + (\Configer::get('publish_frequency') - time() % \Configer::get('publish_frequency')) + $posts * \Configer::get('publish_frequency');
                }

                if ($mode == 3) {
                    $post_type = 3;
                }
                if (strlen($this->searchUrl($post_message))) {
                    $post_type = 2;
                    $post_url = $this->searchUrl($post_message);
                } else {
                    $post_url = '0';
                }

                if (\Configer::get('log_guest_ip') == "on") {
                    $post_user_ip = $this->getUserIP();

                    if (\Configer::get('encrypt_guest_ip') == "on") {
                        $post_user_ip = md5($post_user_ip);
                    }

                    $check = \DB::table('block_guest')->where('ip', $post_user_ip)->first();
                    if (count($check)) {
                        $post_state = 5;
                    }

                } else {
                    $post_user_ip = '0';
                }

                $block_keyword = \DB::table('block_keyword')->get();
                if (count($block_keyword)) {
                    foreach ($block_keyword as $item) {
                        if (!empty($item->keyword)) {
                            if (strpos($post_message, $item->keyword) !== false) {
                                $post_state = 5;
                            }
                        }
                    }

                    if (\Configer::get('advance_block_mode') == 'on') {
                        $covert = new utf8_chinese_str;
                        $pinyin = \App::make('pinyin');
                        $gb_post_message = $covert->big5_gb2312($post_message);
                        $pinyin_post_message = $pinyin->trans($gb_post_message, ['delimiter' => '-', 'accent' => false, 'only_chinese' => true]);
                        foreach ($block_keyword as $item) {
                            $gb_keyword = $covert->big5_gb2312($item->keyword);
                            $pinyin_keyword = $pinyin->trans($gb_keyword, ['delimiter' => '-', 'accent' => false, 'only_chinese' => true]);
                            if (!empty($pinyin_post_message) && !empty($pinyin_keyword)) {
                                if (strpos($pinyin_post_message, $pinyin_keyword) !== false) {
                                    $post_state = 5;
                                }
                            }
                        }
                    }
                }

                $post = [
                    'post_key' => $post_key,
                    'post_state' => $post_state,
                    'post_type' => $post_type,
                    'post_message' => $post_message,
                    'post_url' => $post_url,
                    'post_user_ip' => $post_user_ip,
                    'insert_time' => $insert_time,
                    'publish_time' => $publish_time,
                    'facebook_url' => '0',
                ];

                if (\DB::table('post')->insert($post)) {
                    $response = [
                        'state' => 'success',
                        'redirct' => '/' . $post_key,
                    ];
                }
            }
        }

        echo json_encode($response);
    }

    public function check()
    {
        @$key = strip_tags(\Route::input('key'));

        if (empty($key)) {
            return redirect('/');
        } else {
            $result = \DB::table('post')->where('post_key', $key)->first();

            if (isset($result->id)) {
                if ($result->post_state == 1) {
                    $exp = explode('_', $result->facebook_url);
                    $url = 'https://facebook.com/' . $exp[0] . '/posts/' . $exp[1];
                    return redirect($url);
                } else {
                    if ($result->publish_time - time() >= 0) {
                        $state = '<div class="alert alert-danger text-center" role="alert">' . \Lang::get('check.do_not_close_window') . '</div><h4>' . \Lang::get('check.remaining_time') . ': <span id="countdown" data-timestamp="' . $result->publish_time . '"></span></h4><p>' . \Lang::get('check.auto_redirect_message') . '</p>';
                        $foot = '<script src="/js/check.js" type="text/javascript"></script>';
                    } elseif ($result->publish_time - time() < 0 && $result->post_state == 0) {
                        $last = \DB::table('post')->where('post_state', '1')->orderBy('id', 'desc')->first();
                        if (count($last) > 0) {
                            $query = 'UPDATE post SET post_state = 8 WHERE id < ' . $last->id . ' AND post_state = 0';
                            \DB::update(\DB::raw($query));
                        }
                        $this->publishPostToFacebook($result);
                        header("Refresh:0");
                        exit;
                    } elseif ($result->publish_time - time() < 0 && $result->post_state == 5) {
                        $state = '<h4>' . \Lang::get('check.pending') . '</h4><p>' . \Lang::get('check.pending_message') . '</p>';
                        $foot = '';
                    } else {
                        $state = '<h4>' . \Lang::get('check.oops') . '</h4><p>' . \Lang::get('check.oops_message') . '<br />' . \Lang::get('check.try_contact_with') . '<a href="http://facebook.com/' . \Configer::get('page_id') . '">' . \Lang::get('check.page_admin') . '</a></p>';
                        $foot = '';
                    }

                    return view('check', [
                        'config' => \Configer::get(),
                        'state' => $state,
                        'foot' => $foot,
                    ]);
                }
            } else {
                return redirect('/');
            }
        }
    }
}
