<?php
/**
 * 随机题目
 * Author: CK
 * Date: 2018/1/21
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class RandQuestion extends Model
{

    protected $table = 'rand_question';

    protected $primaryKey = 'rand_id';

    protected $fillable = array('rand_options', 'status');

    /**
     * 题目的添加
     * @return array
     */
    public static function randQuestionAdd($params)
    {
        $arr = ['rand_options', 'status'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return RandQuestion::create($data);
    }

    /**
     * 题目的修改
     * @return array
     */
    public static function randQuestionEdit($params)
    {
        $arr = ['rand_options', 'status'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return RandQuestion::where('rand_id', $params['rand_id'])->update($data);
    }

    /**
     * 题目的详情
     * @return array
     */
    public static function randQuestionDetail($params)
    {
        return RandQuestion::where('rand_id', $params['rand_id'])->first();
    }

    /**
     * 题目的列表
     * @return array
     */
    public static function randQuestionList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = RandQuestion::select('*')
            ->where(function ($query) use ($params) {
                if (isset($params['keyword'])) {
                    return $query->where('status', $params['keyword']);
                }
            })
            ->take($params['limit'])
            ->offset($offset)
            ->get()
            ->toArray();
       for ($i=0;$i<count($data);$i++){
           $data[$i]['rand_options']=unserialize( $data[$i]['rand_options']);
       }
        return $data;
    }

    public static function randQuestionListCount($params)
    {
        $data = RandQuestion::select('*')
            ->where(function ($query) use ($params) {
                if (!empty($params['keword'])) {
                    return $query->where('status', $params['keyword']);
                }
            })
            ->get()
            ->toArray();
        return count($data);
    }

    /**
     * 题目的删除
     * @return array
     */
    public static function randQuestionDelete($params)
    {
        return RandQuestion::where('rand_id', $params['rand_id'])->delete();
    }


    /**
     * 所有可用题目
     * @return array
     */
    public static function randQuestionListAll()
    {
        $data= RandQuestion::where('status', 1)->get()->toArray();
       for ($i=0;$i<count($data);$i++){
           $data[$i]['rand_options']=unserialize( $data[$i]['rand_options']);
       }

        return $data;
    }
}