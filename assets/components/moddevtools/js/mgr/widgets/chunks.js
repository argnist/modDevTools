
MODx.on("ready",function() {
    MODx.addTab("modx-chunk-tabs",{
        title: _('chunks'),
        id: 'moddevtools-chunk-chunks-tab',
        width: '100%',
        link_type: 'chunk-chunk',
        items: [{
            xtype: 'moddevtools-panel-chunks'
        }]
    });
    MODx.addTab("modx-chunk-tabs",{
        title: _('snippets'),
        id: 'moddevtools-chunk-snippets-tab',
        width: '100%',
        link_type: 'chunk-snip',
        items: [{
            xtype: 'moddevtools-panel-snippets'
        }]
    });
});

