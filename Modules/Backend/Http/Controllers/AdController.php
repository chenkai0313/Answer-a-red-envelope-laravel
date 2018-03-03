<?php
/**
 * 广告
 * Author: CK
 * Date: 2018/1/21
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class AdController extends Controller
{
    /**
     * 广告的添加
     * @return array
     */
    public function adAdd(Request $request)
    {
        $params = $request->all();
        $result = \AdService::adAdd($params);
        return $result;
    }

    /**
     * 广告的修改
     * @return array
     */
    public function adEdit(Request $request)
    {
        $params = $request->all();
        $result = \AdService::adEdit($params);
        return $result;
    }

    /**
     * 广告的列表
     * @return array
     */
    public function adList(Request $request)
    {
        $params = $request->all();
        $result = \AdService::adList($params);
        return $result;
    }

    /**
     * 广告的删除
     * @return array
     */
    public function adDelete(Request $request)
    {
        $params = $request->all();
        $result = \AdService::adDelete($params);
        return $result;
    }

    /**
     * 广告的详情
     * @return array
     */
    public function adDetail(Request $request)
    {
        $params = $request->all();
        $result = \AdService::adDetail($params);
        return $result;
    }

    /**
     * 取出排序最大的一个广告
     * @return array
     */
    public function adSortMaxOne(Request $request)
    {
        $params = $request->all();
        $result = \AdService::adSortMaxOne($params);
        return $result;
    }
}