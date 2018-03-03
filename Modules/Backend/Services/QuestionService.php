<?php
/**
 * 商家出题
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Config;
use Modules\Backend\Models\Merchants;
use Modules\Backend\Models\Notes;
use Modules\Backend\Models\Question;

class QuestionService
{
    /**
     * 问题的添加
     * @return array
     */
    public function questionAdd($params)
    {
//
        $validator = \Validator::make($params, [
            'merch_id' => 'required',
            'qu_options' => 'required',
            'status' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'merch_id' => '商家id',
            'qu_options' => '问题参数',
            'status' => '状态'
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $params['qu_options'] = serialize($params['qu_options']);
        $data = Question::questionAdd($params);
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
     * 问题的修改
     * @return array
     */
    public function questionEdit($params)
    {
        $validator = \Validator::make($params, [
            'merch_id' => 'required',
            'qu_options' => 'required',
            'status' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'merch_id' => '商家id',
            'qu_options' => '问题参数',
            'status' => '状态'
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $params['qu_options'] = serialize($params['qu_options']);
        $data = Question::questionEdit($params);
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
     * 问题的详情
     * @return array
     */
    public function questionDetail($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 1, 'msg' => '问题id必填'];
        }
        $data = Question::questionDetail($params);
        if ($data) {
            $data['qu_options'] = unserialize($data['qu_options']);
            return ['code' => 1, 'data' => $data];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 问题的删除
     * @return array
     */
    public function questionDelete($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 1, 'msg' => '问题id必填'];
        }
        $res = Question::questionDelete($params);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

    /**
     * 问题的列表
     * @return array
     */
    public function questionList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['status'] = isset($params['status']) ? $params['status'] : null;
        $params['merch_name'] = isset($params['merch_name']) ? $params['merch_name'] : null;
        $data['list'] = Question::questionList($params);
        $data['count'] = Question::questionCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 获取一轮答题接口
     * @return array
     */
    public function questionAll($params)
    {
        $config_num = Config::configNewOne($params);
        $num = $config_num['config_num'];
        $data = self::question();
        if (count($data) < $num) {
            $num = count($data);
        }
        $key = array_rand($data, $num);
        for ($i = 0; $i < count($key); $i++) {
            $res[$i] = $data[$key[$i]];
        }
        shuffle($res);
        return ['code' => 1, 'data' => $res];
    }

    public function question()
    {
        $data = Question::select('qu_id')->get()->toArray();
        $res = [];
        for ($i = 0; $i < count($data); $i++) {
            $res[$i] = $data[$i]['qu_id'];
        }
        return $res;
    }

    /**
     * 当前题目的详情（题目信息 商家信息 答题对错个数）
     * @return array
     */
    public function questionDetailNow($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 90002, 'msg' => '问题id必填'];
        }
        $ques = Question::questionDetail($params);
        if ($ques) {
            $ques['qu_options'] = unserialize($ques['qu_options']);
            $data['question_detail'] = $ques;
        } else {
            $data['question_detail'] = '';
        }
        $params['merch_id'] = $data['question_detail']['merch_id'];
        $data['merch_detail'] = Merchants::merchDetail($params);
        $data['right_count'] = Notes::notesRightCount($params);
        $data['error_count'] = Notes::notesErrorCount($params);
        $data['like_num'] = $data['question_detail']['like_num'];
        $data['share_num'] = $data['question_detail']['share_num'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 对当前题目的点赞
     * @return array
     */
    public function questionGood($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 90002, 'msg' => '问题id必填'];
        }
        $question_detail = Question::questionDetail($params);
        $data['qu_id'] = $params['qu_id'];
        $data['like_num'] = $question_detail['like_num'] + 1;
        Question::questionEdit($data);
        return ['code' => 1, 'msg' => '点赞成功'];
    }

    /**
     * 对当前题目的分享次数添加
     * @return array
     */
    public function questionLikeNum($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 90002, 'msg' => '问题id必填'];
        }
        $question_detail = Question::questionDetail($params);
        $data['qu_id'] = $params['qu_id'];
        $data['share_num'] = $question_detail['share_num'] + 1;
        Question::questionEdit($data);
        return ['code' => 1, 'msg' => '点赞成功'];
    }
}
