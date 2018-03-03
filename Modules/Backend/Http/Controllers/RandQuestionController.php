<?php
/**
 * 后台随机题目
 * Author: CK
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class RandQuestionController extends Controller
{
    /**
     * 题目的添加
     * @return array
     */
    public function randQuestionAdd(Request $request)
    {
        $params = $request->all();
        $result = \RandQuestionService::randQuestionAdd($params);
        return $result;
    }

    /**
     * 题目的修改
     * @return array
     */
    public function randQuestionEdit(Request $request)
    {
        $params = $request->all();
        $result = \RandQuestionService::randQuestionEdit($params);
        return $result;
    }

    /**
     * 题目的列表
     * @return array
     */
    public function randQuestionList(Request $request)
    {
        $params = $request->all();
        $result = \RandQuestionService::randQuestionList($params);
        return $result;
    }

    /**
     * 题目的删除
     * @return array
     */
    public function randQuestionDelete(Request $request)
    {
        $params = $request->all();
        $result = \RandQuestionService::randQuestionDelete($params);
        return $result;
    }

    /**
     * 题目的详情
     * @return array
     */
    public function randQuestionDetail(Request $request)
    {
        $params = $request->all();
        $result = \RandQuestionService::randQuestionDetail($params);
        return $result;
    }

    /**
     * 题目的获取随机题目
     * @return array
     */
    public function randQuestionOne(Request $request)
    {
        $params = $request->all();
        $result = \RandQuestionService::randQuestionOne($params);
        return $result;
    }
}