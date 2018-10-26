<?php
/**
 * PHP version 7.1
 * @author Original Author Ilya Utkin https://github.com/ilyautkin/siteExtra
 * @author This version author iWatchYouFromAfar https://github.com/iWatchYouFromAfar
 * @version 1.0.0
 * 01.10.2018 - 03.10.2018
 * 
 * Создаем ресурсы:
 * robots.txt
 * Страница не найдена / 404
 * Карта сайта
 * sitemap.xml
 */

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        $site_start = $modx->getObject('modResource', $modx->getOption('site_start'));
        if ($site_start) {
            $site_start->set('hidemenu', true);
            $site_start->save();
        }
            
        /* robots.txt */
        $alias = 'robots';
        $parent = 0;
        $templateId = 0;
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1010,
            'pagetitle'    => $alias . '.txt',
            'alias'        => $alias,
            'uri'          => $alias . '.txt',
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,

            'searchable'   => 0,
            'content_type' => 3,
            'contentType'  => 'text/plain',

            'content' => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                User-agent: *
                Allow: /
                
                Host: {\$_modx->config.http_host}
                
                Sitemap: {\$_modx->config.site_url}sitemap.xml
            ")
        ));
        $resource->save();

        /* 404 */
        $alias = '404';
        $parent = 0;
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1001,
            'pagetitle'    => 'Страница не найдена',
            'longtitle'    => '&nbsp;',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                <div style='width: 500px; margin: -30px auto 0; overflow: hidden;padding-top: 25px;'>
                    <div style='float: left; width: 100px; margin-right: 50px; font-size: 75px;margin-top: 45px;'>404</div>
                    <div style='float: left; width: 350px; padding-top: 30px; font-size: 14px;'>
                        <h2>Страница не найдена</h2>
                        <p style='margin: 8px 0 0;'>Страница, на которую вы зашли, вероятно, была удалена с сайта, либо ее здесь никогда не было.</p>
                        <p style='margin: 8px 0 0;'>Возможно, вы ошиблись при наборе адреса или перешли по неверной ссылке.</p>
                        <h3 style='margin: 15px 0 0;'>Что делать?</h3>
                        <ul style='margin: 5px 0 0 15px;'>
                            <li>проверьте правильность написания адреса,</li>
                            <li>перейдите на <a href='{\$_modx->config.site_url}'>главную страницу</a> сайта,</li>
                            <li>или <a href='javascript:history.go(-1);'>вернитесь на предыдущую страницу</a>.</li>
                        </ul>
                    </div>
                </div>
            ")
        ));
        $resource->save();
        $res404 = $resource->get('id');
        
        /* HTML карта сайта */
        $alias = 'site-map';
        $parent = 0;
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1000,
            'pagetitle'    => 'Карта сайта',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                {'pdoMenu' | snippet : [
                    'startId' => 0,
                    'ignoreHidden' => 1,
                    'resources' => '-".$res404.",-' ~ \$_modx->resource.id,
                    'level' => 2,
                    'outerClass' => '',
                    'firstClass' => '',
                    'lastClass' => '',
                    'hereClass' => '',
                    'where' => '{\"searchable\":1}'
                ]}
            ")
        ));
        $resource->save();

        /* sitemap.xml */
        $alias = 'sitemap';
        $parent = 0;
        $templateId = 0;
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1011,
            'pagetitle'    => $alias . '.xml',
            'alias'        => $alias,
            'uri'          => $alias . '.xml',
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,

            'searchable'   => 0,
            'content_type' => 2,
            'contentType'  => 'text/xml',

            'content' => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                {'pdoSitemap' | snippet : [ 'showHidden' => 1, 'resources' => '-{$res404}' ]}
            ")
        ));
        $resource->save();
        
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;
