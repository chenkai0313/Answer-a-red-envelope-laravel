<?php
/**
 * 广告
 * Author: CK
 * Date: 2018/1/21
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{

    protected $table = 'ad';

    protected $primaryKey = 'ad_id';

    protected $fillable = array('ad_title', 'ad_linker', 'ad_img', 'sort');

    /**
     * 广告的添加
     * @return array
     */
    public static function adAdd($params)
    {
        $arr = ['ad_title', 'ad_linker', 'ad_img', 'sort'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Ad::create($data);
    }

    /**
     * 广告的修改
     * @return array
     */
    public static function adEdit($params)
    {
        $arr = ['ad_title', 'ad_linker', 'ad_img', 'sort'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Ad::where('ad_id', $params['ad_id'])->update($data);
    }

    /**
     * 广告的详情
     * @return array
     */
    public static function adDetail($params)
    {
        return Ad::where('ad_id', $params['ad_id'])->first();
    }

    /**
     * 广告的列表
     * @return array
     */
    public static function adList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Ad::select('*')
            ->where(function ($query) use ($params) {
                if (isset($params['keyword'])) {
                    return $query->where('ad_title', $params['keyword']);
                }
            })
            ->take($params['limit'])
            ->offset($offset)
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        return $data;
    }

    public static function adListCount($params)
    {
        $data = Ad::select('*')
            ->where(function ($query) use ($params) {
                if (!empty($params['keword'])) {
                    return $query->where('ad_title', $params['keyword']);
                }
            })
            ->orderBy('sort', 'desc')
            ->get()
            ->toArray();
        return count($data);
    }

    /**
     * 广告的删除
     * @return array
     */
    public static function adDelete($params)
    {
        return Ad::where('ad_id', $params['ad_id'])->delete();
    }


    /**
     * 取出排序最大的一个广告
     * @return array
     */
    public static function adSortMaxOne()
    {
        $data = Ad::orderBy('sort', 'desc')->limit(1)->get();
        return $data;
    }
}