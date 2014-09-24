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
 */
$path = $modx->getOption('moddevtools_core_path',null,$modx->getOption('core_path').'components/moddevtools/').'model/moddevtools/';
/**
 * @var modDeveloperTools $devTools
 */
$devTools = $modx->getService('devTools','modDeveloperTools',$path, array('debug' => true));
$eventName = $modx->event->name;

switch($eventName) {
    case 'OnDocFormSave':

        break;
    case 'OnTempFormSave':
        $devTools->debug('Start OnTempFormSave');
        $result = $devTools->parseContent($template);
        break;
    case 'OnTVFormSave':

        break;
    case 'OnChunkFormSave':

        break;
    case 'OnSnipFormSave':

        break;
    /* Add tabs */
    case 'OnDocFormPrerender':

        break;

    case 'OnTempFormPrerender':
        if ($mode == modSystemEvent::MODE_UPD) {
            $result = $devTools->outputTab('Template');
        }
        break;

    case 'OnTVFormPrerender':
        break;


    case 'OnChunkFormPrerender':
        break;

    case 'OnSnipFormPrerender':
        break;


}
if (isset($result) && $result === true)
    return;
elseif (isset($result)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[modDeveloperTools] An error occured. Event: '.$eventName.' - Error: '.($result === false) ? 'undefined error' : $result);
    return;
}