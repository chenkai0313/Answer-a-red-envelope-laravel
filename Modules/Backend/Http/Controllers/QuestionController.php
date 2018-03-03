<?php
/**
 * 商家问题答题记录
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class QuestionController extends Controller
{
    /**
     * 问题的添加
     * @return array
     */
    public function questionAdd(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionAdd($params);
        return $result;
    }

    /**
     * 问题的修改
     * @return array
     */
    public function questionEdit(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionEdit($params);
        return $result;
    }

    /**
     * 问题的详情
     * @return array
     */
    public function questionDetail(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionDetail($params);
        return $result;
    }

    /**
     * 问题的删除
     * @return array
     */
    public function questionDelete(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionDelete($params);
        return $result;
    }

    /**
     * 问题的列表
     * @return array
     */
    public function questionList(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionList($params);
        return $result;
    }

    /**
     * 获取一轮答题接口
     * @return array
     */
    public function questionAll(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionAll($params);
        return $result;
    }

    /**
     * 当前题目的详情（题目信息 商家信息 答题对错个数）
     * @return array
     */
    public function questionDetailNow(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionDetailNow($params);
        return $result;
    }

    /**
     * 对当前题目的点赞
     * @return array
     */
    public function questionGood(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionGood($params);
        return $result;
    }

    /**
     * 对当前题目的分享次数添加
     * @return array
     */
    public function questionLikeNum(Request $request)
    {
        $params = $request->all();
        $result = \QuestionService::questionLikeNum($params);
        return $result;
    }
}
