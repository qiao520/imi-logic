<?php

namespace App\MainServer\Controller;

use Imi\Controller\HttpController;
use Imi\Server\Route\Annotation\Controller;
use Imi\Server\Route\Annotation\Action;
use Imi\Server\Route\Annotation\Route;
use Imi\Server\View\Annotation\View;
use Logic\Form\DemoForm;

/**
 * 演示
 * @Controller("/")
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
        $requestData = $this->request->getQueryParams();
        $form = DemoForm::instance($requestData);

        if ($form->validate()) {
            $result = $form->handle();

            return $this->success('ok', $result);
        }

        return $this->error($form->getError());
    }

    /**
     * 请求成功响应格式
     * @param $msg
     * @param array $data
     * @param int $code
     * @return array
     */
    private function success($msg, $data = [], $code = 200) {
        return compact('msg', 'data', 'code');
    }

    /**
     * 请求失败响应格式
     * @param $msg
     * @param int $code
     * @param array $data
     * @return array
     */
    private function error($msg, $code = 0, $data = []) {
        return compact('msg', 'data', 'code');
    }
}
