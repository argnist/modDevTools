<?php
/**
 * modDevTools
 *
 * Copyright 2014 by Vitaly Kireev <kireevvit@gmail.com>
 *
 * @package moddevtools
 * @subpackage plugin
 *
 * @event OnChunkFormPrerender
 * @event OnChunkFormSave
 * @event OnDocFormPrerender
 * @event OnDocFormSave
 * @event OnSnipFormPrerender
 * @event OnSnipFormSave
 * @event OnTempFormPrerender
 * @event OnTempFormSave
 * @event OnTVFormPrerender
 * @event OnTVFormSave
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var string $mode
 * @var modResource $resource
 * @var modTemplate $template
 * @var modChunk $chunk
 */

$eventName = $modx->event->name;

$corePath = $modx->getOption('moddevtools.core_path', null, $modx->getOption('core_path') . 'components/moddevtools/');
/** @var modDevTools $moddevtools */
$moddevtools = $modx->getService('moddevtools', 'modDevTools', $corePath . 'model/moddevtools/', array(
    'core_path' => $corePath
));

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
    /** Add breadcrumbs */
    case 'OnDocFormPrerender':
        $moddevtools->getBreadCrumbs($scriptProperties);
        return;
        break;
    /** Add tabs */
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

if (isset($result) && $result === true) {
    return;
} elseif (isset($result)) {
    $modx->log(xPDO::LOG_LEVEL_ERROR, '[modDevTools] An error occured. Event: ' . $eventName . ' - Error: ' . ($result === false) ? 'undefined error' : $result);
    return;
}