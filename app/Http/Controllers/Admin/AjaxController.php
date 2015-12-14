<?php namespace App\Http\Controllers\Admin;

class AjaxController extends AdminController
{
    public function config()
    {
        echo json_encode(\Lang::get('dashboard'));
    }

    public function queue()
    {
        @$keyword = urldecode(\Input::get('keyword'));
        $query = \DB::table('post');

        if (!empty($keyword)) {
            $this->keyword = $keyword;
            $query->where(function ($q) {
                $q->where('post_key', 'LIKE', '%' . $this->keyword . '%')
                    ->orWhere('id', 'LIKE', '%' . $this->keyword . '%')
                    ->orWhere('post_message', 'LIKE', '%' . $this->keyword . '%')
                    ->orWhere('post_user_ip', 'LIKE', '%' . $this->keyword . '%');
            });
        }

        $result = $query->orderby('id', 'desc')->paginate(100)->toArray();

        for ($i = 0; $i < count($result['data']); $i++) {
            $result['data'][$i]->post_message = $this->link($result['data'][$i]->post_message);
        }

        $data = [
            'keyword' => $keyword,
            'total' => $result['total'],
            'current_page' => $result['current_page'],
            'last_page' => $result['last_page'],
            'result' => $result['data'],
        ];

        echo json_encode($data);
    }

    public function blockGuest()
    {
        $result = \DB::table('block_guest')->orderBy('id', 'desc')->get();
        echo json_encode($result);
    }

    public function blockKeyword()
    {
        $result = \DB::table('block_keyword')->orderBy('id', 'desc')->get();
        echo json_encode($result);
    }

    public function setting()
    {
        $config = [];
        $result = \DB::table('setting')->get();
        foreach ($result as $item) {
            $config[$item->name] = $item->value;
        }
        echo json_encode($config);
    }
}
