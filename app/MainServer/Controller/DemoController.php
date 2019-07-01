<?php
namespace App\MainServer\Controller;

use Imi\App;
use Imi\Controller\HttpController;
use Imi\Server\View\Annotation\View;
use Imi\Server\Route\Annotation\Route;
use Imi\Server\Route\Annotation\Action;
use Imi\Server\Route\Annotation\Controller;
use Logic\Form\DemoForm;

/**
 * 演示
 * @Controller("/demo")
 * @View(renderType="json")
 */
class DemoController extends HttpController
{
    /**
     * 演示所有参数为非必填
     * 
     * @Action
     * @Route(url="", method={"GET"})
     * http://127.0.0.1:8080/demo
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
     * 演示所有参数为必填
     * @Action
     * @Route(url="required", method={"GET"})
     * http://127.0.0.1:8080/demo/required
     * @return array
     */
    public function required()
    {
        $requestData = $this->request->all();
        $form = DemoForm::instance($requestData, true);

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