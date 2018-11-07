<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * 应该用来检测代理的头信息。
     *
     * @var string
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
    /**
     * The trusted proxies for this application.
     *
     * @var array
     */
    protected $proxies;


}
