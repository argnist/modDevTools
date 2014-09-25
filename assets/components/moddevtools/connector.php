<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var modExtra $modExtra */
$modDevTools = $modx->getService('moddevtools', 'modDeveloperTools', $modx->getOption('moddevtools_core_path', null, $modx->getOption('core_path') . 'components/moddevtools/') . 'model/moddevtools/');
$modx->lexicon->load('moddevtools:default');

// handle request
$corePath = $modx->getOption('moddevtools_core_path', null, $modx->getOption('core_path') . 'components/moddevtools/');
$path = $modx->getOption('processorsPath', $modx->moddevtools->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));