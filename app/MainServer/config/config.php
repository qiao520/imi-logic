<?php

use Imi\Log\LogLevel;
return [
    'configs'    =>    [

    ],
    // bean扫描目录
    'beanScan'    =>    [
        'App\MainServer\Controller',
    ],
    'beans'    =>    [
        'HtmlView'    =>    [
            'templatePath'    =>    dirname(__DIR__) . '/template/',
        ],
        'SessionManager'    =>    [
            'handlerClass'    =>    \Imi\Server\Session\Handler\File::class,
        ],
        'SessionConfig'    =>    [

        ],
        'SessionCookie'    =>    [
            'lifetime'    =>    86400 * 30,
        ],
        "SessionFile"    =>    [
            'savePath'    =>    dirname(__DIR__, 3) . '/session',
        ],
        'SessionRedis'    =>    [
            'poolName'    =>    'redis'
        ],
        'HttpDispatcher'    =>    [
            'middlewares'    =>    [
                \Imi\Server\Session\Middleware\HttpSessionMiddleware::class,
                \Imi\Server\Http\Middleware\RouteMiddleware::class,
                // \ImiDemo\HttpDemo\Middlewares\PoweredBy::class,
            ],
        ],
        'View'    =>    [
            'data'    =>    [
                // 这里可以设置视图默认带的数据
            ],
        ],
    ],
];