<?php
/**
 * 提现
 * Author: CK
 * Date: 2017/12/26
 */

namespace Modules\Backend\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class DfController extends Controller
{
    /**
     * 体现添加
     * @return array
     */
    public function dfAdd(Request $request)
    {
        $params = $request->all();
        $result = \DfService::dfAdd($params);
        return $result;
    }


}
