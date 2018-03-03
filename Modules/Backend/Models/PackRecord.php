<?php
/**
 * 红包记录
 * Author: CK
 * Date: 2017/12/28
 */


namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class PackRecord extends Model
{

    protected $table = 'pack_record';

    protected $primaryKey = 'record_id';

    protected $fillable = array('openid', 'order_sn', 'record_money', 'status','is_right','level');

    /**
     * 红包记录的添加
     * @return array
     */
    public static function packRecordAdd($params)
    {
        $arr = ['openid', 'order_sn', 'record_money', 'status','is_right','level'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return PackRecord::create($data);
    }


    /**
     * 红包记录的列表
     * @return array
     */
    public static function packRecordList($params)
    {
        $data = PackRecord::select('pack_record.*', 'user.avatarUrl', 'user.nick_name')
            ->leftJoin('user', 'user.openid', '=', 'pack_record.openid')
            ->where('pack_record.order_sn', $params['order_sn'])
            ->orderBy('pack_record.record_money','desc')
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * 红当前红包 用户是否有记录
     * @return array
     */
    public static function packRecordExist($params)
    {
        return PackRecord::where('order_sn', $params['order_sn'])->where('openid', $params['openid'])
            ->where('status', 1)->first();
    }


}