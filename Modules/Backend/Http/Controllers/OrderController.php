<?php
/**
 * 订单
 * Author: CK
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class OrderController extends Controller
{
    /**
     * 订单的添加
     * @return array
     */
    public function orderAdd(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderAdd($params);
        return $result;
    }

    /**
     * 订单的修改
     * @return array
     */
    public function orderEdit(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderEdit($params);
        return $result;
    }

    /**
     * 订单的详情
     * @return array
     */
    public function orderDetail(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderDetail($params);
        return $result;
    }

    /**
     * 红包过期
     * @return array
     */
    public function orderOver(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderOver($params);
        return $result;
    }

    /**
     * 获取订单二维码
     * @return array
     */
    public function orderQrcode(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderQrcode($params);
        return $result;
    }

    /**
     * 获取订单二维码图片路径
     * @return array
     */
    public function OrderEditImgUrl(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::OrderEditImgUrl($params);
        return $result;
    }
}