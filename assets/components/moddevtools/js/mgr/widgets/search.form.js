modDevTools.panel.SearchForm = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        cls: 'container form-with-labels',
        labelAlign: 'left',
        autoHeight: true,
        layout: 'form',
        saveMsg: _('search'),
        url: modDevTools.config.connector_url,
        errorReader: new Ext.data.JsonReader({
            totalProperty: 'total'
            ,root: 'results'
            ,fields: ['id', 'name', 'class', 'content']
        }),
        baseParams: {
            action: 'mgr/search/getlist'
        },
        items: [{
            xtype: 'textfield',
            id: 'search-string',
            fieldLabel: _('moddevtools_text_to_find'),
            allowBlank: false
        },{
            xtype: 'textfield',
            id: 'replace-string',
            fieldLabel: _('moddevtools_replace_with')
        },{
            xtype: 'button',
            align: 'left',
            text: _('moddevtools_find'),
            handler: this.submit,
            scope: this
        },{
            id: 'moddevtools-search-results'
        }],
        listeners: {
            success: {fn: function(response) {
                var results = Ext.getCmp('moddevtools-search-results');
                results.removeAll();

                if (response.result.success && response.result.errors) {
                    var foundItems = response.result.errors;
                    this.records = foundItems;

                    for (var i = 0; i < foundItems.length; i++) {
                        var item = {
                            xtype: 'panel',
                            title: foundItems[i].class + ' ' + foundItems[i].name + ' (' + foundItems[i].id + ')',
                            items: [{
                                id: 'found-element-' + i,
                                xtype: 'displayfield',
                                value: foundItems[i].content,
                                width: '100%',
                                height: 'auto'
                            },{
                                xtype: 'button',
                                text: _('moddevtools_replace'),
                                record: i,
                                handler: function(b) {
                                    this.replace(b, 0, 0);
                                },
                                scope: this
                            },{
                                xtype: 'button',
                                text: _('moddevtools_replace_all'),
                                record: i,
                                handler: function(b) {
                                    this.replace(b, 1, 0);
                                },
                                scope: this
                            },{
                                xtype: 'button',
                                text: _('moddevtools_skip'),
                                record: i,
                                handler: function(b) {
                                    this.replace(b, 0, 1);
                                },
                                scope: this
                            }]
                        }
                        results.add(item);
                    }
                    results.doLayout();
                }

            },scope: this}

        }
    });
    modDevTools.panel.SearchForm.superclass.constructor.call(this,config);
};
Ext.extend(modDevTools.panel.SearchForm,MODx.FormPanel,{
    replace: function(btn, all, skip) {
        var record = this.records[btn.record];
        var form = this.getForm();
        MODx.Ajax.request({
            url: modDevTools.config.connector_url,
            params: {
                id: record.id,
                class: record.class,
                action: 'mgr/search/replace',
                offset: all ? 0 : record.offset,
                search: form.findField('search-string').getValue(),
                replace: form.findField(skip ? 'search-string' : 'replace-string').getValue(),
                all: all
            },
            listeners: {
                'success': {fn:function(r) {
                    if (r.success && (typeof r.object !== 'undefined')) {
                        var element = Ext.getCmp('found-element-' + btn.record);
                        element.setValue(r.object.content);
                        this.records[btn.record] = r.object;
                    }
                },scope:this}
            }
        });
    }
});
Ext.reg('moddevtools-search-form',modDevTools.panel.SearchForm);