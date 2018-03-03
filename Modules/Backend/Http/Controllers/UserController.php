<?php
/**
 * 订单
 * Author: CK
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class UserController extends Controller
{

    /**
     * 用户的添加
     * @return array
     */
    public function userAdd(Request $request)
    {
        $params = $request->all();
        $params['ip'] = $request->getClientIp();
        $result = \UserService::userAdd($params);
        return $result;
    }

    /**
     * 获取openid
     * @return array
     */
    public function getOpenid(Request $request)
    {
        $params = $request->all();
        $result = \UserService::getOpenid($params);
        return $result;
    }

    /**
     *  获取 token
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function userDetail(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userDetail($params);
        return $result;
    }

    /**
     * 用户收到的红包记录
     * @return array
     */
    public function userAllPackRecive(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userAllPackRecive($params);
        return $result;
    }

    /**
     * 用户发出的红包记录
     * @return array
     */
    public function userAllPackSend(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userAllPackSend($params);
        return $result;
    }

    /**
     * 用户退回的红包记录
     * @return array
     */
    public function userAllPackRefund(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userAllPackRefund($params);
        return $result;
    }

    /**
     * 用户答题接口
     * @return array
     */
    public function userCheckQuestion(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userCheckQuestion($params);
        return $result;
    }

    /**
     * 用户答对的题目接口
     * @return array
     */
    public function userRightList(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userRightList($params);
        return $result;
    }

    /**
     * 用户答错的题目接口
     * @return array
     */
    public function userErrorList(Request $request)
    {
        $params = $request->all();
        $result = \UserService::userErrorList($params);
        return $result;
    }

    /**
     * 今日新增统计
     * @return array
     */
    public function todayCount(Request $request)
    {
        $params = $request->all();
        $result = \UserService::todayCount($params);
        return $result;
    }
}