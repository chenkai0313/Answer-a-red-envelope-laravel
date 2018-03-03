<?php
/**
 * 随机题目
 * Author: CK
 * Date: 2018/1/21
 */

namespace Modules\Backend\Services;


use Modules\Backend\Models\RandQuestion;

class RandQuestionService
{
    /**
     * 题目的添加
     * @return array
     */
    public function randQuestionAdd($params)
    {
        if (!isset($params['rand_options'])) {
            return ['code' => 90002, 'msg' => '题目不能为空'];
        }
        $params['rand_options'] = serialize($params['rand_options']);
        $res = RandQuestion::randQuestionAdd($params);
        if ($res) {
            return ['code' => 1, 'msg' => '添加成功'];
        }
        return ['code' => 90002, 'msg' => '添加失败'];
    }

    /**
     * 题目的修改
     * @return array
     */
    public function randQuestionEdit($params)
    {
        if (!isset($params['rand_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $params['rand_options'] = serialize($params['rand_options']);
        $res = RandQuestion::randQuestionEdit($params);
        if ($res) {
            return ['code' => 1, 'msg' => '修改成功'];
        }
        return ['code' => 90002, 'msg' => '修改失败'];
    }

    /**
     * 题目的详情
     * @return array
     */
    public function randQuestionDetail($params)
    {
        if (!isset($params['rand_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $res = RandQuestion::randQuestionDetail($params);
        $res['rand_options'] = unserialize($res['rand_options']);
        if ($res) {
            return ['code' => 1, 'data' => $res];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 题目的列表
     * @return array
     */
    public function randQuestionList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $data['list'] = RandQuestion::randQuestionList($params);
        $data['count'] = RandQuestion::randQuestionListCount($params);
        $data['limit'] = $params['limit'];
        $data['page'] = $params['page'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 题目的删除
     * @return array
     */
    public function randQuestionDelete($params)
    {
        if (!isset($params['rand_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $res = RandQuestion::randQuestionDelete($params);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

    /**
     * 随机取出题目
     * @return array
     */
    public function randQuestionOne()
    {
        $data = RandQuestion::randQuestionListAll();
        if ($data) {
            return ['code' => 1, 'data' => $data[array_rand($data, 1)]];
        } else {
            return ['code' => 90002, 'msg' => '获取失败'];
        }
    }
}
