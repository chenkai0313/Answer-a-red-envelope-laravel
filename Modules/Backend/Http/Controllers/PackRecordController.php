<?php
/**
 * 红包记录
 * Author: CK
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class PackRecordController extends Controller
{
    /**
     * 记录的添加
     * @return array
     */
    public function packRecordAdd(Request $request)
    {
        $params = $request->all();
        $result = \PackRecordService::packeRecordConfig($params);
        return $result;
    }

    /**
     * 当前红包列表
     * @return array
     */
    public function packRecordList(Request $request)
    {
        $params = $request->all();
        $result = \PackRecordService::packRecordList($params);
        return $result;
    }

}