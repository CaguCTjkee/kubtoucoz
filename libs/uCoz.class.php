<?php


class uCoz extends Functions {

    public
            function __construct()
    {
        parent::__construct();
    }

    public
            function check_auth()
    {
        if( !empty($this->config['site']) )
        {
            $page = $this->getPage($this->config['site']);
            if( strpos($page, 'umoder_panel_params') === false )
                return false;
            else
                return true;
        }
        else
            $this->error('Не uCoz указан сайт. Укажите сайт в настройках.');

        return false;
    }

    public
            function add( $news )
    {
        $page = $this->post($this->config['site'] . $news['addpage']);
        $news['ssid'] = $this->getSsid($page);

        if( $news['ssid'] )
        {
            // add on site
            $post = $this->post($this->config['site'] . $news['addpage'], $news);
            $href = array();
            preg_match("#href=\"([^\"]+)\"#isu", $post, $href);
            $news['href'] = !empty($href[1]) ? $href[1] : null;

//            if( !empty($news['href']) )
//            {
//
//            }
//            else
//            {
//                preg_match("#class=\"myWinError\">([^<]+)</div>#isu", $post, $error);
//                if( isset($error[1]) && !empty($error[1]) )
//                    $this->error('<b>Ошибка:</b> ' . $error[1]);
//                else
//                {
//                    $answer = '<textarea style="width: 100%" rows="10">' . htmlspecialchars($post) . '</textarea>';
//                    $this->error('Ссылка на материал не найдена, возможно он не добавился.<br>Ответ от козы:<br>' . $answer);
//                }
//            }

            $next = false;
            if( $_POST['post'] )
            {
                $params = array(
                    'add' => 'next',
                    'category' => !empty($_POST['category']) ? $_POST['category'] : null,
                    'count' => !empty($_POST['count']) ? $_POST['count'] : 10,
                    'page' => !empty($_POST['page']) ? $_POST['page'] : 1
                );

                $next = true;
                header('Refresh: 25; url=' . $this->config['home'] . '?' . http_build_query($params));
            }
            else
                unset($_SESSION['post']);

            $this->usuccess($next, $news);
        }
        else
            $this->error('Проблема с получением ключа сессии.');
    }

    public
            function authorizate()
    {

        $u_url = $this->config['site'] . 'index/sub/';
        $page = $this->post($u_url, array(
            'user' => $this->config['login'],
            'password' => $this->config['password'],
            'rem' => 1,
            'a' => 2,
            'ajax' => 1,
            'rnd' => rand(100, 999)
        ));

        if( strpos($page, 'myWinError') !== false )
            $this->error('Авторизация на uCoz сайте не удалась, проверьте логин и пароль.', false);
    }

    public
            function getRubrik()
    {
        if( !$this->check_auth() )
            $this->authorizate();

        $page = $this->getPage($this->config['site'] . $this->config['mod'] . '/0-0-0-0-1');

        preg_match("#<select.*?name=\"cat\">(.*?)</select>#isu", $page, $modcat);
        return !empty($modcat[1]) ? $modcat[1] : '<option value="0">Категории не найдены</option>';
    }

    // получение сессии на сайтах uCoz
    private function getSsid( $str )
    {
        if( !strripos($str, 'module not installed') )
            preg_match('#ssid" value="([^"\']+)"#', $str, $ssid);
        else
            $this->error('Модуль не установлен');

        return !empty($ssid[1]) ? $ssid[1] : false;
    }

}
