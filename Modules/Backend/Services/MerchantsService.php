<?php
/**
 * 商家
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;


use Modules\Backend\Models\Merchants;
use Modules\Backend\Models\Question;

class MerchantsService
{
    /**
     * 商家的添加
     * @return array
     */
    public function merchAdd($params)
    {
        $validator = \Validator::make($params, [
            'merch_name' => 'required',
            'merch_avator' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'merch_name' => '商家名称',
            'merch_avator' => '商家头像',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Merchants::merchAdd($params);
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
     * 商家的修改
     * @return array
     */
    public function merchEdit($params)
    {
        $validator = \Validator::make($params, [
            'merch_name' => 'required',
            'merch_avator' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'merch_name' => '商家名称',
            'merch_avator' => '商家头像',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Merchants::merchEdit($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "修改成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "修改失败";
        }
        return $result;
    }

    /**
     * 商家的详情
     * @return array
     */
    public function merchDetail($params)
    {
        if (!isset($params['merch_id'])) {
            return ['code' => 1, 'msg' => '商家id必填'];
        }
        $data = Merchants::merchDetail($params);
        if ($data) {
            $data['qu_options'] = unserialize($data['qu_options']);
            return ['code' => 1, 'data' => $data];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 商家的删除
     * @return array
     */
    public function merchDelete($params)
    {
        if (!isset($params['merch_id'])) {
            return ['code' => 1, 'msg' => '商家id必填'];
        }
        $res = Merchants::merchDelete($params);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

    /**
     * 商家的列表
     * @return array
     */
    public function merchList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : null;
        $data['list'] = Merchants::merchList($params);
        $data['count'] = Merchants::merchListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 所有商家
     * @return array
     */
    public function merchSearchList($params)
    {
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : null;
        $data['list'] = Merchants::merchSearchList($params);
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 商家主页信息
     * @return array
     */
    public function merchPortal($params)
    {
        if (!isset($params['merch_id'])) {
            return ['code' => 1, 'msg' => '商家id必填'];
        }
        $data['merch_detail'] = Merchants::merchDetail($params);
        $data['merch_question_list'] = Question::where('merch_id', $params['merch_id'])
            ->where('status', 1)
            ->get();
        foreach ($data['merch_question_list'] as $v) {
            $v['qu_options'] = json_decode(unserialize($v['qu_options']), true);
        }
        return ['code' => 1, 'data' => $data];
    }
}
