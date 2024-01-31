<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class ToastrAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js',
    ];
}
