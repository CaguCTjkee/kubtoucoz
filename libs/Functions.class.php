<?php


class Functions {

    public
            $config = array(
        'home' => 'http://caguct.com/kubtoucoz/index.php',
        'THEME' => '/kubtoucoz/templates',
        'fullstory' => '[image]\n[description]\n$CUT$\n[video=680x450]',
        'add_attach' => 1,
        'add_tags' => 1,
        'rubrika' => 0,
        'mod' => 'news',
        'saw_new' => 1,
        'site' => '',
        'login' => '',
        'password' => ''
    );
    public
            $useragent = 'Opera/9.80 (Windows NT 5.1; U; MRA 5.9 (build 4848); ru) Presto/2.9.168 Version/11.52';
    public
            $error = '';
    public
            $ids = array();

    public
            function __construct()
    {
        if( is_file(ROOT . '/config.php') )
            $this->config = include ROOT . '/config.php';

        $url = array();
        preg_match("#^http[s]*://([^/]+).*$#isu", $this->config['site'], $url);
        $site = $url[1];
        if( is_file(ROOT.'/'.$site.'.tmp') )
            $this->ids = unserialize(file_get_contents(ROOT.'/'.$site.'.tmp'));
        else
            $this->ids = array();
    }

    public
            function usuccess( $next, $news )
    {
        // add in file
        $url = array();
        preg_match("#^http[s]*://([^/]+).*$#isu", $this->config['site'], $url);
        $site = $url[1];
        $this->ids[$news['id']] = 1;
        file_put_contents(ROOT.'/'.$site.'.tmp', serialize($this->ids));

        $this->setSmarty();
        $this->smarty->assign('next', $next);
        $this->smarty->assign('news', $news);

        $this->error($this->smarty->fetch('_success.tpl'));
    }

    public
            function error( $error, $die = true )
    {
        $this->error = $error;
        $this->setSmarty();
        $this->smarty->display('_error.tpl');
        if( $die ) die();
    }

    public
            function save_image( $kartinka, $kuda_sohranit )
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $kartinka);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);

        $dir = pathinfo($kuda_sohranit, PATHINFO_DIRNAME) . '/';
        if( !is_dir($dir) )
        {
            @mkdir($dir, 0777);
            @chmod($dir, 0777);
        }
        $fp = file_put_contents($kuda_sohranit, $content);
    }

    public
            function setSmarty()
    {
        $this->videokub = new Videokub;
        $this->smarty = new Smarty;
        $this->ucoz = new uCoz;

        $this->smarty->template_dir = ROOT . '/templates/';
        $this->smarty->compile_dir = ROOT . '/templates_c/';
        $this->smarty->config_dir = ROOT . '/configs/';
        $this->smarty->cache_dir = ROOT . '/cache/';
        $this->smarty->left_delimiter = '{{';
        $this->smarty->right_delimiter = '}}';

        $this->smarty->assign('error', $this->error);

        $this->smarty->assign('config', $this->config);
    }

    public
            function getPage( $url, $reffer = null )
    {
        $cookie = 'cookies.txt';
        // имитация браузера
        $uagent = $this->useragent;
        if( $reffer == null )
            $reffer = false;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // возвращает веб-страницу
        curl_setopt($ch, CURLOPT_HEADER, 0);           // не возвращает заголовки
        curl_setopt($ch, CURLOPT_ENCODING, '');        // обрабатывает все кодировки
        curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // таймаут соединения
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // таймаут ответа
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // останавливаться после 10-ого редиректа
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($ch, CURLOPT_REFERER, $reffer);

        $content = curl_exec($ch);
        curl_close($ch);
//        return file_get_contents($url);
        return $content;
    }

    public
            function arrayToPhp( $array, $name = "config", $inc = 0 )
    {
        ob_start();
        if( is_array($array) )
        {
            if( $name !== null )
            {
                echo "<?php\n";
                echo '$' . $name . ' = ';
            }

            echo "array(\n";

            foreach($array as $key => $item)
            {
                echo str_repeat("\t", $inc + 1);

                if( !is_array($item) )
                {
                    echo '\'' . $key . '\'' . ' => \'' . str_replace('\'', "\'", $item) . '\',' . "\n";
                }
                else
                {
                    echo '\'' . $key . '\' => ';
                    echo $this->arrayToPhp($item, null, ($inc + 1));
                }
            }

            if( $name !== null )
            {
                echo str_repeat("\t", $inc);
                echo ');' . "\n";
                echo 'return $' . $name . ';' . "\n";
            }
            else
            {
                echo str_repeat("\t", $inc);
                echo '),' . "\n";
            }
        }
        return ob_get_clean();
    }

    public
            function post( $url, $post = null, $reffer = null )
    {
        $uagent = $this->useragent;
        if( $post == null )
            $post = 0;
        if( $reffer == null )
            $reffer = $url;
        $cookie = 'cookies.txt';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); // Устанавливаем URL на который посылать запрос
        curl_setopt($ch, CURLOPT_HEADER, 0); //  Результат будет содержать заголовки
        curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); // Сюда будем записывать cookies, файл в той же папке, что и сам скрипт

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Результат будет возвращём в переменную, а не выведен.
        curl_setopt($ch, CURLOPT_TIMEOUT, 4); // Таймаут после 4 секунд
        curl_setopt($ch, CURLOPT_POST, 1); // Устанавливаем метод POST
        curl_setopt($ch, CURLOPT_REFERER, $reffer);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }

}
