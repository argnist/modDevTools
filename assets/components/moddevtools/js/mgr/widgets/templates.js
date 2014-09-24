
MODx.on("ready",function() {
    MODx.addTab("modx-template-tabs",{
        title: _('chunks'),
        id: 'moddevtools-template-chunks-tab',
        width: '100%',
        items: [{
            xtype: 'moddevtools-panel-chunks'
        }]
    });
    MODx.addTab("modx-template-tabs",{
        title: _('snippets'),
        id: 'moddevtools-template-snippets-tab',
        width: '100%',
        items: [{
            html: _('moddevtools_intro_msg')
            ,border: false
            ,bodyCssClass: 'panel-desc'
            ,bodyStyle: 'margin-bottom: 10px'
        }]
    });
    MODx.addTab("modx-template-tabs",{
        title: _('resources'),
        id: 'moddevtools-template-resources-tab',
        width: '100%',
        items: [{
            html: _('moddevtools_intro_msg')
            ,border: false
            ,bodyCssClass: 'panel-desc'
            ,bodyStyle: 'margin-bottom: 10px'
        }]
    });
});

