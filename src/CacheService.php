<?php

namespace RfplLaravel;

use RfplLaravel\Services\Cache;

class CacheService{

    protected $byPassRoutes;
    protected $ttl;
    protected $cachePath;
    protected $url;
    protected $method;
    protected $queryString;

    /**
     * Constructor function Initizlize Necessory Options
     *
     * @param array $byPassRoutes
     * @param $ttl
     * @param $cachePath
     *
     */
    public function __construct(array $byPassRoutes=[],$ttl=null,$cachePath=null)
    {
        $this->byPassRoutes = $byPassRoutes;
        $this->cachePath    = $cachePath;
        $this->ttl          = $ttl;
        $this->url          = $_SERVER['REQUEST_URI'];
        $this->method       = $_SERVER['REQUEST_METHOD'];
        $this->queryString  = $_SERVER['QUERY_STRING'];
    }

    /**
     * Handle Request
     */
    public function handle(){

        /**
         * Serve Cache for this route if this is not in exception list
         */
        if ( !$this->inExceptArray($this->url) && !$this->isAjax()){
            $options = [];

            if ( $this->ttl ){
                $options['ttl'] = $this->ttl;
            }

            if ( $this->cachePath ){
                $options['path'] = $this->cachePath;
            }

            $cache = new Cache($options);

            $cache->serve();
        }
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($url)
    {
        foreach ($this->byPassRoutes as $except) {
            // if ($except !== '/') {
            //     $except = trim($except, '/');
            // }

            /**
             * Check if match has astrix
             */
            $except = str_replace("/","\/",$except);

            /**
             * Check if Asterisk Patteren Given
             */
            if ( strpos($except,"*") ){
                $except = str_replace("*",".*",$except);

                if (preg_match("/".$except."/",$url)){
                    return true;
                }
            /**
             * Match Not Asterisk Patteren
             */
            }else if (preg_match("/\/".$except."$/",$url)){
                return true;
            }
        }

        return false;
    }

    /**
     * Check if request is json Request
     */
    protected  function isAjax(){
        if ( strpos($_SERVER['HTTP_ACCEPT'],'application/json') || $_SERVER['HTTP_X_REQUESTED_WITH'] ==='XMLHttpRequest' ){
            return true;
        }

        return false;
    }
}
