
MODx.on("ready",function() {
    var parent = Ext.getCmp('modx-panel-chunk').chunk;
    MODx.addTab("modx-chunk-tabs",{
        title: _('chunks'),
        id: 'moddevtools-chunk-chunks-tab',
        width: '100%',
        parent: parent,
        link_type: 'chunk-chunk',
        items: [{
            xtype: 'moddevtools-panel-chunks'
        }]
    });
    MODx.addTab("modx-chunk-tabs",{
        title: _('snippets'),
        id: 'moddevtools-chunk-snippets-tab',
        width: '100%',
        parent: parent,
        link_type: 'chunk-snip',
        items: [{
            xtype: 'moddevtools-panel-snippets'
        }]
    });
});

