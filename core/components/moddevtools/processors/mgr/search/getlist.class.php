<?php

/**
 * Class modDevToolsSearchProcessor
 */
class modDevToolsSearchProcessor extends modProcessor {
    public $map = array(
        'modChunk' => array('name' => 'name', 'content' => 'snippet', 'type' => 'chunk'),
        'modTemplate' => array('name' => 'templatename', 'content' => 'content', 'type' => 'template')
    );

    /**
     * Run the processor and return the result.
     *
     * @return mixed
     */
    public function process() {
        $data = array();
        $filters = $this->getProperty('filters');
        foreach ($filters as $class => $enabled) {
            if (isset($this->map[$class]) && $enabled) {
                $data = array_merge($data, $this->getElements($class));
            }
        }

        return $this->outputArray($data);
    }

    /**
     * @param $class
     * @return array
     */
    public function getElements($class) {
        $data = array();
        $search = $this->getProperty('search-string');
        $c = $this->modx->newQuery($class, array($this->map[$class]['content'] . ':LIKE BINARY' => '%' . $search . '%'));
        $elements = $this->modx->getIterator($class, $c);
        /**
         * @var modElement $element
         */
        foreach ($elements as $element) {
            $object = $element->toArray();
            $data[] = array(
                'id' => $object['id'],
                'name' => $object[$this->map[$class]['name']],
                'class' => $class,
                'content' => $this->modx->moddevtools->getSearchContent($object['content'], $search),
                'type' => $this->map[$class]['type'],
                'offset' => 0
            );
        }
        return $data;
    }
}

return 'modDevToolsSearchProcessor';