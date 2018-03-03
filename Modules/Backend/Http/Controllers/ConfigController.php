<?php
/**
 * 配置
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ConfigController extends Controller
{
    /**
     * 设置一轮答题个数的添加
     * @return array
     */
    public function configAdd(Request $request)
    {
        $params = $request->all();
        $result = \ConfigService::configAdd($params);
        return $result;
    }

    /**
     * 最新一轮答题个数
     * @return array
     */
    public function configNewOne(Request $request)
    {
        $params = $request->all();
        $result = \ConfigService::configNewOne($params);
        return $result;
    }

    /**
     * 计算器和答题红包开关切换
     * @return array
     */
    public function configSwitchAdd(Request $request)
    {
        $params = $request->all();
        $result = \ConfigService::configSwitchAdd($params);
        return $result;
    }

    /**
     * 开关详情
     * @return array
     */
    public function configSwitchDetail(Request $request)
    {
        $params = $request->all();
        $result = \ConfigService::configSwitchDetail($params);
        return $result;
    }

}
