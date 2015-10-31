<?php
/**
 * Resolve creating db tables
 *
 * THIS RESOLVER IS AUTOMATICALLY GENERATED, NO CHANGES WILL APPLY
 *
 * @package moddevtools
 * @subpackage build
 */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('moddevtools.core_path', null, $modx->getOption('core_path') . 'components/moddevtools/') . 'model/';
            $modx->addPackage('moddevtools', $modelPath);

            $manager = $modx->getManager();

            $manager->createObjectContainer('modDevToolsLink');

            break;
    }
}

return true;