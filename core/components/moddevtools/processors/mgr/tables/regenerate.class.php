<?php

/**
 * Class modDevToolsRefreshProcessor
 */
class modDevToolsRefreshProcessor extends modProcessor {
    public $map = array(
        'modChunk' => array('name' => 'name', 'content' => 'snippet', 'type' => 'chunk'),
        'modTemplate' => array('name' => 'templatename', 'content' => 'content', 'type' => 'template'),
        'modResource' => array('name' => 'pagetitle', 'content' => 'snippet', 'type' => 'resource')
    );

    /**
     * Run the processor and return the result.
     *
     * @return mixed
     */
    public function process() {
        $corePath = $this->modx->getOption('moddevtools.core_path', null, $this->modx->getOption('core_path') . 'components/moddevtools/');
        /**
         * @var modDevTools $moddevtools
         */
        $moddevtools = $this->modx->getService('moddevtools', 'modDevTools', $corePath . 'model/moddevtools/', array(
            'core_path' => $corePath,
            'debug' => false
        ));

        if (!$moddevtools->config['accessRegenerate']) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        $filters = $this->getProperty('filters');


        foreach ($filters as $class) {
            if (isset($this->map[$class])) {
                $objects = $this->modx->getIterator($class);
                foreach ($objects as $idx => $object) {
                    $this->modx->setLogLevel( xPDO::LOG_LEVEL_WARN ); // Change log level to WARN
                    $moddevtools->parseContent($object);
                    $this->modx->setLogLevel( xPDO::LOG_LEVEL_INFO ); // Change log level to INFO
                    $this->modx->log(modX::LOG_LEVEL_INFO,
                        'Regenerated links to ' . $this->map[$class]['type'] . ': ' .
                        $object->get($this->map[$class]['name']) . ' (' . $object->get('id') . ')'
                    );
                }
            }
        }

        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
        return $this->success();
    }
}

return 'modDevToolsRefreshProcessor';