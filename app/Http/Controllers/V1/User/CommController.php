<?php

namespace App\Http\Controllers\V1\User;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Utils\Dict;
use Illuminate\Http\Request;

class CommController extends Controller
{
    public function config()
    {
        $data = [
            'is_telegram' => (int)admin_setting('telegram_bot_enable', 0),
            'telegram_discuss_link' => admin_setting('telegram_discuss_link'),
            'stripe_pk' => admin_setting('stripe_pk_live'),
            'withdraw_methods' => admin_setting('commission_withdraw_method', Dict::WITHDRAW_METHOD_WHITELIST_DEFAULT),
            'withdraw_close' => (int)admin_setting('withdraw_close_enable', 0),
            
            'currency' => admin_setting('currency', 'CNY'),
            'currency_symbol' => admin_setting('currency_symbol', '¥'),
            'commission_distribution_enable' => (int)admin_setting('commission_distribution_enable', 0),
            'commission_distribution_l1' => admin_setting('commission_distribution_l1'),
            'commission_distribution_l2' => admin_setting('commission_distribution_l2'),
            'commission_distribution_l3' => admin_setting('commission_distribution_l3'),
            
            'app_url_android' => 'https://baidu.com',
            'app_url_windows' => 'https://baidu.com',
            'app_url_ios' => 'https://baidu.com',
            'app_url_drawin' => 'https://baidu.com',
            
            'menus' => array_values([
                [
                    'name' => '推荐有奖',
                    'label' => 'reward',
                    'iconUrl' => 'assets/images/pngs/reward.png',
                    'routePath' => '',
                    'order' => 1,
                    'show' => false,
                ],
                [
                    'name' => '免费领会员',
                    'label' => 'freeMembership',
                    'iconUrl' => 'assets/images/pngs/member.png',
                    'routePath' => '',
                    'order' => 1,
                    'show' => false,
                ],
                [
                    'name' => '大家都在玩',
                    'label' => 'hot',
                    'iconUrl' => 'assets/images/pngs/hot.png',
                    'routePath' => '',
                    'order' => 1,
                    'show' => false,
                ],
                [
                    'name' => '消息中心',
                    'label' => 'news',
                    'iconUrl' => 'assets/images/pngs/message.png',
                    'routePath' => '/notice',
                    'order' => 1,
                    'show' => true,
                ],
                [
                    'name' => '软件防丢失',
                    'label' => 'prevention',
                    'iconUrl' => 'assets/images/pngs/prevention.png',
                    'routePath' => '/prevention',
                    'order' => 1,
                    'show' => true,
                ],
                [
                    'name' => '在线客服',
                    'label' => 'onlineService',
                    'iconUrl' => 'assets/images/pngs/customer_service.png',
                    'routePath' => '',
                    'order' => 1,
                    'show' => false,
                ],
                [
                    'name' => '上传日志',
                    'label' => 'uploadLog',
                    'iconUrl' => 'assets/images/pngs/upload_log.png',
                    'routePath' => '',
                    'order' => 1,
                    'show' => false,
                ],
                [
                    'name' => '登出',
                    'label' => 'logout',
                    'iconUrl' => '',
                    'routePath' => '/login',
                    'order' => 1,
                    'show' => true,
                ],
            ]),
        ];
        return $this->success($data);
    }

    public function getStripePublicKey(Request $request)
    {
        $payment = Payment::where('id', $request->input('id'))
            ->where('payment', 'StripeCredit')
            ->first();
        if (!$payment) throw new ApiException('payment is not found');
        return $this->success($payment->config['stripe_pk_live']);
    }
}
