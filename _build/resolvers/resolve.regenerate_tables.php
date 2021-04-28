<?php
/** @var array $options */
/** @var xPDOObject $object */
if ($object->xpdo) {

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            /** @var modX $modx */
            $modx =& $object->xpdo;

            $processorsPath = $modx->getOption('moddevtools.core_path', null, $modx->getOption('core_path') . 'components/moddevtools/') . 'processors/';

            $modx->runProcessor('mgr/tables/regenerate', array(
                'filters' => array('modChunk', 'modTemplate', 'modResource')
            ), array('processors_path' => $processorsPath));

            break;
    }
}
$modx->log(xPDO::LOG_LEVEL_INFO, 'modDevTools tables regenerated.');
return true;