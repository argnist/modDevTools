<?php
/**
 * The base class for modDevTools.
 */

class modDevTools {
	/** @var modX $modx */
	public $modx;

    /** @var array $config */
    public $config = array();

    /** @var string $namespace */
    public $namespace = 'moddevtools';

    /** @var string $version */
    public $version = '1.1.0';

    /**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

        $corePath = $this->getOption('core_path', $config, $this->modx->getOption('core_path') . 'components/moddevtools/');
        $assetsUrl = $this->getOption('assets_url', $config, $this->modx->getOption('assets_url') . 'components/moddevtools/');
        $debug = $this->getOption('debug', $config, false);

        // Load some default paths for easier management
		$this->config = array_merge(array(
            'namespace' => $this->namespace,
            'version' => $this->version,
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $assetsUrl . 'connector.php',
			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',
		), $config);

        // set default options
        $this->config = array_merge($this->config, array(
            'debug' => $debug,
            'accessRegenerate' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('system_perform_maintenance_tasks')),
            'viewChunk' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('view_chunk')),
            'saveChunk' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('save_chunk')),
            'viewTemplate' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('view_template')),
            'saveTemplate' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('save_template')),
            'viewSnippet' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('view_snippet')),
            'editSnippet' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('edit_snippet')),
            'saveSnippet' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('save_snippet')),
            'viewResource' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('view_document')),
            'saveResource' => ($this->modx->user->get('sudo') || $this->modx->hasPermission('save_document')),
            'extractLines' => 6,
            'extractEllips' => '...',
            'extractSeparator' => '<br>',
            'extractQuantity' => 1,
            'pcreModifier' => 'u',
         ));

        $this->modx->addPackage('moddevtools', $this->config['modelPath']);
		$this->modx->lexicon->load('moddevtools:default');
	}

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = array(), $default = null) {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->config)) {
                $option = $this->config[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }

    /**
     *
     */
    public function initializeTabs() {
        $this->modx->controller->addLexiconTopic('moddevtools:default');
        $this->modx->controller->addCss($this->config['cssUrl'] . 'mgr/main.css');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/moddevtools.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/misc/utils.js');

        $this->modx->controller->addHtml("
            <script type=\"text/javascript\">
            // <![CDATA[
			modDevTools.config = " . $this->modx->toJSON($this->config) . ";
            // ]]>
            </script>");

        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/elements.panel.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/chunks.panel.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/snippets.panel.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/resources.grid.js');
    }

    public function outputTemplateTab() {
        $this->modx->controller->addHtml("
            <script>
                Ext.onReady(function() {
                    " . ($this->config['viewChunk'] ? "modDevTools.utils.addTab('modx-template-tabs',{
                        title: _('chunks'),
                        id: 'moddevtools-template-chunks-tab',
                        width: '100%',
                        items: [{
                            xtype: 'moddevtools-panel-chunks',
                            link_type: 'temp-chunk',
                            intro: _('moddevtools_template_chunks_intro')
                        }]
                    });\n" : "") .
                    ($this->config['viewSnippet'] ? "modDevTools.utils.addTab('modx-template-tabs',{
                        title: _('snippets'),
                        id: 'moddevtools-template-snippets-tab',
                        width: '100%',
                        items: [{
                            xtype: 'moddevtools-panel-snippets',
                            link_type: 'temp-snip',
                            intro: _('moddevtools_template_snippets_intro')
                        }]
                    });\n" : "") .
                    ($this->config['viewResource'] ? "modDevTools.utils.addTab('modx-template-tabs',{
                        title: _('resources'),
                        id: 'moddevtools-template-resources-tab',
                        width: '100%',
                        items: [{
                            html: _('moddevtools_resource_template_intro'),
                            border: false,
                            cls: 'panel-desc',
                            width: '100%'
                        },{
                            baseCls: 'main-wrapper',
                            width: '100%',
                            layout: 'anchor',
                            items: [{
                                xtype: 'moddevtools-grid-resources',
                                link_type: 'res-temp',
                                anchor: '100%'
                            }]
                        }]
                    });\n" : "") .
"                });
            </script>");
    }

    public function outputChunkTab() {
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/templates.panel.js');
        $this->modx->controller->addHtml("
            <script>
                Ext.onReady(function() {
                    " . ($this->config['viewTemplate'] ? "modDevTools.utils.addTab('modx-chunk-tabs',{
                        title: _('templates'),
                        id: 'moddevtools-chunk-templates-tab',
                        width: '100%',
                        items: [{
                            xtype: 'moddevtools-panel-templates',
                            link_type: 'temp-chunk',
                            intro: _('moddevtools_chunk_templates_intro')
                        }]
                    });\n" : "") .
                    ($this->config['viewChunk'] ? "modDevTools.utils.addTab('modx-chunk-tabs',{
                        title: _('chunks'),
                        id: 'moddevtools-chunk-chunks-tab',
                        width: '100%',
                        items: [{
                            xtype: 'moddevtools-panel-chunks',
                            link_type: 'chunk-chunk',
                            intro: _('moddevtools_chunk_chunks_intro')
                        }]
                    });\n" : "") .
                    ($this->config['viewSnippet'] ? "modDevTools.utils.addTab('modx-chunk-tabs',{
                        title: _('snippets'),
                        id: 'moddevtools-chunk-snippets-tab',
                        width: '100%',
                        items: [{
                            xtype: 'moddevtools-panel-snippets',
                            link_type: 'chunk-snip',
                            intro: _('moddevtools_chunk_snippets_intro')
                        }]
                    });" : "") .
                    ($this->config['viewResource'] ? "modDevTools.utils.addTab('modx-chunk-tabs',{
                        title: _('resources'),
                        id: 'moddevtools-chunk-resources-tab',
                        width: '100%',
                        items: [{
                            html: _('moddevtools_resource_chunk_intro'),
                            border: false,
                            cls: 'panel-desc',
                            width: '100%'
                        },{
                            baseCls: 'main-wrapper',
                            width: '100%',
                            layout: 'anchor',
                            items: [{
                                xtype: 'moddevtools-grid-resources',
                                link_type: 'res-chunk',
                                anchor: '100%'
                            }]
                        }]
                    });\n" : "") .
"                });
            </script>");
    }

    public function outputSnippetTab() {
        $this->modx->controller->addHtml("
            <script>
                Ext.onReady(function() {
                    " . ($this->config['viewResource'] ? "modDevTools.utils.addTab('modx-snippet-tabs',{
                        title: _('resources'),
                        id: 'moddevtools-snippet-resources-tab',
                        width: '100%',
                        items: [{
                            html: _('moddevtools_resource_snippet_intro'),
                            border: false,
                            cls: 'panel-desc',
                            width: '100%'
                        },{
                            baseCls: 'main-wrapper',
                            width: '100%',
                            layout: 'anchor',
                            items: [{
                                xtype: 'moddevtools-grid-resources',
                                link_type: 'res-snip',
                                anchor: '100%'
                            }]
                        }]
                    });" : "") .
"                });
            </script>");
    }

    /**
     * Outputs the JavaScript needed to add a tab to the panels.
     *
     * @param string $class
     * @return bool
     */
    public function outputTab($class) {
        $this->initializeTabs();

        if ($class == 'Template') {
            $this->outputTemplateTab();
        } else if ($class == 'Chunk') {
            $this->outputChunkTab();
        } else if ($class == 'Snippet') {
            $this->outputSnippetTab();
        }
        return true;
    }

    /**
     * @param bool|string $link_type
     * @param bool|int $parent
     */
    public function clearLinks($link_type = false, $parent = false) {
        $c = $this->modx->newQuery('modDevToolsLink');
        if ($link_type) {
            $c->where(array(
                'link_type:LIKE' => $link_type . '-%'
            ));
        }

        if ($parent) {
            $c->where(array(
                'parent' => $parent
            ));
        }
        $links = $this->modx->getIterator('modDevToolsLink', $c);
        /**
         * @var modDevToolsLink $link
         */
        foreach ($links as $link) {
            $link->remove();
        }
    }

    /**
     * @param modAccessibleObject $object
     * @return bool|string
     */
    public function getLinkParentType($object) {
        if ($object instanceof modTemplate) {
            return 'temp';
        } else if ($object instanceof modChunk) {
            return 'chunk';
        } else if ($object instanceof modResource) {
            return 'res';
        } else {
            return false;
        }
    }

    /**
     * @param string $content
     * @param array $tags
     */
    public function findTags($content, &$tags) {
        $parser = $this->modx->getParser();
        $collectedTags = array();
        $parser->collectElementTags($content, $collectedTags);
        foreach ($collectedTags as $tag) {
            $tagName = $tag[1];
            if (substr($tagName,0,1) == '!') {
                $tagName = substr($tagName, 1);
            }

            $token = substr($tagName, 0, 1);

            $tagParts= xPDO::escSplit('?', $tagName, '`', 2);
            $tagName= trim($tagParts[0]);
            $tagPropString= null;

            $tagName = trim($this->modx->stripTags($tagName));
            if (in_array($token, array('$', '+', '~', '#', '%', '-', '*'))) {
                $tagName = substr($tagName, 1);
            }

            switch ($token) {
                case '$':
                    $class = 'modChunk';
                    break;
                case '+':
                case '~':
                case '#':
                case '%':
                case '-':
                case '*':
                    continue 2;
                    break;
                default:
                    $class = 'modSnippet';
                    break;
            }

            if (isset ($tagParts[1])) {
                $tagPropString = trim($tagParts[1]);
                $this->findTags($tagPropString, $tags);
                $element = $parser->getElement($class, $tagName);
                if ($element) {
                    $properties = $element->getProperties($tagPropString);
                } else {
                    $properties = array();
                }
            } else {
                $properties = array();
            }

            $this->debug('Found ' . $class . ' ' . $tagName . ' with properties ' . print_r($properties,1));

            $tagName = $parser->realname($tagName);
            if (empty($tagName)) {
                continue;
            }

            $tags[$tagName] = array(
                'name' => $tagName,
                'class' => $class,
            );

            foreach ($properties as $property) {
                $prop = trim($property);
                if (!empty($prop) && !is_numeric($prop) && is_string($prop)) {
                    $tags[$prop] = array(
                        'name' => $prop,
                        'class' => 'modChunk',
                        'isProperty' => true,
                    );
                }
            }
        }
    }

    /**
     * @param modAccessibleObject $object
     */
    public function parseContent(&$object) {
        $t = microtime(true);
        $objLink = $this->getLinkParentType($object);
        if ($objLink === false) {
            return;
        }

        $this->clearLinks($objLink, $object->get('id'));

        $tags = array();
        $this->findTags($object->get('content'), $tags);
        $this->debug('All found tags: ' . print_r($tags,1));
        foreach ($tags as $tag) {
            $this->findLink($object, $tag, $objLink);
        }
        $this->debug('Total time: ' . (microtime(true) - $t));
    }

    /**
     * @param xPDOObject $parent
     * @param string $tag
     * @param string $linkType
     */
    public function findLink($parent, $tag, $linkType) {
        if (isset($tag['class'], $tag['name'])) {
            switch ($tag['class']) {
                case 'modSnippet':
                    $type = 'snip';
                    break;
                case 'modChunk':
                    $type = 'chunk';
                    break;
                default:
                    return;
                    break;
            }
            /**
             * @var bool|xPDOObject $child
             */
            $child = $this->findObject($tag['class'], $tag['name']);
            if ($child !== false) {
                $this->createLink($parent, $child, $linkType . '-' . $type);
            }
        }
    }

    /**
     * @param $parent xPDOObject
     * @param $child xPDOObject
     * @param $linkType
     */
    public function createLink($parent, $child, $linkType) {
        $c = array(
            'parent' => $parent->get('id'),
            'child' => $child->get('id'),
            'link_type' => $linkType,
        );
        $link = $this->modx->getObject('modDevToolsLink', $c);

        if (!$link) {
            $this->debug('Try to create link with criteria ' . print_r($c,1));
            $link = $this->modx->newObject('modDevToolsLink', $c);
            $link->save();
        } else {
            $this->debug('Link is already exists with criteria ' . print_r($c,1));
        }
    }

    /**
     * @param string $class
     * @param string $name
     * @return bool|null|object
     */
    public function findObject($class, $name) {
        if (!empty($class) && !empty($name)) {
            $obj = $this->modx->getObject($class, array('name' => $name));
            if (!empty($obj)) {
                $this->debug('Object exists of class ' . $class);
                return $obj;
            } else {
                $this->debug('Object doesnt exist of class ' . $class);
                return false;
            }
        }
        return false;
    }


    /**
     * @param $config
     */
    public function getBreadCrumbs($config) {
        $mode = $this->modx->getOption('mode', $config);
        $resource = $this->modx->getOption('resource', $config);

        if (($mode === modSystemEvent::MODE_NEW) || !$resource) {
            if (!isset($_GET['parent'])) {return;}
            $resource = $this->modx->getObject('modResource', $_GET['parent']);
            if (!$resource) {return;}
        }
        $context = $resource->get('context_key');
        if ($context != 'web') {
            $this->modx->reloadContext($context);
        }

        /** @TODO Prepare as System Setting */
        $limit = 3;
        $resources = $this->modx->getParentIds($resource->get('id'), $limit, array( 'context' => $context ));


        if ($mode === modSystemEvent::MODE_NEW) {
            array_unshift($resources, $_GET['parent']);
        }

        $crumbs = array();
        $root = $this->modx->toJSON(array(
            'text' => $context,
            'className' => 'first',
            'root' => true,
            'url' => '?'
        ));

        $controllerConfig = $this->modx->controller->config;
        $action = $controllerConfig['controller'];

        if ($action == 'resource/create') {
            $action = 'resource/update';
        }

        if (isset($controllerConfig['id'])) {
            if ($controllerConfig['controller'] == 'resource/create') {
                $actionObj = $this->modx->getObject('modAction', array('controller' => 'resource/update'));
                $action = $actionObj->get('id');
            } else {
                $action = $controllerConfig['id'];
            }
        }

        $isAll = false;
        for ($i = count($resources)-1; $i >= 0; $i--) {
            $resId = $resources[$i];
            if ($resId == 0) {
                continue;
            }
            $parent = $this->modx->getObject('modResource', $resId);
            if (!$parent) {break;}
            if ($parent->get('parent') == 0) {
                $isAll = true;
            }

            $crumbs[] = array(
                'text' => $parent->get('pagetitle'),
			    'url' => '?a=' . $action . '&id=' . $parent->get('id')
            );
        }

        if ((count($resources) == $limit) && !$isAll) {
            array_unshift($crumbs, array(
                'text' => '...',
            ));
        }

        // Add pagetitle of current page
        if ($mode === modSystemEvent::MODE_NEW) {
            $pagetitle = $this->modx->lexicon('new_document');
        } else {
            $pagetitle = $resource->get('pagetitle');
        }
        $crumbs[] = array('text' => $pagetitle);

        $crumbs = $this->modx->toJSON($crumbs);

        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/moddevtools.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/breadcrumbs.panel.js');
        $this->modx->controller->addHtml("<script>
            Ext.onReady(function() {
                var header = Ext.getCmp('modx-resource-header').ownerCt;
                header.insert(1, {
                    xtype: 'moddevtools-breadcrumbs-panel'
                    ,id: 'resource-breadcrumbs'
                    ,desc: ''
                    ,root : {$root}
                });
                header.doLayout();

                var crumbCmp = Ext.getCmp('resource-breadcrumbs');
                var bd = { trail : {$crumbs}};
		        crumbCmp.updateDetail(bd);

		        Ext.getCmp('modx-resource-pagetitle').on('keyup', function(){
                    bd.trail[bd.trail.length-1] = {text: crumbCmp.getPagetitle()};
                    crumbCmp._updatePanel(bd);
                });
            });
            </script>"
        );
    }

    /**
     * @param $content
     * @param $search
     * @param int $offset
     * @return string
     */
    public function getSearchContent($content, $search, $offset = 0) {

        // clean content
        $content = str_replace(array("\r\n", "\r"), "\n", $content);

        $offsetString = substr($content, 0, $offset);
        $lineLeftString = '';

        if ($offset) {
            $this->config['highlightCount'] = 1;
            $pattern = '/' . preg_quote($search, '/') . '/' . $this->config['pcreModifier'];
            $count = preg_match_all($pattern, $content);

            if ($count > 1) {
                $linesLeft = $this->mb_strrposnth(mb_substr($offsetString, 0, $offset), "\n", $this->config['extractLines'] / 2);
                $lineLeftString = (($linesLeft) ? $this->config['extractEllips'] . "\n" : '') . ltrim(mb_substr($offsetString, $linesLeft), "\n");
                $lineLeftString = preg_replace_callback($pattern, array($this, 'highlight'), $lineLeftString);
            } else {
                $offset = 0;
            }
        }

        return '<pre>' . $lineLeftString . $this->getExtract(substr($content, $offset), $search) . '</pre>';
    }

    /**
     * Returns extracts with highlighted searchterms
     *
     * @param string $text
     * @param string $searchString
     * @return string
     */
    function getExtract($text, $searchString) {
        $output = '';

        if (($text !== '') && ($searchString !== '')) {
            $extracts = array();

            $textLength = mb_strlen($text);
            $wordLength = mb_strlen($searchString);

            $pattern = '/' . preg_quote($searchString, '/') . '/' . $this->config['pcreModifier'];

            // Collect matches
            $matches = array();
            $nbr = preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);
            for ($i = 0; $i < $nbr && $i < $this->config['extractQuantity']; $i++) {
                $wordLeft = mb_strlen(mb_substr($text, 0, $matches[0][$i][1]));
                $wordRight = $wordLeft + $wordLength - 1;

                $linesLeft = $this->mb_strrposnth(mb_substr($text, 0, $wordLeft), "\n", $this->config['extractLines'] / 2);
                $left = ($linesLeft !== false) ? $linesLeft + 1 : 0;
                $left = ($left < 0) ? 0 : $left;
                $linesRight = $this->mb_strposnth(mb_substr($text, $wordRight), "\n", $this->config['extractLines'] / 2);
                $right = ($linesRight !== false) ? $wordRight + $linesRight - 1 : $textLength;
                $right = ($right > $textLength) ? $textLength : $right;
                $extracts[] = array('word' => $searchString,
                    'wordLeft' => $wordLeft,
                    'wordRight' => $wordRight,
                    'left' => $left,
                    'right' => $right,
                    'etcLeft' => ($left == 0) ? '' : $this->config['extractEllips'] . "\n",
                    'etcRight' => ($right == $textLength) ? '' : "\n". $this->config['extractEllips']
                );
            }

            // Join overlapping extracts
            $nbExtr = count($extracts);
            if ($nbExtr > 1) {
                for ($i = 0; $i < $nbExtr; $i++) {
                    $lft[$i] = $extracts[$i]['left'];
                    $rght[$i] = $extracts[$i]['right'];
                }
                array_multisort($lft, SORT_ASC, $rght, SORT_ASC, $extracts);
                for ($i = 0; $i < $nbExtr; $i++) {
                    $begin = mb_substr($text, 0, $extracts[$i]['left']);
                    if ($begin != '') {
                        $extracts[$i]['left'] = intval(mb_strrpos($begin, ' '));
                    }
                    $end = mb_substr($text, $extracts[$i]['right'] + 1, $textLength - $extracts[$i]['right']);
                    if ($end != '') {
                        $dr = intval(mb_strpos($end, ' '));
                        $extracts[$i]['right'] += $dr + 1;
                    }
                }
                for ($i = 1; $i < $nbExtr; $i++) {
                    if ($extracts[$i]['left'] < $extracts[$i - 1]['wordRight']) {
                        $extracts[$i - 1]['right'] = $extracts[$i - 1]['wordRight'];
                        $extracts[$i]['left'] = $extracts[$i - 1]['right'] + 1;
                        $extracts[$i - 1]['etcRight'] = $extracts[$i]['etcLeft'] = '';
                    } else if ($extracts[$i]['left'] < $extracts[$i - 1]['right']) {
                        $extracts[$i - 1]['right'] = $extracts[$i]['left'];
                        $extracts[$i - 1]['etcRight'] = $extracts[$i]['etcLeft'] = '';
                    }
                }
            }

            // Highlight extracts
            for ($i = 0; $i < $nbExtr; $i++) {
                $this->config['highlightCount'] = 0;
                $separation = ($extracts[$i]['etcRight'] != '') ? $this->config['extractSeparator'] : '';

                $extract = mb_substr($text, $extracts[$i]['left'], $extracts[$i]['right'] - $extracts[$i]['left'] + 1);
                $extract = preg_replace_callback($pattern, array($this, 'highlight'), $extract);

                $output .= $extracts[$i]['etcLeft'] . $extract . $extracts[$i]['etcRight'] . $separation;
            }
        }
        return $output;
    }

    /**
     * @param array $matches
     * @return string
     */
    private function highlight(array $matches) {
        $class = ($this->config['highlightCount'] == 0) ? 'first-string' : 'found-string';
        $this->config['highlightCount']++;
        return '<span class="' . $class . '">' . $matches[0] . '</span>';
    }

    /**
     * @param string $message
     */
    public function debug($message) {
        if ($this->config['debug']) {
            if ($message instanceof xPDOObject) {
                $message = $message->toArray();
            }

            if (is_array($message)) {
                $message = print_r($message,1);
            }

            $this->modx->log(modX::LOG_LEVEL_ERROR, $message);
        }
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @param int $nth
     * @return bool|int
     */
    function mb_strposnth($haystack, $needle, $nth = 1) {
        $count = mb_substr_count($haystack, $needle);
        if ($count < 1 || $nth > $count) {
            return false;
        }
        for ($i = 0, $pos = 0, $len = 0; $i < $nth; $i++) {
            $pos = mb_strpos($haystack, $needle, $pos + $len);
            if ($i == 0){
                $len = mb_strlen($needle);
            }
        }
        return $pos;
    }

    /**
     * @param string $haystack
     * @param string $needle
     * @param int $nth
     * @return bool|int
     */
    function mb_strrposnth($haystack, $needle, $nth = 1) {
        $count = mb_substr_count($haystack, $needle);
        if ($count < 1 || $nth > $count) {
            return false;
        }
        for ($i = 0, $pos = $hlen = mb_strlen($haystack), $len = 0; $i < $nth; $i++) {
            $test = $hlen - ($pos - $len);
            $pos = mb_strrpos($haystack, $needle, -$test);
            if ($i == 0) {
                $len = mb_strlen($needle);
            }
        }
        return $pos;
    }

}
