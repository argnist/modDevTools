<?php
/**
 * @var modx $modx
 */

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('moddevtools.core_path', null, $modx->getOption('core_path') . 'components/moddevtools/');

/** @var modDevTools $modDevTools */
$modDevTools = $modx->getService('moddevtools', 'modDevTools', $corePath . 'model/moddevtools/', array(
    'core_path' => $corePath
));

// Handle request
$path = $modx->getOption('processorsPath', $modDevTools->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));