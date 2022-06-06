<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple messages links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */

    'request' => [
        'invalid' => '無効なリクエスト',
    ],
    'success' => [
        'read' => ':nameの取得が完了しました',
        'create' => ':nameの作成が完了しました',
        'update' => ':nameの更新が完了しました',
        'delete' => ':nameの削除が完了しました',
    ],
    'failed' => [
        'read' => ':nameの取得に失敗しました',
        'create' => ':nameの作成に失敗しました',
        'update' => ':nameの更新に失敗しました',
        'delete' => ':nameを削除できませんでした',
        'login' => 'アカウント及びパスワードが一致しません',
    ],
    'custom' => [
        'autoEntryWhenLoggedIn' => 'ログインしたら自動でエントリーとする',
        'fileSize' => [
            'kb' => ':nameには:kb KB以下のファイルを指定してください',
            'mb' => ':nameには:mb MB以下のファイルを指定してください',
            'invalid' => '画像サイズが間違っている'
        ],
        'fileTypesLimitation' => 'ファイルサイズは2M以下、形式はJPEG or PNG のみとなります',
        'flyerFileTypesLimitation' => 'ファイルサイズは2M以下、形式はJPEG or PNG のみとなります',
        'imageNotExist' => '画像が存在しません',
        'csvNotExist' => 'CSVが存在しません ',
        'pleaseSpecifyForGrantingBenfits' => '特典を付与する条件を指定してください',
        'specifyAoOrUbCode' => '利用事業・所属事業で指定する場合は事業コードを入力してください',
        'specifyAoOrUb' => '利用事業・所属事業所で指定する場合は選択してください',
        'specifyAoOrUbOrStore' =>  'お店・利用事業・所属事業所で指定する場合は選択してください',
        'specifyBenefitsToBeGivenWhenConditionsAreMet' => '条件達成時に付与する特典を指定してください',
        'specifyNumberOfTimeCouponCanBeUsed' => 'クーポンの利用可能回数を指定してください無制限の場合は“0”を入力してください',
        'specifyLimitAwardGrant' => '特典付与の回数制限を指定してください',
        'specifyStoreConditionForGrantingBenefits' => '特典を付与する条件に店舗を指定する場合はチェックをつけてください',
        'weWillSendWithFollowingContents' => '下記の内容で送信を行います よろしいですか?',
        'ifYouWantToMakeATimeLimitUseTheCouponPleaseEnterTheTime' => 'クーポンの利用に時間制限を儲ける場合は時間を入力してください（分単位）無制限の場合は“0”を入力してください',
        'inTheCouponListCheckIfWantToDisplayHigher' => 'クーポン一覧にて、上位表示をする場合チェックをつけてください',
        'invalidCsvData' => 'CSVに問題がありますCSVをダウンロードしてエラー内容を確認してください',
        'failedUpload' => 'アップロードに失敗しました',
        'invalidImg' => '画像の種類が間違っています',
        'invalidFileUrl' => 'ファイルが見つかりません',
        'productCodeNotFound' => '該当する商品が見つかりません',
        'EmptyFile' => 'ファイルが見つかりませんでした',
        'productCategoryNotFound' => '商品カテゴリが見つかりません',
        'fileNotRecieved' => 'サーバーがファイルを受信しなかったようです。もう一度やり直してください',
        'invalidFileType' => 'ファイルタイプが無効です',
        'invalidMemberCode' => '正しい組合員番号を入力してください',
        'invalidMemberCodeNoUnionMemberFound' => ' 登録されていない組合員番号です。',
        'invalidMemberCodeWithdrawalDate' => '脱退済みです',
        'invalidMemberCodeNoLineLink' => 'カード登録が済んでない会員です',
        'invalidProductCode' => '正しいJANコードを入力してください',
    ],
];
