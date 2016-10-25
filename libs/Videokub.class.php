<?php


class Videokub extends Functions {

    public
            $api = 'http://www.videokub.net/api/';
    public
            $api_array = array();
    public
            $param = array();

    public
            function __construct()
    {
        parent::__construct();
        $this->getVideos();
    }

    public
            function getVideos()
    {
        $this->getParam();
        $json = $this->getPage($this->api.implode('/', $this->param));
        $this->api_array = json_decode($json, true);

        return $this->api_array;
    }

    public
            function getParam()
    {
        if( !empty($_REQUEST['category']) )
            $this->param['category'] = $_REQUEST['category'];

        $this->param['count'] = !empty($_REQUEST['count']) ? $_REQUEST['count'] : 10;
        $this->param['page'] = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
    }

}