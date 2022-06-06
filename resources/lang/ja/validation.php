<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attributeを承認してください',
    'active_url'           => ':attributeには有効なURLを指定してください',
    'after'                => ':attributeには:date以降の日付を指定してください',
    'after_or_equal'       => ':attributeには:dateかそれ以降の日付を指定してください',
    'alpha'                => ':attributeには英字のみからなる文字列を指定してください',
    'alpha_dash'           => ':attributeには英数字・ハイフン・アンダースコアのみからなる文字列を指定してください',
    'alpha_num'            => ':attributeには英数字のみからなる文字列を指定してください',
    'array'                => ':attributeには配列を指定してください',
    'before'               => ':attributeには:date以前の日付を指定してください',
    'before_or_equal'      => ':attributeには:dateかそれ以前の日付を指定してください',
    'between'              => [
        'numeric' => ':attributeには:min〜:maxまでの数値を指定してください',
        'file'    => ':attributeには:min〜:max KBのファイルを指定してください',
        'string'  => ':attributeには:min〜:max文字の文字列を指定してください',
        'array'   => ':attributeには:min〜:max個の要素を持つ配列を指定してください',
    ],
    'boolean'              => ':attributeには真偽値を指定してください',
    'confirmed'            => ':attributeが確認用の値と一致しません',
    'date'                 => ':attributeには正しい形式の日付を指定してください',
    'date_format'          => '":format"という形式の日付を指定してください',
    'different'            => ':attributeには:otherとは異なる値を指定してください',
    'digits'               => ':attributeには:digits桁の数値を指定してください',
    'digits_between'       => ':attributeには:min〜:max桁の数値を指定してください',
    'dimensions'           => ':attributeの画像サイズが不正です',
    'distinct'             => '指定された:attributeは既に存在しています',
    'email'                => ':attributeには正しい形式のメールアドレスを指定してください',
    'exists'               => '指定された:attributeは存在しません',
    'file'                 => ':attributeにはファイルを指定してください',
    'filled'               => ':attributeには空でない値を指定してください',
    'image'                => ':attributeには画像ファイルを指定してください',
    'in'                   => ':attributeには:valuesのうちいずれかの値を指定してください',
    'in_array'             => ':attributeが:otherに含まれていません',
    'integer'              => ':attributeには整数を指定してください',
    'ip'                   => ':attributeには正しい形式のIPアドレスを指定してください',
    'ipv4'                 => ':attributeには正しい形式のIPv4アドレスを指定してください',
    'ipv6'                 => ':attributeには正しい形式のIPv6アドレスを指定してください',
    'json'                 => ':attributeには正しい形式のJSON文字列を指定してください',
    'max'                  => [
        'numeric' => ':attributeには:max以下の数値を指定してください',
        'file'    => ':attributeには:max KB以下のファイルを指定してください',
        'string'  => ':attributeには:max文字以下の文字列を指定してください',
        'array'   => ':attributeには:max個以下の要素を持つ配列を指定してください',
    ],
    'mimes'                => ':attributeには:valuesのうちいずれかの形式のファイルを指定してください',
    'mimetypes'            => ':attributeには:valuesのうちいずれかの形式のファイルを指定してください',
    'min'                  => [
        'numeric' => ':attributeには:min以上の数値を指定してください',
        'file'    => ':attributeには:min KB以上のファイルを指定してください',
        'string'  => ':attributeには:min文字以上の文字列を指定してください',
        'array'   => ':attributeには:min個以上の要素を持つ配列を指定してください',
    ],
    'not_in'               => ':attributeには:valuesのうちいずれとも異なる値を指定してください',
    'not_regex'            => ':attribute形式は無効です',
    'numeric'              => ':attributeには数値を指定してください',
    'present'              => ':attributeには現在時刻を指定してください',
    'regex'                => '正しい形式の:attributeを指定してください',
    'required'             => ':attributeは必須です',
    'required_if'          => ':otherが:valueの時:attributeは必須です',
    'required_unless'      => ':otherが:values以外の時:attributeは必須です',
    'required_with'        => ':valuesのうちいずれかが指定された時:attributeは必須です',
    'required_with_all'    => ':valuesのうちすべてが指定された時:attributeは必須です',
    'required_without'     => ':valuesのうちいずれかがが指定されなかった時:attributeは必須です',
    'required_without_all' => ':valuesのうちすべてが指定されなかった時:attributeは必須です',
    'same'                 => ':attributeが:otherと一致しません',
    'size'                 => [
        'numeric' => ':attributeには:sizeを指定してください',
        'file'    => ':attributeには:size KBのファイルを指定してください',
        'string'  => ':attributeには:size文字の文字列を指定してください',
        'array'   => ':attributeには:size個の要素を持つ配列を指定してください',
    ],
    'string'              => ':attributeには文字列を指定してください',
    'timezone'            => ':attributeには正しい形式のタイムゾーンを指定してください',
    'unique'              => 'その:attributeはすでに使われています',
    'uploaded'            => ':attributeのアップロードに失敗しました',
    'url'                 => ':attributeには正しい形式のURLを指定してください',
    'recaptcha'           => ':attribute は間違っています',
    'file_extension'      => ':attribute 正しい拡張子のファイルをアップしてください',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'お客様のメッセージ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        // Contents Management
        'uid' => 'ログインID',
        'password' => 'パスワード',
        'openingLetter' => 'タイトル',
        'selectPublicationDateTime' => '公開日時を選択',
        'startDate' => '公開日',
        'startDateTime' => '公開日',
        'endDateTime' => '公開終了日',
        'startDate' => '公開日',
        'endDate' => '公開終了日',
        'endDateTime' => '公開終了日',
        'contentTypeNews' => 'TOPページの表示',
        'displayTargetFlg' => '表示する組合員',
        'unionMemberCsv' => 'CSV',
        'utilizationBusiness' => '利用事業所',
        'affiliationOffice' => '所属事業所',
        'openingImg' => 'TOPイメージ',
        'contents' => '本文 (送信内容)',
        'topImage' => 'TOPイメージ',

        // Message Management
        'messageName' => '管理名',
        'selectTransmissionTiming' => '送信タイミングを選択',
        'sendDateTime' => '送信日・送信時間',
        'sendTargetFlg' => '送信対象者を選択',
        'thumbnail' => '画像を送信',
        'storeId' => 'お店',
        'sendImage' => '画像を送信',

        // Flyer Management
        'flyerPlanId' => 'チラシID',
        'displayStore' => '表示する店舗',
        'flyerName' => 'タイトル',
        'flyerImg' => 'チラシ画像 (表)',
        'flyerImageFront' => 'チラシ画像 (表)',
        'flyerUraImg' => 'チラシ画像 (裏)',
        'flyerImageBack' => 'チラシ画像 (裏)',
        'updateDate' => '更新日',
        'updateUser' => 'ユーザーの更新',
        'delFlg' => '削除フラグ',

        //stamp type
        'BusinessCode' => 'The business code', //trans
        'csvUploadProduct' => 'The csv',//trans
        'csvUploadProductRedumption' => 'The csv',//trans
        'DepartmentCode' => 'The department code', //trans
        'stampImage' => 'The image of stamp', //trans
        'increaseFlg' => 'The type of benefits', //trans
        'unionMemberCode' => 'The csv',//trans
        'productFlg' => 'The target products for granting benefits', //trans  
        'SpecifiedAmount' => 'The amount', //trans
        'SpecifiedCouponId' => 'The coupon ID', //trans
        'SpecifiedNumberOfPurchase' => 'The number of purchase', //trans
        'SpecifiedNumberOfPoints' => 'The number of points', //trans
        'stampAchievement' => 'This field', //trans
        'stampType' => 'The Stamp Type', //trans
        'stampText' => 'The stamp contents',//trans
        'stampDisplayFlg' => 'The union member to display', //trans
        'stampGrantFlg' => 'The stamping condition', //trans
        'store' => 'Specifying a store as condition for granting benefits', //trans
        'useCount' => 'This field', //trans
        

        // Coupon Management
        'cuponName' => 'タイトル',
        'cuponType' => 'クーポンの種別',
        'cuponDisplayFlg' => '表示する組合員',
        'useCount' => '利用可能回数',
        'useTime' => 'クーポンの利用制限時間の設定',
        'priorityDisplayFlg' => 'クーポン一覧画面における上位表示',
        'autoEntryFlg' => 'ログインした際に自動エントリー',
        'pointGrantFlg' => '特典の付与条件',
        'useFlg' => '特典付与の回数制限',
        'stores' => '特典の付与条件の店舗指定',
        'increaseFlg' => '特典の種類',
        'cuponImg' => 'クーポン画像',
        'couponImage' => 'クーポン画像',
        'cuponText' => '本文 (投稿内容)',
        'pointGrantPurchasesCount' => '下の枠に指定の購入点数を入力してください',
        'grantPoint' => 'ポイント',
        'pointGrantPurchasesPrice' => '金額',

        // Admin Management
        'name' => '名',
        'username' => 'ログインID',
        'email' => 'Eメール',
        'password' => 'パスワード',
        'passwordConfirmation' => '確認用パスワード',
        'role' => '役割',
    ],
];
