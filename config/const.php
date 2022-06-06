<?php

return [
    'batchLog' => [
        'path' => env('BATCH_LOG_PATH', '/var/www/batch/log'),
    ],
    'pageBreadCrumbs' => [
        // SAMPLE:
        // 'sample.route' => [
        //     'title' => 'Sample',
        //     'routes' => [
        //         'words.Home' => 'login',
        //         'words.Column' => 'dashboard.index',
        //         'words.Content' => ['notice.index', ['noticeType' => 'deal']],
        //         'words.Login,<i class="fa fa-bullhorn"></i>' => ['notice.index', ['noticeType' => 'deal']],
        //     ]
        // ],
    ],
    'routeMenuGroup' => [
        'notice' => [
            'notice.index',
            'notice.create',
            'notice.edit',
        ],
        'recipe' => [
            'recipe.index',
            'recipe.create',
            'recipe.edit',
        ],
        'productInformation' => [
            'productInformation.index',
            'productInformation.create',
            'productInformation.edit',
        ],
        'column' => [
            'column.index',
            'column.create',
            'column.edit',
        ],
        'flyer' => [
            'flyer.index',
            'flyer.create',
            'flyer.edit',
        ],
        'stamp' => [
            'stamp.index',
            'stamp.create',
            'stamp.edit',
        ],
        'coupon' => [
            'coupon.index',
            'coupon.create',
            'coupon.edit',
        ],
        'message' => [
            'message.index',
            'message.create',
            'message.edit',
        ],
        'user' => [
            'user.index',
        ],
        'batchLog' => [
            'batchLog.index',
        ],
        'admin' => [
            'admin.index',
            'admin.create',
            'admin.edit',
        ],
    ],
    'upload' => [
        'disk' => [
            'default' => 'public',
            'image' => 'public',
            'csv' => 'public',
            'tmp' => [
                'default' => 'public',
                'image' => 'public',
                'csv' => 'public',
            ],
        ],
        'path' => [
            'default' => 'default',
            'image' => 'images',
            'csv' => 'csv',
            'tmp' => [
                'default' => ['tmp', 'default'],
                'image' => ['tmp', 'images'],
                'csv' => ['tmp', 'csv'],
            ],
        ],
        'extension' => [
            'image' => 'png',
            'csv' => 'csv',
        ],
        'custom' => [
            // NOTE: please follow folder structure:
            //  notice/[id]/thumbnail
            //  notice/[id]/csv
            'path' => [
                'csvValidatedExport' => storage_path('app/public/tmp'),

                'noticeThumbnail' => 'M_ContentPlan',
                'noticeImageContent' => 'M_ContentPlan',

                'recipeThumbnail' => 'M_ContentPlan',
                'recipeImageContent' => 'M_ContentPlan',

                'productInformationThumbnail' => 'M_ContentPlan',
                'productInformationImageContent' => 'M_ContentPlan',

                'columnThumbnail' => 'M_ContentPlan',
                'columnImageContent' => 'M_ContentPlan',

                'flyerThumbnail' => 'M_FlyerPlan',

                'stampThumbnail' => 'M_StampPlan',
                
                'messageThumbnail' => 'M_Message',
                'messageImageContent' => 'M_Message',

                'couponThumbnail' => 'M_CuponPlan',
                'couponImageContent' => 'M_CuponPlan',
            ],
            'name' => [
                // 'noticeThumbnail' => 'thumbnail.png',
            ],
        ],
    ],
    'listAo' => [
        '352' => ['0352', 'words.StoreShinShimozeki'],
        '353' => ['0353', 'words.StoreKuchiOgori'],
        '450' => ['0450', 'words.StoreIzumi'],
        '452' => ['0452', 'words.StoreUbe'],
        '455' => ['0455', 'words.StoreThankYou'],
        '456' => ['0456', 'words.StoreTokuyama'],
        '50' => ['0050', 'words.StoreShimada'],
        '2001' => ['2001', 'words.ChubuCenter'],
        '2002' => ['2002', 'words.UbeCenter'],
        '2003' => ['2003', 'words.ShimonosekiCenter'],
        '2004' => ['2004', 'words.ZhouSoutheastCenter'],
        '2007' => ['2007', 'words.IwakuniCenter'],
        '2010' => ['2010', 'words.NagatoCenter'],
        '2011' => ['2011', 'words.ShutoCenter'],
        '2012' => ['2012', 'words.HagiCenter'],
        '2014' => ['2014', 'words.AtsusaCenter'],
        '2015' => ['2015', 'words.ZhouSouthwestCenter'],
    ],
    'listUb' => [
        '1' => ['10', 'words.Store'],
        '2' => ['20', 'words.Delivery'],
        '3' => ['30', 'words.SupperDeliver'],
        '4' => ['40', 'words.MutualAid'],
        '5' => ['50', 'words.Insurance'],
        '6' => ['60', 'words.Welfare'],
        '7' => ['70', 'words.Other1'],
        '8' => ['80', 'words.Other2'],
        '9' => ['90', 'words.Other3'],
    ],
    'listStore' => [
        '1' => 'words.StoreShinShimozeki',
        '3' => 'words.StoreKuchiOgori',
        '4' => 'words.StoreIzumi',
        '2' => 'words.StoreUbe',
        '5' => 'words.StoreThankYou',
        '7' => 'words.StoreTokuyama',
        '8' => 'words.StoreShimada',
    ],
    'listCouponStore' => [
        '0352' => 'words.StoreShinShimozeki',
        '0353' => 'words.StoreKuchiOgori',
        '0450' => 'words.StoreIzumi',
        '0452' => 'words.StoreUbe',
        '0455' => 'words.StoreThankYou',
        '0456' => 'words.StoreTokuyama',
        '0050' => 'words.StoreShimada'
    ],
    'listRole' => [
        '0' => 'words.Administrator',
        '1' => 'words.Checker',
        '2' => 'words.InformationSystemCharge',
        '3' => 'words.Headquarters',
    ],
];
