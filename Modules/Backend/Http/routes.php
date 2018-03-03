<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    #无需身份验证
    $api->group(['namespace' => 'Modules\Backend\Http\Controllers', 'prefix' => 'backend', 'middleware' => ['session_start']], function ($api) {
        // 验证码正式接口
//        $api->get('code/{tmp}', 'AdminController@qrcode');
        #获取openid
        $api->get('get-openid', 'UserController@getOpenid');
        #用户管理
        $api->post('user-add', 'UserController@userAdd');//用户创建
        $api->get('user-detail', 'UserController@userDetail');//用户查询
        #敏感字过滤
        $api->get('filter-word', 'PayController@filterWord');
        #获取订单二维码
        $api->get('order-qrcode', 'OrderController@orderQrcode');
        #二维码上传
        $api->post('qrcode-upload', 'FileUploadController@qrcodeUpLoad');
        #订单二维码url
        $api->post('qrcode-upload', 'FileUploadController@qrcodeUpLoad');
        #订单二维码保存
        $api->post('order-edit-img-url', 'OrderController@OrderEditImgUrl');
        $api->post('order-over', 'OrderController@orderOver');//红包过期触发接口
        #wx
        #商家问题答题记录
        $api->post('notes-add', 'NotesController@notesAdd');
        #获取一轮答题接口
        $api->get('question-all', 'QuestionController@questionAll');
        #当前题目的详情（题目信息 商家信息 答题对错个数）
        $api->get('question-detail-now', 'QuestionController@questionDetailNow');
        #对当前题目的点赞添加
        $api->post('question-good', 'QuestionController@questionGood');
        #对当前题目的分享次数添加
        $api->post('question-share', 'QuestionController@questionLikeNum');
        #商家主页信息
        $api->get('merch-portal', 'MerchantsController@merchPortal');
        #计算器和答题红包开关详情
        $api->get('config-switch-detail', 'ConfigController@configSwitchDetail');
    });
    #小程序端身份认证
    $api->group(['namespace' => 'Modules\Backend\Http\Controllers', 'prefix' => 'backend', 'middleware' => ['random_session', 'session_start']], function ($api) {
        #订单管理
        $api->post('order-add', 'OrderController@orderAdd');//订单添加
        $api->post('order-edit', 'OrderController@orderEdit');//订单修改
        $api->get('order-detail', 'OrderController@orderDetail');//订单详情
        #随机获取题目
        $api->get('randquestion-one', 'RandQuestionController@randQuestionOne');
        #获取排序最大的广告
        $api->get('ad-sort-max-one', 'AdController@adSortMaxOne');
        #红包记录
        $api->post('packrecord-add', 'PackRecordController@packRecordAdd'); //红包记录的添加
        $api->get('packrecord-list', 'PackRecordController@packRecordList'); //当前红包记录的列表信息
        #用户
        $api->post('user-edit', 'UserController@userEdit');//用户更新
        $api->get('user-packrecord-recive', 'UserController@userAllPackRecive');//用户的收到红包记录
        $api->get('user-packrecord-send', 'UserController@userAllPackSend');//用户的发出红包记录
        $api->get('user-packrecord-refund', 'UserController@userAllPackRefund');//用户的退回记录
        #用户答对接口 和 打错接口
        $api->get('user-right-list', 'UserController@userRightList');//用户答对
        $api->get('user-error-list', 'UserController@userErrorList');//用户答错

        $api->post('df-add', 'DfController@dfAdd');//添加提现记录
        #支付
        $api->post('pay-money-config', 'PayController@getPayConfig');
        #提现
        $api->post('refund-money-config', 'PayController@getRefundConfig');
        #回调地址
        $api->post('pay-notify', 'PayController@notify');
        #用户答题接口
        $api->get('user-check-question', 'UserController@userCheckQuestion');//用户答题接口
        #获取二维码
        $api->get('get-qrcode-config', 'PayController@getQrcodeConfig');
        #体现到银行卡
        $api->post('bank-card', 'PayController@bankCard');
        #银行卡二元素验证
        $api->get('bank-verify', 'PayController@bankVerify');
        #银行卡类别验证
        $api->get('bank-type', 'PayController@bankType');
    });


    #需要身份验证
    $api->group(['namespace' => 'Modules\Backend\Http\Controllers', 'prefix' => 'backend', 'middleware' => ['jwt-admin', 'log-admin', 'session_start']], function ($api) {
        #账户管理
        $api->post('dthb-login', 'AdminController@adminLogin');
        $api->get('dthb-detail', 'AdminController@adminDetail');
        $api->post('dthb-edit', 'AdminController@adminEdit');
        $api->post('dthb-delete', 'AdminController@adminDelete');
        $api->post('dthb-change-pwd', 'AdminController@adminChangePassword');
        #注册
        $api->post('dthb-add', 'AdminController@adminAdd');
//        #操作日志管理
//        $api->get('log-list', 'LogController@logList');
//        $api->get('log-detail', 'LogController@logDetail');
        #文件上传
        $api->post('file-upload', 'FileUploadController@fileUpLoad');    //多文件上传
        $api->get('df-list', 'PcController@dflist');//提现列表
        $api->get('user-list', 'PcController@userList');//用户列表
        $api->get('order-list', 'PcController@orderList');//订单列表
        $api->get('order-list-detail', 'PackRecordController@packRecordList'); //当前红包记录的列表信息
        #随机题目
        $api->post('randquestion-add', 'RandQuestionController@randQuestionAdd');
        $api->post('randquestion-edit', 'RandQuestionController@randQuestionEdit');
        $api->get('randquestion-list', 'RandQuestionController@randQuestionList');
        $api->get('randquestion-detail', 'RandQuestionController@randQuestionDetail');
        $api->post('randquestion-delete', 'RandQuestionController@randQuestionDelete');
        #广告
        $api->post('ad-add', 'AdController@adAdd');
        $api->post('ad-edit', 'AdController@adEdit');
        $api->get('ad-list', 'AdController@adList');
        $api->get('ad-detail', 'AdController@adDetail');
        $api->post('ad-delete', 'AdController@adDelete');
        #今日新增统计
        $api->get('today-count', 'UserController@todayCount');
        #单轮答题个数设置
        $api->post('config-add', 'ConfigController@configAdd');
        $api->get('config-new-one', 'ConfigController@configNewOne');
        #商家
        $api->post('merch-add', 'MerchantsController@merchAdd');
        $api->post('merch-edit', 'MerchantsController@merchEdit');
        $api->post('merch-delete', 'MerchantsController@merchDelete');
        $api->get('merch-detail', 'MerchantsController@merchDetail');
        $api->get('merch-list', 'MerchantsController@merchList');
        $api->get('merch-search-list', 'MerchantsController@merchSearchList');//查询所有商家
        #商家问题
        $api->post('question-add', 'QuestionController@questionAdd');
        $api->post('question-edit', 'QuestionController@questionEdit');
        $api->post('question-delete', 'QuestionController@questionDelete');
        $api->get('question-detail', 'QuestionController@questionDetail');
        $api->get('question-list', 'QuestionController@questionList');
        #计算器和答题红包开关切换
        $api->post('config-switch-add', 'ConfigController@configSwitchAdd');
    });


});

