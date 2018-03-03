<?php
/**
 * 商家出题表
 * Author: CK
 * Date: 2018/2/11
 */

namespace Modules\Backend\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';

    protected $primaryKey = 'qu_id';

    protected $fillable = array('merch_id', 'qu_options', 'status', 'like_num','parsing','share_num');

    /**
     * 问题的添加
     * @return array
     */
    public static function questionAdd($params)
    {
        $arr = ['merch_id', 'qu_options', 'status', 'like_num','parsing','share_num'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Question::create($data);
    }

    /**
     * 问题的修改
     * @return array
     */
    public static function questionEdit($params)
    {
        $arr = ['merch_id', 'qu_options', 'status', 'like_num','parsing','share_num'];
        $data = array();
        foreach ($arr as $v) {
            if (array_key_exists($v, $params)) {
                $data[$v] = $params[$v];
            }
        }
        return Question::where('qu_id', $params['qu_id'])->update($data);
    }

    /**
     * 问题的详情
     * @return array
     */
    public static function questionDetail($params)
    {
       $data=Question::where('qu_id', $params['qu_id'])
           ->select('question.*','merchants.merch_name')
           ->leftJoin('merchants', 'merchants.merch_id', 'question.merch_id')
           ->first();
       return $data;
    }

    /**
     * 问题的删除
     * @return array
     */
    public static function questionDelete($params)
    {
        return Question::where('qu_id', $params['qu_id'])->delete();
    }

    /**
     * 问题记录的列表
     * @return array
     */
    public static function questionList($params)
    {
        $offset = ($params['page'] - 1) * $params['limit'];
        $data = Question::orderBy('question.created_at', 'desc')
            ->leftJoin('merchants', 'merchants.merch_id', 'question.merch_id')
            ->where(function ($query) use ($params) {
                if (!is_null($params['status'])) {
                    return $query->where('question.status', 'like', '%' . $params['status'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['merch_name'])) {
                    return $query->where('merchants.merch_name', 'like', '%' . $params['merch_name'] . '%');
                }
            })
            ->skip($offset)
            ->take($params['limit'])
            ->get()
            ->toArray();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['qu_options'] = unserialize($data[$i]['qu_options']);
        }
        return $data;
    }

    public static function questionCount($params)
    {
        $data = Question::orderBy('question.created_at', 'desc')
            ->leftJoin('merchants', 'merchants.merch_id', 'question.merch_id')
            ->where(function ($query) use ($params) {
                if (!is_null($params['status'])) {
                    return $query->where('question.status', 'like', '%' . $params['status'] . '%');
                }
            })
            ->where(function ($query) use ($params) {
                if (!is_null($params['merch_name'])) {
                    return $query->where('merchants.merch_name', 'like', '%' . $params['merch_name'] . '%');
                }
            })
            ->get()
            ->toArray();
        return count($data);
    }
}