<?php
/**
 * 订单表
 * Author: CK
 * Date: 2018/1/20
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Backend\Http\Controllers\PayController;

class Order extends Model
{

    protected $table = 'order';

    protected $primaryKey = 'order_id';

    protected $fillable = array('openid', 'order_sn', 'num', 'remark', 'count_money', 'status', 'pre_num', 'pre_money', 'hb_options', 'img_url', 'img_name');

    /**
     * 订单的添加
     * @return array
     */
    public static function orderAdd($params)
    {
        $arr = ['openid', 'order_sn', 'num', 'remark', 'count_money', 'status', 'pre_num', 'pre_money', 'hb_options', 'img_name'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Order::create($data);
    }

    /**
     * 红包的二维码图片路径
     * @return array
     */
    public static function OrderEditImgUrl($params)
    {
        $data['img_url']=$params['img_url'];
        return Order::where('order_sn', $params['order_sn'])->update($data);
    }

    /**
     * 红包的修改
     * @return array
     */
    public static function orderEdit($params)
    {
        $res = new PayController();
        $params['img_name'] = '' . $res->getQrcodeConfig2($params) . '';
        $arr = ['openid', 'order_sn', 'num', 'remark', 'count_money', 'status', 'pre_num', 'pre_money', 'hb_options', 'img_url', 'img_name'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Order::where('order_sn', $params['order_sn'])->update($data);
    }


    /**
     * 红包个数的修改
     * @return array
     */
    public static function orderNumEdit($params)
    {
        return Order::where('order_sn', $params['order_sn'])->update(array('num' => $params['num']));
    }


    /**
     * 当前用户的订单列表
     * @return array
     */
    public static function orderUserList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Order::orderBy('order.created_at', 'desc')
            ->leftJoin('user', 'user.openid', 'order.openid')
            ->orWhere('order.order_sn', 'like', '%' . $params['order_sn'] . '%')
            ->orWhere('user.nick_name', 'like', '%' . $params['nick_name'] . '%')
            ->select('order.*', 'user.nick_name')
            ->skip($offset)
            ->take($params['limit'])
            ->get()
            ->toArray();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['hb_options'] = unserialize($data[$i]['hb_options']);
        }
        return $data;
    }

    public static function orderUserListCount($params)
    {
        $data = Order::orderBy('order.created_at', 'desc')
            ->leftJoin('user', 'user.openid', 'order.openid')
            ->select('order.*', 'user.nick_name')
            ->get()
            ->toArray();
        return count($data);
    }

    /**
     * 订单的详情
     * @return array
     */

    public static function orderDetail($params)
    {
        return Order::where('order_sn', $params['order_sn'])->first();
    }


}