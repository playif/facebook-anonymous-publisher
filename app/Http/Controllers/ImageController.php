<?php namespace App\Http\Controllers;

class ImageController extends PublisherController
{

    public function __construct()
    {
        $this->background_color = [230, 230, 230];
        $this->text_color = [0, 0, 0];
        $this->font = base_path('resources/font/BiauKai.ttf');
    }

    public function preview()
    {
        $text = \Input::get('message');
        $text = $this->handleText($text);
        $image = $this->warpTextImage($text);

        ob_start();
        imagepng($image, null, 9, null);
        $image = ob_get_contents();
        ob_end_clean();
        @imagedestroy($image);
        print '<img src="data:image/png;base64,' . base64_encode($image) . '">';
    }

    public function image()
    {
        $key = strip_tags(\Route::input('key'));
        $key = str_replace('.png', '', $key);
        if (empty($key)) {
            return redirect('/');
        } else {
            $result = \DB::table('post')->where('post_key', $key)->first();
            if ($result->post_type == 3) {
                $text = $result->post_message;
                $text = $this->handleText($text);
                $image = $this->warpTextImage($text);

                ob_start();
                imagepng($image, null, 9, null);
                $image = ob_get_contents();
                ob_end_clean();
                @imagedestroy($image);
                $response = \Response::make($image);
                $response->header('Content-Type', 'image/png');

                return $response;
            } else {
                return redirect('/');
            }
        }
    }

    private function autoWrap($text)
    {
        $length = 14 * 3;
        $j = 0;
        $text = str_replace("\r", "", $text);
        $new_text = explode("\n", $text);
        $data = [];

        for ($i = 0; $i < count($new_text); $i++) {
            $count = strlen($new_text[$i]);
            if ($count > $length) {
                $temp_data = $this->utf8StringSplit($new_text[$i]);
                $temp_plus = 0;
                $temp_split = [];
                for ($k = 0; $k < count($temp_data); $k++) {
                    $temp_plus += strlen($temp_data[$k]);
                    if ($temp_plus == $length) {
                        array_push($temp_split, ($k));
                        $temp_plus = 0;
                    } elseif ($temp_plus > $length) {
                        array_push($temp_split, ($k - 1));
                        $temp_plus = strlen($temp_data[$k]);
                    }
                }
                array_push($temp_split, count($temp_data));
                $start = 0;
                $temp_string = null;
                foreach ($temp_split as $value) {
                    for ($m = $start; $m <= $value; $m++) {
                        @$temp_string = $temp_string . $temp_data[$m];
                    }
                    if ($temp_string !== '') {
                        $data[$j++] = $temp_string;
                    }
                    $start = $value + 1;
                    $temp_string = null;
                }
            } else {
                $data[$j++] = $new_text[$i];
            }
        }
        $data_length = count($data);
        if ($data_length > 10) {
            $data_length = 10;
        }
        for ($i = 0; $i < $data_length; $i++) {
            @$new_data = $new_data . $data[$i] . "\n";
        }

        return $new_data;
    }

    private function utf8StringSplit($string, $split_length = 1)
    {
        if (!preg_match('/^[0-9]+$/', $split_length) || $split_length < 1) {
            return false;
        }
        $len = mb_strlen($string, 'UTF-8');
        if ($len <= $split_length) {
            return array($string);
        }
        preg_match_all('/.{' . $split_length . '}|[^\x00]{1,' . $split_length . '}$/us', $string, $ar);
        return $ar[0];
    }

    private function handleText($text)
    {
        if (empty($text)) {
            $text = \Configer::get('page_name');
        }
        if (mb_strlen($text, 'utf-8') > 140) {
            $text = mb_substr($text, 0, 140, 'utf-8');
        }
        $text = str_replace('\n', "\n", $text);
        $text = $this->autoWrap($text);
        return $text;
    }

    private function warpTextImage($text)
    {
        $font = $this->font;
        $text_dimensions = imagettfbbox(30, 0, $font, $text);
        $text_width = abs($text_dimensions[4] - $text_dimensions[0]);
        $text_height = abs($text_dimensions[5] - $text_dimensions[1]);
        $img_width = abs($text_dimensions[4] - $text_dimensions[0]) + 40;
        $img_height = abs($text_dimensions[5] - $text_dimensions[1]) + 40;
        $image = imagecreate($img_width, $img_height);
        $background = imagecolorallocate($image, $this->background_color[0], $this->background_color[1], $this->background_color[2]);
        $color = imagecolorallocate($image, $this->text_color[0], $this->text_color[1], $this->text_color[2]);
        $x = ($img_width - $text_width) / 2 - 4;
        $y = ($img_height - $text_height) / 2 + 30;
        imagettftext($image, 30, 0, $x, $y, $color, $font, $text);
        return $image;
    }
}
