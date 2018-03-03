<?php
/**
 * 配置
 * Author: CK
 * Date: 2018/2/11
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'config';

    protected $primaryKey = 'config_id';

    protected $fillable = array('config_num', 'config_switch');

    /**
     * 个数的添加
     * @return array
     */
    public static function configAdd($params)
    {
        $arr = ['config_num', 'config_switch'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Config::where('config_id', $params['config_id'])->update($data);
    }

    /**
     * 最新设置的个数
     * @return array
     */
    public static function configNewOne($params)
    {
        return Config::where('config_id', 1)->select('config_id', 'config_num', 'updated_at')->first();
    }

    /**
     * 开关详情
     * @return array
     */
    public static function configSwitchDetail($parmas)
    {
        return Config::where('config_id', 2)->select('config_id', 'config_switch', 'updated_at')->first();
    }
}