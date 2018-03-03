<?php
/**
 * 设置一轮答题个数
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Config;

class ConfigService
{
    /**
     * 设置一轮答题个数的添加
     * @return array
     */
    public function configAdd($params)
    {
        $validator = \Validator::make($params, [
            'config_num' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'config_num' => '个数',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $params['config_id'] = 1;
        $data = Config::configAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "添加成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 最新一轮答题个数
     * @return array
     */
    public function configNewOne($params)
    {
        return ['code' => 1, 'data' => Config::configNewOne($params)];
    }

    /**
     * 计算器和答题红包开关切换
     * @return array
     */
    public function configSwitchAdd($params)
    {
        $validator = \Validator::make($params, [
            'config_switch' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'config_switch' => '开关设置',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $params['config_id'] = 2;
        $data = Config::configAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "添加成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 开关详情
     * @return array
     */
    public function configSwitchDetail($params)
    {
        return ['code' => 1, 'data' => Config::configSwitchDetail($params)];
    }
}
