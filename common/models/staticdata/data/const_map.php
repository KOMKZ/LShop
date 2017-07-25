<?php
use Yii;
return [
    'file_save_type' => [
        \common\models\file\drivers\Disk::NAME => Yii::t('app', '本地存储'),
        \common\models\file\drivers\Oss::NAME => Yii::t('app', 'Oss存储'),
    ],
    'file_is_private' => [
        1 => Yii::t('app', '私有访问'),
        0 => Yii::t('app', '公有访问')
    ],
    'file_is_tmp' => [
        1 => Yii::t('app', '临时文件'),
        0 => Yii::t('app', '永久文件')
    ],
    'file_task_type' => [
        \common\models\file\ar\FileTask::TASK_CHUNK_UPLOAD => Yii::t('app', "文件分片上传任务"),
    ],
    'file_task_status' => [
        \common\models\file\ar\FileTask::STATUS_INIT => Yii::t('app', '初始化')
    ],
    'g_status' => [
        \common\models\goods\ar\Goods::STATUS_DRAFT => Yii::t('app', "草稿"),
        \common\models\goods\ar\Goods::STATUS_ON_SALE => Yii::t('app', "上架"),
        \common\models\goods\ar\Goods::STATUS_ON_NOT_SALE => Yii::t('app', "下架"),
        \common\models\goods\ar\Goods::STAUTS_FORBIDDEN => Yii::t('app', "禁止销售"),
    ],
    'g_atr_type' => [
        \common\models\goods\ar\GoodsAttr::ATR_TYPE_INFO => Yii::t('app', '信息属性'),
        \common\models\goods\ar\GoodsAttr::ATR_TYPE_SKU => Yii::t('app', 'sku属性')
    ]
];
