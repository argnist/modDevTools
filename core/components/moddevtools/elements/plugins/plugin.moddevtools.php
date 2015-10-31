<?php

/**
 * modDevTools
 *
 * Copyright 2014 by Vitaly Kireev <kireevvit@gmail.com>
 *
 * @package moddevtools
 *
 * @var modX $modx
 * @var int $id
 * @var string $mode
 */

/**
 * @var modx $modx
 * @var array $scriptProperties
 * @var modResource $resource
 * @var modTemplate $template
 * @var modChunk $chunk
 */
$corePath = $modx->getOption('moddevtools.core_path', null, $modx->getOption('core_path') . 'components/moddevtools/');
/**
 * @var modDevTools $moddevtools
 */
$moddevtools = $modx->getService('moddevtools', 'modDevTools', $corePath . 'model/moddevtools/', array(
    'core_path' => $corePath,
    'debug' => false
));

$eventName = $modx->event->name;
switch ($eventName) {
    case 'OnDocFormSave':
        $moddevtools->debug('Start OnDocFormSave');
        $moddevtools->parseContent($resource);
        break;
    case 'OnTempFormSave':
        $moddevtools->debug('Start OnTempFormSave');
        $moddevtools->parseContent($template);
        break;
    case 'OnTVFormSave':

        break;
    case 'OnChunkFormSave':
        $moddevtools->debug('Start OnChunkFormSave');
        $moddevtools->parseContent($chunk);
        break;
    case 'OnSnipFormSave':

        break;
    /* Add breadcrumbs */
    case 'OnDocFormPrerender':
        if ($modx->event->name == 'OnDocFormPrerender') {
            $moddevtools->getBreadCrumbs($scriptProperties);
            return;
        }
        break;

    /* Add tabs */
    case 'OnTempFormPrerender':
        if ($mode == modSystemEvent::MODE_UPD) {
            $result = $moddevtools->outputTab('Template');
        }
        break;

    case 'OnTVFormPrerender':
        break;


    case 'OnChunkFormPrerender':
        if ($mode == modSystemEvent::MODE_UPD) {
            $result = $moddevtools->outputTab('Chunk');
        }
        break;

    case 'OnSnipFormPrerender':
        if ($mode == modSystemEvent::MODE_UPD) {
            $result = $moddevtools->outputTab('Snippet');
        }
        break;


}

if (isset($result) && $result === true)
    return;
elseif (isset($result)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[modDevTools] An error occured. Event: '.$eventName.' - Error: '.($result === false) ? 'undefined error' : $result);
    return;
}