modDevTools.panel.SearchForm = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        cls: 'container form-with-labels',
        labelAlign: 'left',
        autoHeight: true,
        anchor: '100%',
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
        defaults: {border: false},
        items: [{
            layout: 'column',
            cls: 'x-toolbar',
            style: {
                backgroundColor: 'transparent'
            },
            defaults: {layout: 'form', cls: 'col-sm-4', border: false},
            items: [{
                columnWidth: 0.4,
                items: [{
                    xtype: 'textfield', cls: 'col-sm-4',
                    id: 'search-string',
                    fieldLabel: _('moddevtools_text_to_find'),
                    allowBlank: false,
                    anchor: '100%',
                    border: false,
                    labelStyle: 'font-size: 13px; line-height:20px; text-align: center'
                }]
            }, {
                columnWidth: 0.4,
                items: [{
                    xtype: 'textfield', cls: 'col-sm-4',
                    id: 'replace-string',
                    fieldLabel: _('moddevtools_replace_with'),
                    anchor: '100%',
                    border: false,
                    labelStyle: 'font-size: 13px; line-height:20px; text-align: center'
                }]
            }, {
                columnWidth: 0.2,
                items: [{
                    xtype: 'button', cls: 'col-sm-4',
                    align: 'left',
                    text: _('moddevtools_find'),
                    handler: this.submit,
                    scope: this,
                    anchor: '100%',
                    border: false
                }]
            }]
        }, {
            id: 'filter-group',
            xtype: 'fieldset',
            title: _('moddevtools_search_filters'),
            layout: 'auto',
            defaults: {
                style: {width: 'auto', float: 'left', marginRight: '25px'},
                border: false
            },
            items: [{
                items: {
                    xtype: 'xcheckbox'
                    ,name: 'filters[modChunk]'
                    ,id: 'moddevtools-search-chunks'
                    ,boxLabel: _('chunks')
                    ,inputValue: 1
                    ,checked: true,
                    border: false
                }
            }, {
                items: {
                    xtype: 'xcheckbox'
                    ,name: 'filters[modTemplate]'
                    ,id: 'moddevtools-search-templates'
                    ,boxLabel: _('templates')
                    ,inputValue: 1
                    ,checked: true,
                    border: false
                }
            } ]
        }, {
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
                            headerCfg: {
                                cls: 'x-panel-header devtools-el-header'
                            },
                            items: [{
                                id: 'found-element-' + i,
                                xtype: 'displayfield',
                                value: foundItems[i].content,
                                height: 'auto',
                                cls: 'devtools-search-code'
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
                } else {
                    results.add({
                        html: '<h3>' + _('moddevtools_notfound') + '</h3>',
                        style: {
                            margin: '10px 0'
                        }
                    });
                }
                results.doLayout();
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