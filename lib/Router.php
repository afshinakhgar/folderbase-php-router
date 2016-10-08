<?php
class Router {
    // base uri of website
    public $siteUrl;
    // connection protocol ssl or ...   
    public $url_protocol;
    // requested page
    public $page;


    public $notfoundAddress = '404.php';

    public function __construct($mainPage = 'home')
    {
        // main page for main index home of site
        $this->page = $mainPage;
        // protocol recognizer http or ssl 
        $this->url_protocol = 'http://';
        if(@$_SERVER['HTTPS'] == 'on' || @$_SERVER['SERVER_PORT'] == 443){
            $this->url_protocol = 'https://';
        }
        if($_SERVER["HTTP_HOST"]=='localhost'){
            $this->siteUrl = $this->url_protocol.$_SERVER['HTTP_HOST'].'/'.explode('/',$_SERVER['PHP_SELF'])[1].'/';
        } else {
            $this->siteUrl = $this->url_protocol.$_SERVER['HTTP_HOST'].'/';
        }

    }
    public function route($routestr = '')
    {
        
        $siteUrl = trim($this->siteUrl,'/');

        // full url of site with all parameters 
        $fullUrl = $this->url_protocol.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        // get just parameters from url
        $param = str_replace($siteUrl,'',$fullUrl);
        $Adr   = explode('/', $param);

        // calculation of query strings in url string

        if($Adr[1]){
            if(strpos($Adr[1], '?') !== false){
                $addressParams = explode('?',$Adr[1]);
                $this->page = $Adr[1] = $addressParams[0];
                $queryStringArr = explode('&',$addressParams[1]);
                foreach($queryStringArr as $queryString){
                    $queryStringValKey = explode('=',$queryString);
                    @$_GET[$queryStringValKey[0]] = $queryStringValKey[1];
                    @$queries[$queryStringValKey[0]] = $queryStringValKey[1];
                }
            }else{
                $this->page = $Adr[1];
            }
        }

        // for sub url support
        $urlStr = implode('/',$Adr);
        $urlStr = (ltrim($urlStr,'/'));
        if(isset($routestr[$urlStr])){
            $this->page =$urlStr;
        }
        // sub/iran

        // without route for home page
        if(strlen($Adr[1]) == 0){
            $this->page = '/';
        }

        /*
            Parameters are some variables that pass with url
            ex . akhgar.net/name/afshin/lastname/akhgar
            name => afshin , lastname => akhgar
        */
        $parameters = array();
        foreach($Adr as $key=>$rowParam){
            if($key<2) continue;
            $parameters[] = $rowParam;
        }
        if(!$this->page) $this->page = 'home';

        return array(
            'page'=>$this->page,
            'params'=> isset($parameters) ? $parameters : array(),
            'querystring'=> isset($queries) ? $queries : array(),
        );
    }

    // run router
    /**
    * @param array $routesArr 
    */
    public function go($routesArr){
        $pageParamsArr = $this->route($routesArr);
        if(!isset($routesArr[$this->page])){
            require($this->notfoundAddress);
            exit;
        }
        
        $folder = explode('/',$routesArr[$this->page]);
        $assetsUrl =  '' ;

        $folderRoute = array_slice($folder,0,count($folder)-1); 
        $assetsUrl =  $folderRoute = implode('/',$folderRoute);

        $includePath = $assetsUrl.'/';
        $assetsUrl = $this->siteUrl.$assetsUrl.'/';
        // $routesArr[$this->page];


        require($routesArr[$this->page]);
        exit;
    }
}
