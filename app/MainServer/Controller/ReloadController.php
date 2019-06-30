<?php
namespace App\MainServer\Controller;

use Imi\Controller\HttpController;
use Imi\Server\Route\Annotation\Controller;
use Imi\Server\Route\Annotation\Action;
use Imi\Server\Route\Annotation\Route;
use Imi\Server\View\Annotation\View;
use Imi\ServerManage;

/**
 * 演示
 * @Controller("/reload")
 * @View(renderType="json")
 */
class ReloadController extends HttpController
{
    /**
     * @Action
     * @Route(url="", method={"GET"})
     * @return array
     */
    public function index()
    {
        $mainSwServer = ServerManage::getServer('main')->getSwooleServer();
        $reloadStatus = $mainSwServer->reload();

        return [
            'reloadStatus' => $reloadStatus
        ];
    }

}
