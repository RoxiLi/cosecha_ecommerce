<?php

use  RebelCode\Spotlight\Instagram\Modules\AccountsModule ;
use  RebelCode\Spotlight\Instagram\Modules\AdminModule ;
use  RebelCode\Spotlight\Instagram\Modules\CacheIntegrationsModule ;
use  RebelCode\Spotlight\Instagram\Modules\CleanUpCronModule ;
use  RebelCode\Spotlight\Instagram\Modules\ConfigModule ;
use  RebelCode\Spotlight\Instagram\Modules\Dev\DevModule ;
use  RebelCode\Spotlight\Instagram\Modules\EngineModule ;
use  RebelCode\Spotlight\Instagram\Modules\FeedsModule ;
use  RebelCode\Spotlight\Instagram\Modules\ImportCronModule ;
use  RebelCode\Spotlight\Instagram\Modules\InstagramModule ;
use  RebelCode\Spotlight\Instagram\Modules\MediaModule ;
use  RebelCode\Spotlight\Instagram\Modules\MigrationModule ;
use  RebelCode\Spotlight\Instagram\Modules\NewsModule ;
use  RebelCode\Spotlight\Instagram\Modules\NotificationsModule ;
use  RebelCode\Spotlight\Instagram\Modules\PreviewModule ;
use  RebelCode\Spotlight\Instagram\Modules\RestApiModule ;
use  RebelCode\Spotlight\Instagram\Modules\SaasModule ;
use  RebelCode\Spotlight\Instagram\Modules\ServerModule ;
use  RebelCode\Spotlight\Instagram\Modules\ShortcodeModule ;
use  RebelCode\Spotlight\Instagram\Modules\TemplatesModule ;
use  RebelCode\Spotlight\Instagram\Modules\TokenRefresherModule ;
use  RebelCode\Spotlight\Instagram\Modules\UiModule ;
use  RebelCode\Spotlight\Instagram\Modules\WidgetModule ;
use  RebelCode\Spotlight\Instagram\Modules\WordPressModule ;
use  RebelCode\Spotlight\Instagram\Modules\WpBlockModule ;
$modules = [
    'wp'                   => new WordPressModule(),
    'admin'                => new AdminModule(),
    'config'               => new ConfigModule(),
    'ig'                   => new InstagramModule(),
    'feeds'                => new FeedsModule(),
    'templates'            => new TemplatesModule(),
    'preview'              => new PreviewModule(),
    'accounts'             => new AccountsModule(),
    'media'                => new MediaModule(),
    'engine'               => new EngineModule(),
    'importer'             => new ImportCronModule(),
    'cleaner'              => new CleanUpCronModule(),
    'token_refresher'      => new TokenRefresherModule(),
    'rest_api'             => new RestApiModule(),
    'server'               => new ServerModule(),
    'ui'                   => new UiModule(),
    'shortcode'            => new ShortcodeModule(),
    'wp_block'             => new WpBlockModule(),
    'widget'               => new WidgetModule(),
    'notifications'        => new NotificationsModule(),
    'migrator'             => new MigrationModule(),
    'saas'                 => new SaasModule(),
    'news'                 => new NewsModule(),
    'integrations/caching' => new CacheIntegrationsModule(),
    'dev'                  => new DevModule(),
];
return $modules;