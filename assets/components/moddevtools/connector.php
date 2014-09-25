<?php

if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    $path = dirname(dirname(dirname(dirname(__FILE__))));
}
else {
    $path = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
}
/** @noinspection PhpIncludeInspection */
require_once $path . '/config.core.php';

/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var modDevTools $modDevTools */
$modDevTools = $modx->getService('moddevtools', 'modDevTools', $modx->getOption('moddevtools_core_path', null, $modx->getOption('core_path') . 'components/moddevtools/') . 'model/moddevtools/');
$modx->lexicon->load('moddevtools:default');

// handle request
$corePath = $modx->getOption('moddevtools_core_path', null, $modx->getOption('core_path') . 'components/moddevtools/');
$path = $modx->getOption('processorsPath', $modDevTools->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));