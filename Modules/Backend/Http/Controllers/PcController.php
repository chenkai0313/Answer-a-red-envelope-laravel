<?php
/**
 * pc
 * Author: CK
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class PcController extends Controller
{
    /**
     * 用户列表
     * @return array
     */
    public function userList(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userList($params);
        return $result;
    }

    /**
     * 体现列表
     * @return array
     */
    public function dfList(Request $request)
    {
        $params = $request->all();
        $result = \DfService::dfList($params);
        return $result;
    }

    /**
     * 订单列表
     * @return array
     */
    public function orderList(Request $request)
    {
        $params = $request->all();
        $result = \OrderService::orderList($params);
        return $result;
    }
}