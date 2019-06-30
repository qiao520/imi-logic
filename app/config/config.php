<?php
use Imi\Server\Type;

// 注释项代表可省略的，使用默认值
return [
    // 项目根命名空间
    'namespace'    =>    'App',
    // 扫描目录
    'beanScan'    =>    [
    ],
    // 主服务器配置
    'mainServer'    =>    [
        'namespace'    =>    'App\MainServer',
        'type'        =>    Type::HTTP,
        // 'host'        =>    '0.0.0.0',
        'port'        =>    8080,
        // 'mode'        =>    SWOOLE_BASE,
        // 'sockType'    =>    SWOOLE_SOCK_TCP,
        'configs'    =>    [
            'reactor_num'        =>    1,
            'worker_num'        =>    1,
            'task_worker_num'    =>    1,
        ],
    ],
    // 子服务器（端口监听）配置
    'subServers'        =>    [],
    // 配置文件
    'configs'    =>    [
        'beans'        =>    __DIR__ . '/beans.php',
    ],
];