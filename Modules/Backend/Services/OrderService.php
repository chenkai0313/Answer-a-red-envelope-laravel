<?php
/**
 * 订单
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Order;
use Modules\Backend\Models\PackRecord;
use Modules\Backend\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * 订单的添加
     * @return array
     */
    public function orderAdd($params)
    {
        $params['order_sn'] = 'HB' . get_sn();
        $params['remark'] = isset($params['remark']) ? $params['remark'] : '这题都答不上来，删除好友啦！';
        $validator = \Validator::make($params, [
            'openid' => 'required',
            'order_sn' => 'required|unique:order',
            'status' => 'required',
            'pre_money' => 'required',
            'num' => 'required',
            'hb_options' => 'required'
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'openid' => 'openid',
            'order_sn' => '订单号',
            'status' => '状态',
            'num' => '红包个数',
            'pre_money' => '金额',
            'hb_options' => '红包参数'
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $params['pre_num'] = $params['num'];
        $params['count_money'] = $params['pre_money'] * $params['num'];
        $params['hb_options'] = serialize($params['hb_options']);
        $data = Order::orderAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "添加成功";
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    public function orderEdit($params)
    {
        if (!isset($params['path'])) {
            return ['code' => 90002, 'msg' => 'path不能为空'];
        }
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => 'order_sn不能为空'];
        }
        $validator = \Validator::make($params, [
            'openid' => 'required',
            'order_sn' => 'required',
            'status' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'openid' => 'openid',
            'order_sn' => '订单号',
            'status' => '状态',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Order::orderEdit($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "修改成功";
            $result['data'] = Order::orderDetail($params);
        } else {
            $result['code'] = 90002;
            $result['msg'] = "修改失败";
        }
        return $result;
    }


    /**
     * 订单详情
     * @return array
     */
    public static function orderDetail($params)
    {
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => 'order_sn不能为空'];
        }
        $data = Order::orderDetail($params);
        if ($data) {
            $data['hb_options'] = unserialize($data['hb_options']);
            $result['code'] = 1;
            $result['data'] = $data;
        } else {
            $result['code'] = 90002;
            $result['msg'] = "查询失败";
        }
        return $result;
    }

    /**
     * 订单列表
     * @return array
     */
    public function orderList($params)
    {
        $params['limit'] = isset($params['limit']) ? $params['limit'] : 10;
        $params['page'] = isset($params['page']) ? $params['page'] : 1;
        $params['keyword'] = isset($params['keyword']) ? $params['keyword'] : null;
        $data['list'] = Order::orderUserList($params);
        $data['count'] = Order::orderUserListCount($params);
        $data['page'] = $params['page'];
        $data['limit'] = $params['limit'];
        return ['code' => 1, 'data' => $data];
    }

    /**
     * 红包过期
     * @return array
     * //todo  事务逻辑 可以修改
     */
    public function orderOver()
    {
        $data = self::orderOverData();
        foreach ($data as $v) {
            $v['time'] = strtotime($v['created_at']);
            if ((time() - $v['time']) > 86400) {
                #将订单中设置为过期
                $res = Order::where('order_id', $v['order_id'])->update(array('is_over' => 1));
                if ($res) {
                    #如果剩余过期红包个数大于0 剩下的钱返回
                    if ($v['num'] > 0) {
                        $balance = $v['num'] * $v['pre_money'];
                        $user = User::where('openid', $v['openid'])->first();
                        #退回用户余额
                        $res1 = User::where('openid', $v['openid'])->update(array('user_account' => $user['user_account'] + $balance));
                        if ($res1) {
                            #当前订单的个数设置为0
                            $res2 = Order::where('order_id', $v['order_id'])->update(array('num' => 0));
                            #生成一条过期记录
                            if ($res2) {
                                $resdata = [
                                    'openid' => $v['openid'],
                                    'order_sn' => $v['order_sn'],
                                    'record_money' => $balance,
                                    'status' => 0,
                                    'is_right' => 0
                                ];
                                PackRecord::packRecordAdd($resdata);
                            }
                        }
                    }
                }
            }
        }
        return ['code' => 1, 'msg' => '数据处理成功'];
    }

#查找成功付款且未过期的数据
    public function orderOverData()
    {
        return Order::where('is_over', '0')->where('status', 1)->get();
    }

    /**
     * 获取订单二维码
     * @return array
     */
    public function orderQrcode($params)
    {
//        if(!isset($params['img_url'])){
//            return null;
//        }
        header("content-type: image/png");
        $params['img_url'] = storage_path() . '/app/wx/' . $params['img_url'];
        $im = $this->radius_img($params['img_url']);
        imagepng($im);
    }


    function radius_img($imgpath, $radius = 215)
    {
        $ext = pathinfo($imgpath);
        $src_img = null;
        switch ($ext['extension']) {
            case 'jpg':
                $src_img = imagecreatefromjpeg($imgpath);
                break;
            case 'png':
                $src_img = imagecreatefrompng($imgpath);
                break;
        }
        $wh = getimagesize($imgpath);
        $w = $wh[0];
        $h = $wh[1];
        // $radius = $radius == 0 ? (min($w, $h) / 2) : $radius;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r = $radius; //圆 角半径
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (($x >= $radius && $x <= ($w - $radius)) || ($y >= $radius && $y <= ($h - $radius))) {
                    //不在四角的范围内,直接画
                    imagesetpixel($img, $x, $y, $rgbColor);
                } else {
                    //在四角的范围内选择画
                    //上左
                    $y_x = $r; //圆心X坐标
                    $y_y = $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }
                    //上右
                    $y_x = $w - $r; //圆心X坐标
                    $y_y = $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }
                    //下左
                    $y_x = $r; //圆心X坐标
                    $y_y = $h - $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }
                    //下右
                    $y_x = $w - $r; //圆心X坐标
                    $y_y = $h - $r; //圆心Y坐标
                    if (((($x - $y_x) * ($x - $y_x) + ($y - $y_y) * ($y - $y_y)) <= ($r * $r))) {
                        imagesetpixel($img, $x, $y, $rgbColor);
                    }
                }
            }
        }
        return $img;
    }


    /**
     * 红包的二维码图片路径
     * @return array
     */
    public function OrderEditImgUrl($params)
    {
        if (!isset($params['order_sn'])) {
            return ['code' => 90002, 'msg' => '订单号不能为空'];
        }
        if (!isset($params['img_url'])) {
            return ['code' => 90002, 'msg' => '订单号不能为空'];
        }
        $orderExist = Order::orderDetail($params);
        if (!empty($orderExist['img_url'])) {
            return $orderExist;
        } else {
            $data = Order::OrderEditImgUrl($params);
            if ($data) {
                $result['code'] = 1;
                $result['msg'] = "修改成功";
                $result['data'] = Order::orderDetail($params);
            } else {
                $result['code'] = 90002;
                $result['msg'] = "修改失败";
            }
            return $result;
        }

    }

}
