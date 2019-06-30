<?php

namespace App\MainServer\Controller;

use Imi\Controller\HttpController;
use Imi\Server\Route\Annotation\Controller;
use Imi\Server\Route\Annotation\Action;
use Imi\Server\Route\Annotation\Route;
use Imi\Server\View\Annotation\View;

/**
 * 演示
 * @Controller("/Index/")
 * @View(renderType="json")
 */
class IndexController extends HttpController
{
    /**
     * @Action
     * @Route(url="", method={"GET"})
     * @return array
     */
    public function index()
    {
        return [
            'title' => 'Login demo',
            'content' => 'http://127.0.0.1:8080/demo',
        ];
    }

}
