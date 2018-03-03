<?php
/**
 * 商家问题答题记录
 * Author: ck
 * Date: 2018/1/20
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class NotesController extends Controller
{
    /**
     * 答题记录的添加
     * @return array
     */
    public function notesAdd(Request $request)
    {
        $params = $request->all();
        $result = \NotesService::notesConfig($params);
        return $result;
    }


    /**
     * 答题的答对记录个数
     * @return array
     */
    public function notesRightCount(Request $request)
    {
        $params = $request->all();
        $result = \NotesService::notesRightCount($params);
        return $result;
    }

    /**
     * 答题的答错记录个数
     * @return array
     */
    public function notesErrorCount(Request $request)
    {
        $params = $request->all();
        $result = \NotesService::notesErrorCount($params);
        return $result;
    }
}
