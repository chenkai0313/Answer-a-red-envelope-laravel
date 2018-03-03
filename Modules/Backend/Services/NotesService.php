<?php
/**
 * 商家问题答题记录
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Services;

use Modules\Backend\Models\Notes;
use Modules\Backend\Models\Question;

class NotesService
{
    /**
     * 答题记录的配置
     * @return array
     */
    public function notesConfig($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 90002, 'msg' => '问题id必填'];
        }
        if (!isset($params['select_options'])) {
            return ['code' => 90002, 'msg' => '问题选项必填'];
        }
        $questionDetail=Question::questionDetail($params);
        $options=unserialize($questionDetail['qu_options']);
        $options= json_decode($options, true);
        if($params['select_options']==$options['rightAnswer']){
            $data['status']=1;
            $data['qu_id']=$params['qu_id'];
            self::notesAdd($data);
            return ['code'=>1,'msg'=>'恭喜您答对了'];
        }
        $data['status']=0;
        $data['qu_id']=$params['qu_id'];
        self::notesAdd($data);
        return ['code'=>1002,'msg'=>'很遗憾答错了'];
    }

    /**
     * 答题记录的添加
     * @return array
     */
    public function notesAdd($params)
    {
        $validator = \Validator::make($params, [
            'status' => 'required',
            'qu_id' => 'required',
        ], [
            'required' => ':attribute必填',
            'unique' => ':attribute已经存在',
        ], [
            'status' => '状态',
            'qu_id' => '题目id',
        ]);
        if (!$validator->passes()) {
            return ['code' => 90002, 'msg' => $validator->messages()->first()];
        }
        $data = Notes::notesAdd($params);
        if ($data) {
            $result['code'] = 1;
            $result['msg'] = "添加成功";
        } else {
            $result['code'] = 90002;
            $result['msg'] = "添加失败";
        }
        return $result;
    }

    /**
     * 答题的答对记录个数
     * @return array
     */
    public function notesRightCount($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 1, 'msg' => '问题id必填'];
        }
        return ['code' => 1, 'data' => Notes::notesRightCount($params)];
    }

    /**
     * 答题的答错记录个数
     * @return array
     */
    public function notesErrorCount($params)
    {
        if (!isset($params['qu_id'])) {
            return ['code' => 1, 'msg' => '问题id必填'];
        }
        return ['code' => 1, 'data' => Notes::notesErrorCount($params)];
    }
}
