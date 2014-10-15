<?php

class modDevToolsSearchProcessor extends modProcessor {

    public function process() {
        $data = $this->getElements('modChunk', 'snippet', 'name');
        $data = array_merge($data, $this->getElements('modTemplate', 'content', 'templatename'));

        return $this->outputArray($data);
    }

    public function getElements($class, $contentField, $nameField) {
        $data = array();
        $search = $this->getProperty('search-string');
        $elements = $this->modx->getIterator($class, array($contentField . ':LIKE' => '%' . $search . '%'));
        /**
         * @var modElement $element
         */
        foreach ($elements as $element) {
            $object = $element->toArray();
            $data[] = array(
                'id' => $object['id'],
                'name' => $object[$nameField],
                'class' => $class,
                'content' => $this->modx->moddevtools->getSearchContent($object['content'], $search),
                'offset' => 0
            );
        }
        return $data;
    }
}

return 'modDevToolsSearchProcessor';