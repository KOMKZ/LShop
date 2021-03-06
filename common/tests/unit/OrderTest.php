<?php
namespace common\tests;
use Yii;
use common\models\order\ar\CartItem;
use common\models\order\Cart;
use common\models\order\CartModel;
use common\models\user\query\UserQuery;
use common\models\goods\GoodsModel;
use common\models\goods\ar\Goods;
use common\models\goods\ar\GoodsSku;
use common\models\goods\query\GoodsSkuQuery;
use common\models\user\query\UserReceAddrQuery;
use common\models\order\OrderModel;
use common\models\pay\payment\Alipay;
use common\models\pay\payment\Wxpay;
use common\models\pay\ar\PayTrace;



class OrderTest extends \Codeception\Test\Unit
{

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    protected function _before()
    {

    }

    protected function _after()
    {
    }

    public function debug($data){
        console($data);
    }

    public function createGoods(){
        $data = [
            'g_cls_id' => 3,
            'g_status' => Goods::STATUS_ON_SALE,
            'g_primary_name' => 'IPhone7',
            'g_secondary_name' => 'IPhone7',
            'g_start_at' => time(),
            'g_end_at' => time()+3600,
            'g_create_uid' => 1,
            'g_metas' => [
                [
                    // 型号
                    'g_atr_id' => 1,
                    'gm_value' => "IPhone7"
                ],
            ],
            'g_attrs' => [
                [
                    // 颜色
                    'g_atr_id' => 5,
                    'g_atr_opts' => [
                        [
                            'g_opt_name' => '金色',
                            'g_opt_img' => 'https://img11.360buyimg.com/n9/s40x40_jfs/t3148/124/1614329694/101185/b709b251/57d0c55cNa20597da.jpg'
                        ],
                        [
                            'g_opt_name' => '白色',
                            'g_opt_img' => 'https://img11.360buyimg.com/n9/s40x40_jfs/t3148/124/1614329694/101185/b709b251/57d0c55cNa20597da.jpg'
                        ],
                        [
                            'g_opt_name' => '亮黑色',
                            'g_opt_img' => 'https://img11.360buyimg.com/n9/s40x40_jfs/t3148/124/1614329694/101185/b709b251/57d0c55cNa20597da.jpg'
                        ],
                    ]
                ],
                [
                    // 内存
                    'g_atr_id' => 4,
                    'g_atr_opts' => [
                        [
                            'g_opt_name' => '32G',
                        ],
                        [
                            'g_opt_name' => '128G',
                        ],
                        [
                            'g_opt_name' => '256G',
                        ],
                    ]
                ]
            ],
            'g_intro_text' => "IPhone7很长的富文本介绍",
        ];
        $gModel = new GoodsModel();
        $goods = $gModel->createGoods($data);

        $skuData = [
            [
                'g_sku_value' => '4:1;5:1',
                'g_sku_stock_num' => 100,
                'g_sku_price' => '499900',
                'g_sku_sale_price' => '529900',
                'g_sku_status' => GoodsSku::STATUS_ON_NOT_SALE,
                'g_sku_create_uid' => 1,
                'g_id' => $goods->g_id,
            ],
            [
                'g_sku_value' => '4:2;5:1',
                'g_sku_stock_num' => 100,
                'g_sku_price' => '579900',
                'g_sku_sale_price' => '599900',
                'g_sku_status' => GoodsSku::STATUS_ON_NOT_SALE,
                'g_sku_create_uid' => 1,
                'g_id' => $goods->g_id,
            ],
            [
                'g_sku_value' => '4:3;5:1',
                'g_sku_stock_num' => 100,
                'g_sku_price' => '678800',
                'g_sku_sale_price' => '698800',
                'g_sku_status' => GoodsSku::STATUS_ON_NOT_SALE,
                'g_sku_create_uid' => 1,
                'g_id' => $goods->g_id,
            ],
        ];
        $gModel = new GoodsModel();
        $skus = $gModel->createMultiGoodsSku($skuData, $goods);
        $oneSku = array_pop($skus);
        return $oneSku['g_sku_value'];
    }

    public function testCreateOrder(){
        // Yii::$app->db->beginTransaction();
        $goodsSkuValue = $this->createGoods();
        $goodsSku = GoodsSkuQuery::find()->where(['g_id' => 1, 'g_sku_value' => $goodsSkuValue])->one();
        $goodsSku01 = GoodsSkuQuery::find()->where(['g_id' => 1, 'g_sku_value' => '4:2;5:1'])->one();
        $user = UserQuery::findActive()->andWhere(['u_id' => 1])->one();
        $defaultReceAddr = UserReceAddrQuery::find()->where(['rece_belong_uid' => $user->u_id, 'rece_default_addr' => 'yes'])->one();
        $orderModel = new OrderModel();
        $orderData = [
            'goods_sku_data' => [
                ['sku' => $goodsSku, 'number' => 2],
                ['sku' => $goodsSku01]
            ],
            'receiver_addr_id' => $defaultReceAddr->rece_addr_id,
            'discount_data' => [
                'user_coupon_price_discount' => [
                    ['id' => 'CR000001'],
                    ['id' => 'CR000002']
                ],
                'global_order_price_discount' => [
                    // ['id' => 'order_full_slice'],
                ]
            ]
        ];
        // 创建预数据 用于订单展示
        $order = $orderModel->createOrderPreData($user, $orderData);
        if(!$order){
            $this->debug($orderModel->getOneError());
            return false;
        }
        // 提交订单数据 用户选择订单
        $order = $orderModel->createOrder($user, $orderData);
        if(!$order){
            $this->debug($orderModel->getOneError());
            return false;
        }
        // 创建交易
        $payData = $orderModel->createOrderPayData($order, [
            'pt_pay_type' => Alipay::NAME,
            'pt_pre_order_type' => PayTrace::TYPE_URL
        ]);
        if(!$payData){
            $this->debug($orderModel->getOneError());
            return false;
        }
        console($payData->toArray());
    }

}
