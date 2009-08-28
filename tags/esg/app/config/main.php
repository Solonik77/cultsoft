<?php
$websiteLanguages = include_once 'languages.php';
$import = array(
    'application.models.*',
    'application.components.*',
    'application.extensions.kohanahelpers.*',
    'application.extensions.nestedset.*');
$log = array();
$log['class'] = 'CLogRouter';
$log['routes'] = array();
$log['routes'][] = array('class' => 'CFileLogRoute', 'levels' => 'error, warning, trace, profile, info');
if (YII_DEBUG) {
    $log['routes'][] = array('class' => 'XWebDebugRouter', 'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle', 'levels' => 'error, warning, trace, profile, info',);
    $import[] = 'application.extensions.yiidebugtb.*';
}
$strWebsiteLangs = array();
foreach($websiteLanguages as $key => $value) {
    $strWebsiteLangs[] = $key;
}
$strWebsiteLangs = implode('|', $strWebsiteLangs);

return array('basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'ESG',

    'modules' => array('admin'),
    'sourceLanguage' => 'ru',
    'language' => 'uk',
    // preloading 'log' component
    'preload' => array('log', 'ELangHandler'),
    // autoloading model and component classes
    'import' => $import,
    // application components
    'components' => array('log' => $log,
        'cache' => array('class' => 'system.caching.CFileCache',),
        'user' => array('allowAutoLogin' => true),
        'ELangHandler' => array ('class' => 'application.extensions.langhandler.ELangHandler', 'languages' => $websiteLanguages),

        'urlManager' => array('class' => 'application.extensions.langhandler.ELangCUrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'caseSensitive' => false,
            'rules' => array('<lang:(' . $strWebsiteLangs . ')>/<_c>/<_a>/' => '<_c>/<_a>',
                ),
            ),

        'db' => include_once 'db.php',

        'session' => array('sessionName' => 'esgua', 'savePath' => APP_PATH . 'runtime/sessions/', 'timeout' => 3600 * 5 ),

        'siteTranlation' => array('class' => 'CPhpMessageSource',
            'basePath' => APP_PATH . 'messages',
            ),

        'request' => array('enableCookieValidation' => true,
            // 'enableCsrfValidation' => true,
            ),

        'authManager' => array(
            'class' => 'CPhpAuthManager',
            'defaultRoles' => array('guest'),
            ),

        'image' => array('class' => 'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
            // ImageMagick setup path
            'params' => array('directory' => '/opt/local/bin'),
            ),

        ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array('site_modules' => array('news' => "Новости", 'team' => "Сотрудники", 'tvprograms' => "Телевизионные программы", 'partners' => "Партнёры"),
        // Website languages
        'websiteLanguages' => $websiteLanguages,
        // this is used in contact page
        'adminEmail' => 'info@example.com',
        // File storage paths
        'storage' => array(
            'team_photos' => STATIC_PATH . 'images/photos/team/',
            'partner_logos' => STATIC_PATH . 'images/partners/logos/',
            'news_images' => STATIC_PATH . 'images/news/',
            'news_files' => STATIC_PATH . 'files/news/',
            'static_pages_images' => STATIC_PATH . 'images/static_pages/',
            'static_pages_files' => STATIC_PATH . 'files/static_pages/',
            'tv_program_images' => STATIC_PATH . 'images/tv_programs/',
            'tv_program_promo_videos' => STATIC_PATH . 'media/tv_programs/promo/',
            'tv_program_files' => STATIC_PATH . 'files/tv_programs/',
            )
        ),
    );
