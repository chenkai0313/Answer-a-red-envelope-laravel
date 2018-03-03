<?php
/**
 * 商家
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class MerchantsController extends Controller
{
    /**
     * 商家的添加
     * @return array
     */
    public function merchAdd(Request $request)
    {
        $params = $request->all();
        $result = \MerchantsService::merchAdd($params);
        return $result;
    }


    /**
     * 商家的修改
     * @return array
     */
    public function merchEdit(Request $request)
    {
        $params = $request->all();
        $result = \MerchantsService::merchEdit($params);
        return $result;
    }

    /**
     * 商家的详情
     * @return array
     */
    public function merchDetail(Request $request)
    {
        $params = $request->all();
        $result = \MerchantsService::merchDetail($params);
        return $result;
    }

    /**
     * 商家的删除
     * @return array
     */
    public function merchDelete(Request $request)
    {
        $params = $request->all();
        $result = \MerchantsService::merchDelete($params);
        return $result;
    }

    /**
     * 商家的列表
     * @return array
     */
    public function merchList(Request $request)
    {
        $params = $request->all();
        $result = \MerchantsService::merchList($params);
        return $result;
    }

    /**
     * 所有商家
     * @return array
     */
    public function merchSearchList(Request $request)
    {
        $params = $request->all();
        $result = \MerchantsService::merchSearchList($params);
        return $result;
    }

    /**
     * 商家主页信息
     * @return array
     */
    public function merchPortal(Request $request)
    {
        $params = $request->all();
        $result = \MerchantsService::merchPortal($params);
        return $result;
    }
}
