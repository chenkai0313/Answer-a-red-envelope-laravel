<?php
/**
 * 红包记录
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;


use Modules\Backend\Models\Order;
use Modules\Backend\Models\PackRecord;
use Modules\Backend\Models\User;
use Illuminate\Support\Facades\DB;

class PackRecordService
{
    public function packeRecordConfig($params)
    {
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => '订单号不存在'];
        }
//        #利用当前时间走一遍红包过期接口
//        $res = new OrderService();
//        $res->orderOver();
//        #判断红包是否过期
//        $over = Order::orderDetail($params);
//        if ($over['is_over'] == 1) {
//            #如果过期 将剩余金额返回 并产生一条过期退回记录
//            return ['code' => 90002, 'msg' => '此红包已经过期'];
//        }
//        #判断红包个数
//        if ($over['num'] < 1) {
//            return ['code' => 90002, 'msg' => '此红包已经被抢完'];
//        }
        #判断是否抢过
        $exit = PackRecord::packRecordExist($params);
        if ($exit) {
            return ['code' => 90002, 'msg' => '您已经抢过此红包了'];
        }
        return $this->packRecordAdd($params);
    }


    /**
     * 红包记录的添加
     * @return array
     */
    public function packRecordAdd($params)
    {
        $validator = \Validator::make($params, [
            'openid' => 'required',
            'order_sn' => 'required',
            'record_money' => 'required',
        ], [
            'required' => ':attribute必填',
        ], [
            'openid' => 'openid',
            'order_sn' => '订单号',
            'record_money' => '红包金额',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = PackRecord::packRecordAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "添加成功";
            $result['data'] = $data;
            $params['account'] = $data['record_money'];
            $res1 = User::userAccountAdd($params);
            if ($res1 && $params['is_right']==1) {
                $order = Order::where('order_sn', $params['order_sn'])->first();
                Order::where('order_sn', $params['order_sn'])->update(array('num' => $order['num'] - 1));
            }
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 红包记录的列表
     * @return array
     */
    public function packRecordList($params)
    {
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => 'order_sn不能为空'];
        }
        $data['list'] = PackRecord::packRecordList($params);
        foreach ($data['list'] as &$v){
            $count=PackRecord::where('openid',$v['openid'])->where('status',1)
                ->where('is_right',1)->get()->count();
           $v['right_count']=$count;
        }
        $data['order_info'] = Order::select('*')
            ->where('order_sn', $params['order_sn'])->first();
        $data['order_info']['hb_options'] = unserialize($data['order_info']['hb_options']);
        $data['user_info'] = User::select('avatarUrl', 'nick_name')
            ->where('openid', $data['order_info']['openid'])->first();
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }
}
