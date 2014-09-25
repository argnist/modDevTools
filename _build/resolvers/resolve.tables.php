<?php

function parseAll($modx) {
    $objects = $modx->getIterator('modTemplate');
    foreach ($objects as $object) {
        $modx->moddevtools->parseContent($object);
    }
    $objects = $modx->getIterator('modChunk');
    foreach ($objects as $object) {
        $modx->moddevtools->parseContent($object);
    }
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
