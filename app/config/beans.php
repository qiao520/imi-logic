<?php
$rootPath = dirname(__DIR__) . '/';
return [
    'hotUpdate'    =>    [
         'status'    =>    false, // 关闭热更新去除注释，不设置即为开启，建议生产环境关闭

        // --- 文件修改时间监控 ---
        // 'monitorClass'    =>    \Imi\HotUpdate\Monitor\FileMTime::class,
        // 'timespan'    =>    1, // 检测时间间隔，单位：秒

        // --- Inotify 扩展监控 ---
        // 'monitorClass'    =>    \Imi\HotUpdate\Monitor\Inotify::class,
        // 'timespan'    =>    0, // 检测时间间隔，单位：秒，使用扩展建议设为0性能更佳

        // 'includePaths'    =>    [], // 要包含的路径数组
        'excludePaths'    =>    [
            $rootPath . '.git',
            $rootPath . 'bin',
        ], // 要排除的路径数组，支持通配符*
        // 'defaultPath'    =>    [], // 设为数组则覆盖默认的监控路径
    ],
    'Logger'    =>    [
        // 'coreHandlers'    =>    [],
        'exHandlers'    =>    [
            [
                'class'        =>    \Imi\Log\Handler\File::class,
                'options'    =>    [
                    'levels'    =>    \Imi\Log\LogLevel::ALL,
                    'fileName'    =>    dirname(__DIR__, 2) . '/log.log',
                    'format'    =>    "{Y}-{m}-{d} {H}:{i}:{s} [{level}] {message}\n{trace}",
                    'traceFormat'    =>    '#{index}  {call} called at [{file}:{line}]',
                    'traceMinimum'  =>  true,
                ],
            ]
        ],
    ],
];