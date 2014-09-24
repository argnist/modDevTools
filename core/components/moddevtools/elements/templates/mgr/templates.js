
MODx.on("ready",function() {
    MODx.addTab("modx-template-tabs",{
        title: _('chunks'),
        id: 'moddevtools-template-chunks-tab',
        width: '95%',
        items: [{
            html: '<h1>' + _('moddevtools_intro_msg') + '</h1>'
            ,border: false
            ,bodyCssClass: 'panel-desc'
            ,bodyStyle: 'margin-bottom: 10px'
        },{
            xtype: Ext.ComponentMgr.types['modx-texteditor'] ? 'modx-texteditor' : 'textarea' //https://github.com/Fi1osof/modx-console/blob/master/manager/components/console/js/widgets/console.panel.js
        }]
    });
    MODx.addTab("modx-template-tabs",{
        title: _('snippets'),
        id: 'moddevtools-template-snippets-tab',
        width: '95%',
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
        width: '95%',
        items: [{
            html: _('moddevtools_intro_msg')
            ,border: false
            ,bodyCssClass: 'panel-desc'
            ,bodyStyle: 'margin-bottom: 10px'
        }]
    });
});

