<?php

/**
 * @param modX $modx
 */
function parseAll($modx) {
    $modx->setLogLevel(modX::LOG_LEVEL_WARN);
    /**
     * @var modDevTools $tools
     */
    $tools = &$modx->moddevtools;
    $objects = $modx->getIterator('modTemplate');
    foreach ($objects as $object) {
        $tools->parseContent($object);
    }
    $objects = $modx->getIterator('modChunk');
    foreach ($objects as $object) {
        $tools->parseContent($object);
    }
    $objects = $modx->getIterator('modResource');
    foreach ($objects as $object) {
        $tools->parseContent($object);
    }
    $modx->setLogLevel(modX::LOG_LEVEL_INFO);
}

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;

    $modelPath = $modx->getOption('moddevtools_core_path', null, $modx->getOption('core_path') . 'components/moddevtools/') . 'model/moddevtools/';
    /**
     * @var modDevTools $modDevTools
     */
    $modDevTools = $modx->getService('moddevtools', 'modDevTools', $modelPath);

    $manager = $modx->getManager();
    $objects = array(
        'modDevToolsLink',
    );

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			foreach ($objects as $tmp) {
				$manager->createObjectContainer($tmp);
			}

            parseAll($modx);
			break;

		case xPDOTransport::ACTION_UPGRADE:
            parseAll($modx);
            break;
		case xPDOTransport::ACTION_UNINSTALL:
            foreach ($objects as $tmp) {
                $manager->removeObjectContainer($tmp);
            }

			break;
	}
}
return true;
