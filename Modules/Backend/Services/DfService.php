<?php
/**
 * 提现
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;


use Modules\Backend\Models\Df;


class DfService
{
    /**
     * 支出收入的添加
     * @return array
     */
    public function dfAdd($params)
    {
        $validator = \Validator::make($params, [
            'openid' => 'required',
            'df_money' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'openid' => 'openid',
            'df_money' => '提现金额',

        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Df::dfAdd($params);
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
     * 提现记录的列表
     * @return array
     */
    public function dfList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : null;
        $data['list'] = Df::dfList($params);
        $data['count'] = Df::dfListCount($params);
        $data['page']=$params['page'];
        $data['limit']=$params['limit'];
        return ['code' => 1, 'data' => $data];
    }


}
