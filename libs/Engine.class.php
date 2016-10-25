<?php


class Engine extends Functions {

    public
            $videokub = null;
    public
            $ucoz = null;
    public
            $smarty = null;
    public
            $error = '';

    public
            function __construct()
    {
        parent::__construct();

        $_GET['add'] = !empty($_GET['add']) ? $_GET['add'] : null;
        if( $_GET['add'] == 'next' )
        {
            if( isset($_SESSION['post']) )
                $_POST = $_SESSION['post'];
        }

        $_POST['why'] = !empty($_POST['why']) ? $_POST['why'] : NULL;
        if( isset($_POST['why']) )
            $this->sendForm();

        $this->setSmarty();
        $this->selectMod();
    }

    private function selectMod()
    {

        $_GET['mod'] = !empty($_GET['mod']) ? $_GET['mod'] : 'index';

        if( $_GET['mod'] == 'settings' )
        {
            $this->smarty->assign('api', $this->videokub->api_array);
            $this->smarty->assign('modcat', $this->ucoz->getRubrik());
            $this->smarty->display('settings.tpl');
        }
        else
        {
            $this->smarty->assign('api', $this->videokub->api_array);
            $this->smarty->assign('api_url', $this->videokub->api);
            $this->smarty->assign('api_param_array', $this->videokub->param);
            $this->smarty->assign('api_param', implode('/', $this->videokub->param));

            $this->smarty->assign('ids', $this->ids);

            $this->smarty->assign('modcat', $this->ucoz->getRubrik());

            $this->smarty->assign('pagination', $this->pagination());
            $this->smarty->display('index.tpl');
        }
    }

    public function saveConfig()
    {
        $this->config['fullstory'] = !empty($_POST['fullstory']) ? $_POST['fullstory'] : $this->config['fullstory'];
        $this->config['add_attach'] = !empty($_POST['add_attach']) ? 1 : 0;
        $this->config['add_tags'] = !empty($_POST['add_tags']) ? 1 : 0;
        $this->config['saw_new'] = !empty($_POST['saw_new']) ? 1 : 0;

        if( !empty($_POST['mod']) && $this->config['mod'] !== $_POST['mod'] )
            $this->config['rubrika'] = array();
        else
            $this->config['rubrika'] = !empty($_POST['rubrika']) ? $_POST['rubrika'] : $this->config['rubrika'];

        $this->config['mod'] = !empty($_POST['mod']) ? $_POST['mod'] : $this->config['mod'];
        $this->config['site'] = !empty($_POST['site']) ? $_POST['site'] : $this->config['site'];
        $this->config['login'] = !empty($_POST['login']) ? $_POST['login'] : $this->config['login'];
        $this->config['password'] = !empty($_POST['password']) ? $_POST['password'] : $this->config['password'];

        file_put_contents(ROOT . '/config.php', $this->arrayToPhp($this->config));
    }

    public
            function sendForm()
    {

        $this->saveConfig();

        if( $_POST['why'] == 'enter' )
        {
            $this->getRequest();
        }
        elseif( $_POST['why'] == 'settings' )
        {
//            $error = '';
        }
        elseif( $_POST['why'] == 'addpost' )
        {
            $this->sendRequest();
        }
    }

    private function sendRequest()
    {

        $_POST['post'] = !empty($_POST['post']) ? $_POST['post'] : null;
        if( !empty($_POST['post']) )
        {
            // uCoz class
            $this->ucoz = new uCoz;

            $news = $this->cooking();

            if( $news )
            {
                if( !$this->ucoz->check_auth() )
                    $this->ucoz->authorizate();

                $this->ucoz->add($news);
            }

            // to do
        }
        else
            $this->error = 'Не выбраны видеозаписи';
        // ucoz autorizate
    }

    private function cooking()
    {
        $return = array();
        $videos = array();
        $this->videokub = new Videokub;

        foreach($this->videokub->api_array['videos'] as $val)
            $videos[$val['id']] = $val;

        foreach($_POST['post'] as $key => $val)
        {
            if( !empty($videos[$val]['id']) )
            {
                //print_r($videos[$val]);
                $template = nl2br($_POST['fullstory']);
                // Replace [title]
                $template = preg_replace("#\\[title\\]#isu", $videos[$val]['title'], $template);
                // Replace [description]
                $template = strtr($template, array('[description]' => urldecode($videos[$val]['description'])));
                // Replace [video]
                $template = preg_replace("#\\[video=([0-9%]+)x([0-9]+)\\]#isu", '<iframe width="$1" height="$2" src="http://www.videokub.net/embed/' . $videos[$val]['id'] . '" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen oallowfullscreen msallowfullscreen></iframe>', $template);

                $tags = isset($videos[$val]['tags'][0]) ? $videos[$val]['tags'][0] : array();
                $cat = 0;

                if( isset($videos[$val]['category'][0]) )
                {
                    $category_id = key($videos[$val]['category'][0]);

                    $cat = isset($this->config['rubrika'][$category_id]) && $this->config['rubrika'][$category_id] > 0 ? $this->config['rubrika'][$category_id] : 0;
                }

                if( $this->config['add_tags'] == 0 )
                    $tags = array();

                $news = array(
                    'id' => $val,
                    'a' => 2,
                    'jkd428' => 1,
                    'jkd498' => 1,
                    'ocat' => 1,
                    'cat' => $cat,
                    'title' => $videos[$val]['title'],
                    'message' => $template,
                    'edttmessage' => 3,
                    'html_message' => 1,
                    'user' => '',
                    'file1' => '',
                    'tags' => implode(',', $tags),
                    'coms_allowed' => 1,
                    'sbm' => 'Добавить',
                    'sbcr' => 0,
                    'is_pending' => 0,
                    'ssid' => 0,
                    'addpage' => $this->config['mod'] . '/0-0-0-0-1'
                );

                if( !empty($_POST['post_mod']) && $_POST['post_mod'] == 'draft' )
                    $news['is_pending'] = 1;

                if( !empty($_POST['add_attach']) )
                {
                    $news['file1'] = '@' . $this->add_attach($videos[$val]['preview']);
                    // Replace [image]
                    $template = preg_replace("#\\[image\\]#isu", '$IMAGE1$', $template);
                }
                else
                {
                    $videos[$val]['preview'] = '<img src="' . $videos[$val]['preview'] . '" alt="' . $videos[$val]['title'] . '">';
                    // Replace [image]
                    $template = preg_replace("#\\[image\\]#isu", $videos[$val]['preview'], $template);
                }
                $news['message'] = $template;

                unset($_POST['post'][$key]);
                $_SESSION['post'] = $_POST;

                return $news;
            }
            else
                return false;
        }
        return false;
    }

    private
            function add_attach( $url )
    {
        $temp = pathinfo($url, PATHINFO_BASENAME);
        $this->save_image($url, ROOT . '/cache/' . $temp);
        $image = ROOT . '/cache/' . $temp;

        return $image;
    }

    private
            function getRequest()
    {
        $request = array();
        $request[] = !empty($_POST['page']) ? 'page=' . $_POST['page'] : 'page=1';
        $request[] = !empty($_POST['count']) ? 'count=' . $_POST['count'] : 'count=10';
        if( !empty($_POST['category']) )
            $request[] = 'category=' . $_POST['category'];

        header('Location: ' . $this->config['home'] . '?' . implode('&', $request));
    }

    private
            function pagination()
    {
        $count = !empty($_GET['count']) ? $_GET['count'] : 10;
        $pagenow = !empty($_GET['page']) ? (int) $_GET['page'] : 1;
        $lastpage = ceil($this->videokub->api_array['videos_count'] / $count);

        $request = array();
        $request[] = 'count=' . $count;
        if( !empty($_GET['category']) )
            $request[] = 'category=' . $_GET['category'];

        $pagination = array();

        $pagination['firstpage'] = $this->config['home'] . '?' . implode('&', $request);

        if( $pagenow > 1 )
            $pagination['prevpage'] = $this->config['home'] . '?' . implode('&', $request) . '&page=' . ($pagenow - 1);
        else
            $pagination['prevpage'] = $this->config['home'] . '?' . implode('&', $request);

        if( $pagenow < $lastpage )
            $pagination['nextpage'] = $this->config['home'] . '?' . implode('&', $request) . '&page=' . ($pagenow + 1);
        else
            $pagination['nextpage'] = $this->config['home'] . '?' . implode('&', $request) . '&page=' . ($lastpage);

        $pagination['lastpage'] = $this->config['home'] . '?' . implode('&', $request) . '&page=' . $lastpage;

        return $pagination;
    }

}
