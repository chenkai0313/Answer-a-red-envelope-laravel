<?php
/**
 * 提现记录
 * Author: CK
 * Date: 2017/12/26
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Df extends Model
{


    protected $table = 'df';

    protected $primaryKey = 'df_id';

    protected $fillable = array('openid', 'df_money');

    /**
     * 提现记录的添加
     * @return array
     */
    public static function dfAdd($params)
    {
        $arr = ['openid', 'df_money'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Df::create($data);
    }

    /**
     * 提现记录的列表
     * @return array
     */
    public static function dfList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Df::leftJoin('user', 'user.openid', '=', 'df.openid')
            ->orderBy('df.created_at', 'desc')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('user.openid', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->get()
            ->toArray();
        return $data;
    }

    public static function dfListCount($params)
    {
        $data = Df::leftJoin('user', 'user.openid', '=', 'df.openid')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('user.openid', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->orderBy('df.created_at', 'desc')
            ->get()
            ->toArray();
        return count($data);
    }


}