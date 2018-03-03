<?php
/**
 * 广告
 * Author: CK
 * Date: 2018/1/21
 */

namespace Modules\Backend\Services;


use Modules\Backend\Models\Ad;

class AdService
{
    /**
     * 广告的添加
     * @return array
     */
    public function adAdd($params)
    {
        $validator = \Validator::make($params, [
            'ad_title' => 'required',
            'ad_linker' => 'required',
            'ad_img' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'ad_title' => '广告标题',
            'ad_linker' => '广告链接',
            'ad_img' => '广告图片路径',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $res = Ad::adAdd($params);
        if ($res) {
            return ['code' => 1, 'msg' => '添加成功'];
        }
        return ['code' => 90002, 'msg' => '添加失败'];
    }

    /**
     * 广告的修改
     * @return array
     */
    public function adEdit($params)
    {
        if (!isset($params['ad_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $validator = \Validator::make($params, [
            'ad_title' => 'required',
            'ad_linker' => 'required',
            'ad_img' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'ad_title' => '广告标题',
            'ad_linker' => '广告链接',
            'ad_img' => '广告图片路径',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $res = Ad::adEdit($params);
        if ($res) {
            return ['code' => 1, 'msg' => '修改成功'];
        }
        return ['code' => 90002, 'msg' => '修改失败'];
    }

    /**
     * 广告的详情
     * @return array
     */
    public function adDetail($params)
    {
        if (!isset($params['ad_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $res = Ad::adDetail($params);
        if ($res) {
            return ['code' => 1, 'data' => $res];
        }
        return ['code' => 90002, 'msg' => '查询失败'];
    }

    /**
     * 广告的列表
     * @return array
     */
    public function adList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $data['list'] = Ad::adList($params);
        $data['count'] = Ad::adListCount($params);
        $data['limit'] = $params['limit'];
        $data['page'] = $params['page'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 广告的删除
     * @return array
     */
    public function adDelete($params)
    {
        if (!isset($params['ad_id'])) {
            return ['code' => 90002, 'msg' => 'id必填'];
        }
        $res = Ad::adDelete($params);
        if ($res) {
            return ['code' => 1, 'msg' => '删除成功'];
        }
        return ['code' => 90002, 'msg' => '删除失败'];
    }

    /**
     * 取出排序最大的一个广告
     * @return array
     */
    public function adSortMaxOne()
    {
        return ['code'=>1 ,'data'=>Ad::adSortMaxOne()];
    }
}
