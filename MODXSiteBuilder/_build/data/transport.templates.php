<?php
/** @var modX $this->modx */
/** @var array $sources */

$templates = array();

$tmp = array(
	'basetemplate' => array(
		'templatename' => 'Базовый шаблон',
		'file' => 'basetemplate',
		'description' => 'Базовый шаблон для наследования',
		'category' => 'goodextra'
	),
	'maintemplate' => array(
		'templatename' => 'Шаблон главной страницы',
		'file' => 'maintemplate',
		'description' => 'Шаблон главной страницы',
		'category' => 'goodextra'
	),
);
$setted = false;
foreach ($tmp as $k => $v) {
    
    /** @var modtemplate $template */
    $template = $this->modx->newObject('modTemplate');
    $template->fromArray(array(
        'templatename' => @$v['templatename'],
        'category' => 0,
        'description' => @$v['description'],
        'content' => file_get_contents($this->config['PACKAGE_ROOT'] . 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/templates/template.' . $v['file'] . '.html'),
        'static' => false,
        //'source' => 1,
        //'static_file' => 'core/components/'.strtolower($this->config['PACKAGE_NAME']).'/elements/templates/template.' . $v['file'] . '.html',
    ), '', true, true);
    $templates[] = $template;
}
unset($tmp, $properties);

return $templates;