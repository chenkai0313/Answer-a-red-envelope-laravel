<?php
/**
 * 用户
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\User;
use Modules\Backend\Models\Order;
use Modules\Backend\Models\PackRecord;

class UserService
{
    /**
     * 用户答题接口
     * @return array
     */
    public function userCheckQuestion($params)
    {
        if (!isset($params['option'])) {
            return ['code' => 90003, 'msg' => '答案选项必填'];
        }
        if (!isset($params['order_sn'])) {
            return ['code' => 90003, 'msg' => '答案选项必填'];
        }
        try {
            #利用当前时间走一遍红包过期接口
            #如果过期 将剩余金额返回 并产生一条过期退回记录
            $res = new OrderService();
            $res->orderOver();
            $over = Order::orderDetail($params);
            $over['hb_options'] = unserialize($over['hb_options']);
            $over['hb_options'] = json_decode($over['hb_options'], true);
            if ($over['is_over'] == 1) {
                return ['code' => 90001, 'msg' => '此红包已经过期'];
            }
            if ($over['status'] == 0) {
                return ['code' => 90005, 'msg' => '红包不合法'];
            }
            #判断红包个数
            if ($over['num'] < 1) {
                return ['code' => 90003, 'msg' => '题目已经被答完啦'];
            }
            #判断是否抢过
            $exit = PackRecord::packRecordExist($params);
            if ($exit) {
                return ['code' => 90004, 'msg' => '您已经回答过此题目了'];
            }
            #判断答题
            $pack = new PackRecordService();
            if ($over['hb_options']['rightAnswer'] == $params['option']) {
                $params['status'] = 1;
                $params['is_right'] = 1;
                $params['record_money'] = $over['pre_money'];
                $pack->packRecordAdd($params);
                return ['code' => 1, 'msg' => '恭喜您答对了,红包已放到您的余额'];
            } else {
                $params['status'] = 1;
                $params['is_right'] = 2;
                $params['record_money'] = 0;
                $pack->packRecordAdd($params);
                return ['code' => 0, 'msg' => '啊呀啊呀 不好意思答错咯'];
            }
        } catch (\Exception $e) {
            return ['code' => 90002, 'msg' => '请求超时,稍后在试'];
        }

    }

    /**
     * 用户的添加
     * @return array
     */

    public function userAdd($params)
    {
        $validator = \Validator::make($params, [
            'openid' => 'required|unique:user',
//            'mobile' => 'required|min:11|integer',
        ], [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
            'min' => ':attribute最少为11位',
        ], [
            'openid' => 'openid',
//            'mobile' => '手机号',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = User::userAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }


    /**
     * 用户的编辑
     * @return array
     */

    public function userEdit($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        $validator = \Validator::make($params, [
            'openid' => 'required',
            'mobile' => 'required|min:11|integer',
        ], [
            'integer' => ':attribute必须为整数',
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
            'min' => ':attribute最少为11位',
        ], [
            'openid' => 'openid',
            'mobile' => '手机号',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = User::userEdit($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "编辑成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "编辑失败";
        }
        return $result;
    }

    /**
     * 用户的详情
     * @return array
     */

    public function userDetail($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        //todo session 无法获取，暂时存数据库
        $session['token'] = getRandomkeys();
        $session['openid'] = $params['openid'];
        User::userEdit($session);
        $data = User::userDetail($params);
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }

    /**
     * 用户的列表
     * @return array
     */

    public function userList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : null;
        $data['list'] = User::userList($params);
        $data['count'] = User::userListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 用户的所有收到的红包
     * @return array
     */

    public function userAllPackRecive($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        #收到红包
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $data = User::userAllPackReciveData($params);
        $data['limit'] = $params['limit'];
        $data['page'] = $params['page'];
        if ($data) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }


    /**
     * 用户的所有发出去的红包
     * @return array
     */

    public function userAllPackSend($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        #所有发出的红包
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $data['count'] = User::userAllPackSendCount($params);
        $data['limit'] = $params['limit'];
        $data['page'] = $params['page'];
        $send = User::userAllPackSend($params);
        $arr = 0;
        for ($i = 0; $i < count($send); $i++) {
            $arr = bcadd($send[$i]['count_money'], $arr, 2);
        }
        $data['count_money'] = $arr;
        $data['list'] = $send;
        $result['code'] = 1;
        $result['data'] = $data;
        return $result;
    }

    /**
     * 用户的过期退回的红包记录
     * @return array
     */

    public function userAllPackRefund($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $data['limit'] = $params['limit'];
        $data['page'] = $params['page'];
        $data['count'] = User::userAllPackRefundCount($params);
        $data['list'] = User::userAllPackRefund($params);
        if ($data['list']) {
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }

    /**
     * 用户答对的题目接口
     * @return array
     */

    public function userRightList($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $data['list'] = User::userRightList($params);
        $data['count'] = User::userRightListCount($params);
        $data['limit'] = $params['limit'];
        $data['page'] = $params['page'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 用户答错的题目接口
     * @return array
     */

    public function userErrorList($params)
    {
        if (!isset($params['openid'])) {
            return ['code' => 90002, 'msg' => 'openid不能为空'];
        }
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 20;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $data['list'] = User::userErrorList($params);
        $data['count'] = User::userErrorListCount($params);
        $data['limit'] = $params['limit'];
        $data['page'] = $params['page'];
        return ['code' => 1, 'data' => $data];
    }


    /**
     * 今日新增统计
     * @return array
     */
    public function todayCount()
    {
        $time = date('Y-m-d', time());
        #新增用户数
        $userTodayCount = User::where('created_at', 'like', '%' . $time . '%')->count();
        $data['user_today_count'] = $userTodayCount;
        #新发红包数
        $OrderTodayCount = Order::where('created_at', 'like', '%' . $time . '%')->where('status', 1)->count();
        $data['order_today_count'] = $OrderTodayCount;
        #今日答对题目的数量
        $packRightCount = PackRecord::where('created_at', 'like', '%' . $time . '%')->where('status', 1)
            ->where('is_right', 1)->count();
        $data['pack_today_count']=$packRightCount;
        return ['code'=>1,'data'=>$data];
    }


    /**
     * 获取用户的openid
     * @return array
     */

    public function getOpenid($params)
    {
        if (!isset($params['code'])) {
            return ['code' => 90002, 'msg' => 'code不能为空'];
        }
        $appid = 'wxf592d3f69391cdbf';
        $secret = '45b54e4ca2e9090f1f3e0318c24c4417';
        $grant_type = 'authorization_code';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=';
        $code = $params['code'];
        $sessUrl = '' . $url . '' . $appid . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=' . $grant_type . '';
        $getSession = json_decode($this->requestGet($sessUrl), true);
        return ['code' => 1, 'data' => $getSession];
    }


    function requestGet($url)
    {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }

    function requestPost($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}
