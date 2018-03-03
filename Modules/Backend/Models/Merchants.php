<?php
/**
 * 商家表
 * Author: CK
 * Date: 2018/2/11
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Merchants extends Model
{
    protected $table = 'merchants';

    protected $primaryKey = 'merch_id';

    protected $fillable = array('merch_img', 'merch_name', 'merch_remark', 'merch_avator','merch_url');

    /**
     * 商家的添加
     * @return array
     */
    public static function merchAdd($params)
    {
        $arr = ['merch_img', 'merch_name', 'merch_remark', 'merch_avator','merch_url'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Merchants::create($data);
    }

    /**
     * 商家的修改
     * @return array
     */
    public static function merchEdit($params)
    {
        $arr = ['merch_img', 'merch_name', 'merch_remark', 'merch_avator','merch_url'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Merchants::where('merch_id', $params['merch_id'])->update($data);
    }

    /**
     * 商家的详情
     * @return array
     */
    public static function merchDetail($params)
    {
        return Merchants::where('merch_id', $params['merch_id'])->first();
    }

    /**
     * 商家的删除
     * @return array
     */
    public static function merchDelete($params)
    {
        return Merchants::where('merch_id', $params['merch_id'])->delete();
    }

    /**
     * 商家记录的列表
     * @return array
     */
    public static function merchList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Merchants::orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('merch_name', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->get()
            ->toArray();
        return $data;
    }

    public static function merchListCount($params)
    {
        $data = Merchants::where(function ($query) use ($params) {
            if (!is_null($params['keyword'])) {
                return $query->where('merch_name', 'like', '%' . $params['keyword'] . '%');
            }
        })
            ->get()
            ->toArray();
        return count($data);
    }

    /**
     * 带查询的所有商家
     * @return array
     */
    public static function merchSearchList($params)
    {
        $data = Merchants::select('merch_id', 'merch_name')->orderBy('created_at', 'desc')
            ->where(function ($query) use ($params) {
                if (!is_null($params['keyword'])) {
                    return $query->where('merch_name', 'like', '%' . $params['keyword'] . '%');
                }
            })
            ->get()
            ->toArray();
        return $data;
    }
}