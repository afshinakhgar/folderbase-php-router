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


        foreach($routestr as $route => $route_file){
            if(strpos($route,':') !== false){
                // triming array element (params)
                $routePart = array_map(function($arrEl){
                    return trim($arrEl,'/');
                },explode(':',$route));
                $routeAddress = $routePart[0];
                // parameter passing string /a/b/c/d/
                $paramString = trim(str_replace($routePart[0],'',$param),'/');
               
                if($routeAddress == trim(str_replace($paramString,'',$param),'/')){
                    // route address from first array element
                    $params = explode('/',$paramString);
                    $routeOpt[$route]['parameters'] = trim(str_replace($routePart[0],'',$param),'/');
                    unset($routePart[0]);
                    $routeOpt[$route]['params'] = $routePart; 
                    $hasParam = true;
                }
            }
        }
        if(isset($hasParam) && isset($params) && is_array($params)){
            for($i=0 ; $i < count($params) -1 ; $i++){
                if($i % 2 == 0 ){
                    if(isset($params[$i])){
                        $pr[$params[$i]] = $params[$i+1];
                    }
                }
            }
            
        }
        // array of address
        $Adr   = explode('/', $param);
        if(isset($hasParam)){
            // $param = str_replace($paramString,'',$param);
            $Adr   = explode('/', trim($route,'/'));
            // $Adr.
            $GLOBALS['parameters'] = $pr;
        }

        
        // calculation of query strings in url string
        $QueryStringDetected = false;
        $routeAddress = '';
        foreach($Adr as $urlEl){
            if(strpos($urlEl, '?') !== false) {
                $queryStringArr = explode('?',$urlEl);
                $urlEl = $queryStringArr[0];
                $QueryStringDetected = true;
                // break;
                $queryString = $queryStringArr[1];
            }
            $routeAddress .= $urlEl.'/';
        }
        $this->page = trim($routeAddress,'/');


       if(isset($queryString)){
            $queryStringArr = explode('&',$queryString);
            foreach($queryStringArr as $queryString){
                $queryStringValKey = explode('=',$queryString);
                @$_GET[$queryStringValKey[0]] = $queryStringValKey[1];
                @$queries[$queryStringValKey[0]] = $queryStringValKey[1];
            }
       }
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
        GLOBAL $parameters ;
        $parameters = $GLOBALS['parameters'];
        require($routesArr[$this->page]);
        exit;
    }
}
