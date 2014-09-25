MODx.on("ready",function() {
    MODx.addTab("modx-template-tabs",{
        title: _('chunks'),
        id: 'moddevtools-template-chunks-tab',
        width: '100%',
        link_type: 'temp-chunk',
        items: [{
            xtype: 'moddevtools-panel-chunks'
        }]
    });
    MODx.addTab("modx-template-tabs",{
        title: _('snippets'),
        id: 'moddevtools-template-snippets-tab',
        width: '100%',
        link_type: 'temp-snip',
        items: [{
            xtype: 'moddevtools-panel-snippets'
        }]
    });
    MODx.addTab("modx-template-tabs",{
        title: _('resources'),
        id: 'moddevtools-template-resources-tab',
        width: '100%',
        items: [{
            xtype: 'moddevtools-grid-resources',
            width: '100%'
        }]
    });
});

