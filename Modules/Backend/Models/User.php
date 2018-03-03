<?php
/**
 * 用户表
 * Author: CK
 * Date: 2018/1/21
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{


    protected $table = 'user';

    protected $primaryKey = 'user_id';

    protected $fillable = array('openid', 'nick_name', 'sex', 'mobile', 'city', 'province', 'avatarUrl', 'user_account', 'ip', 'token');

    /**
     * 用户的添加
     * @return array
     */
    public static function userAdd($params)
    {
        $arr = ['openid', 'nick_name', 'sex', 'mobile', 'city', 'province', 'avatarUrl', 'user_account', 'ip'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return User::create($data);
    }

    /**
     * 用户的列表
     * @return array
     */
    public static function userList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = User::orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('nick_name', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->get()
            ->toArray();
        return $data;
    }

    public static function userListCount($params)
    {
        $data = User::orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('nick_name', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->get()
            ->toArray();
        return count($data);
    }

    /**
     * 用户的更新
     * @return array
     */
    public static function userEdit($params)
    {
        $arr = ['openid', 'nick_name', 'sex', 'mobile', 'city', 'province', 'avatarUrl', 'user_account', 'ip', 'token'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return User::where('openid', $params['openid'])->update($data);
    }

    /**
     * 用户的查询
     * @return array
     */

    public static function userDetail($params)
    {
        return User::where('openid', $params['openid'])->first();
    }

    /**
     * 用户的余额的添加
     * @return array
     */
    public static function userAccountAdd($params)
    {
        $pre = self::userDetail($params);
        $now['user_account'] = $pre['user_account'] + $params['account'];
        return User::where('openid', $params['openid'])->update($now);
    }

    /**
     * 用户的所有收到的红包
     * @return array
     */
    public static function userAllPackReciveData($params)
    {
        $res = static::userAllPackRecive($params);
        $result = static::userAllPackReciveList($params);
        $data['count'] = count($res);
        $arr = 0;
        for ($i = 0; $i < count($res); $i++) {
            $arr = bcadd($res[$i]['record_money'], $arr, 2);
        }
        $data['count_money'] = $arr;
        $data['list'] = $result;;
        return $data;
    }

    /**
     * 用户的所有收到的红包
     * @return array
     */
    public static function userAllPackRecive($params)
    {
        $data = PackRecord::select('record_money')
            ->where('openid', $params['openid'])
            ->where('status', 1)
            ->get();
        return $data;
    }

    /**
     * 用户的所有收到的红包列表
     * @return array
     */
    public static function userAllPackReciveList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = PackRecord::select('pack_record.record_money', 'pack_record.order_sn', 'pack_record.created_at', 'user.avatarUrl', 'user.nick_name')
            ->leftJoin('order', 'order.order_sn', '=', 'pack_record.order_sn')
            ->leftJoin('user', 'user.openid', '=', 'pack_record.openid')
            ->where('pack_record.openid', $params['openid'])
            ->where('pack_record.status', 1)
            ->skip($offset)
            ->take($params['limit'])
            ->orderBy('pack_record.created_at', 'desc')
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * 用户的所有收到的红包(手气最佳)
     * @return array
     */
    public static function userAllPackReciveBest($params)
    {
        $data = PackRecord::select('*')
            ->where('openid', $params['openid'])
            ->where('status', 1)
            ->where('best_man', 1)
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * 用户的所有发出去的红包
     * @return array
     */
    public static function userAllPackSend($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Order::select('order.*', 'user.avatarUrl', 'user.nick_name')
            ->leftJoin('user', 'user.openid', '=', 'order.openid')
            ->where('order.status', 1)
            ->where('order.openid', $params['openid'])
            ->skip($offset)
            ->take($params['limit'])
            ->orderBy('order.created_at', 'desc')
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * 用户的所有发出去的红包(count)
     * @return array
     */
    public static function userAllPackSendCount($params)
    {
        $data = Order::where('openid', $params['openid']) ->where('order.status', 1)->get();
        return count($data);
    }

    /**
     * 用户的过期退回的红包记录
     * @return array
     */
    public static function userAllPackRefund($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = PackRecord::select('pack_record.record_money', 'pack_record.order_sn', 'pack_record.created_at',
            'order.count_money', 'pack_record.openid')
            ->leftJoin('order', 'order.order_sn', '=', 'pack_record.order_sn')
            ->where('pack_record.openid', $params['openid'])
            ->where('pack_record.status', 0)
            ->skip($offset)
            ->take($params['limit'])
            ->orderBy('order.created_at', 'desc')
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * 用户的过期退回的红包记录(count)
     * @return array
     */
    public static function userAllPackRefundCount($params)
    {
        $data = PackRecord::select('record_money')
            ->where('openid', $params['openid'])
            ->where('status', 0)
            ->get();
        return count($data);
    }

    /**
     * 用户答对的题目接口
     * @return array
     */
    public static function userRightList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = PackRecord::select('pack_record.*', 'user.avatarUrl', 'user.nick_name', 'order.hb_options')
            ->where('pack_record.status', 1)
            ->leftJoin('order', 'order.order_sn', '=', 'pack_record.order_sn')
            ->leftJoin('user', 'user.openid', '=', 'pack_record.openid')
            ->where('pack_record.openid', $params['openid'])
            ->where('pack_record.is_right', 1)
            ->skip($offset)
            ->take($params['limit'])
            ->get();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['hb_options'] = unserialize($data[$i]['hb_options']);
        }
        return $data;
    }

    public static function userRightListCount($params)
    {
        $data = PackRecord::select('pack_record.*', 'user.avatarUrl', 'user.nick_name', 'order.hb_options')
            ->where('pack_record.status', 1)
            ->leftJoin('order', 'order.order_sn', '=', 'pack_record.order_sn')
            ->leftJoin('user', 'user.openid', '=', 'pack_record.openid')
            ->where('pack_record.openid', $params['openid'])
            ->where('pack_record.is_right', 1)
            ->get();
        return count($data);
    }

    /**
     * 用户答错的题目接口
     * @return array
     */
    public static function userErrorList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = PackRecord::select('pack_record.*', 'user.avatarUrl', 'user.nick_name', 'order.hb_options')
            ->where('pack_record.status', 1)
            ->leftJoin('order', 'order.order_sn', '=', 'pack_record.order_sn')
            ->leftJoin('user', 'user.openid', '=', 'pack_record.openid')
            ->where('pack_record.openid', $params['openid'])
            ->where('pack_record.is_right', 2)
            ->skip($offset)
            ->take($params['limit'])
            ->get();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['hb_options'] = unserialize($data[$i]['hb_options']);
        }
        return $data;
    }

    public static function userErrorListCount($params)
    {
        $data = PackRecord::select('pack_record.*', 'user.avatarUrl', 'user.nick_name', 'order.hb_options')
            ->where('pack_record.status', 1)
            ->leftJoin('order', 'order.order_sn', '=', 'pack_record.order_sn')
            ->leftJoin('user', 'user.openid', '=', 'pack_record.openid')
            ->where('pack_record.openid', $params['openid'])
            ->where('pack_record.is_right', 2)
            ->get();
        return count($data);
    }
}