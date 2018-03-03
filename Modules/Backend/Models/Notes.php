<?php
/**
 * 商家答题记录表
 * Author: CK
 * Date: 2018/2/11
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = 'notes';

    protected $primaryKey = 'notes_id';

    protected $fillable = array('qu_id', 'status');

    /**
     * 答题的添加
     * @return array
     */
    public static function notesAdd($params)
    {
        $arr = ['qu_id', 'status'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Notes::create($data);
    }

    /**
     * 答题的答对记录个数
     * @return array
     */
    public static function notesRightCount($params)
    {
        return Notes::where('qu_id', $params['qu_id'])->where('status', 1)->count();
    }

    /**
     * 答题的答错记录个数
     * @return array
     */
    public static function notesErrorCount($params)
    {
        return Notes::where('qu_id', $params['qu_id'])->where('status', 0)->count();
    }
}