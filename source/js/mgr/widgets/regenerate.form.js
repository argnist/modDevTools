modDevTools.panel.RegenerateForm = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'moddevtools-panel-regenerateform',
        cls: 'container form-with-labels',
        labelAlign: 'left',
        autoHeight: true,
        anchor: '100%',
        url: modDevTools.config.connectorUrl,
        baseParams: {
            action: 'mgr/tables/regenerate',
            register: 'mgr',
            topic: '/regenerate/'
        },
        items: [{
            id: 'regenerate-group',
            xtype: 'fieldset',
            cls: 'moddevtools-fieldset',
            title: _('moddevtools_regenerate_filters'),
            layout: 'auto',
            defaults: {
                style: {width: 'auto', float: 'left', marginRight: '25px'},
                border: false
            },
            items: [{
                items: {
                    xtype: 'checkbox',
                    name: 'filters[]',
                    inputValue: 'modChunk',
                    id: 'moddevtools-search-chunks',
                    boxLabel: _('chunks'),
                    checked: true,
                    border: false
                }
            }, {
                items: {
                    xtype: 'checkbox',
                    name: 'filters[]',
                    inputValue: 'modTemplate',
                    id: 'moddevtools-search-templates',
                    boxLabel: _('templates'),
                    checked: true,
                    border: false
                }
            }, {
                items: {
                    xtype: 'checkbox',
                    name: 'filters[]',
                    inputValue: 'modResource',
                    id: 'moddevtools-search-resources',
                    boxLabel: _('resources'),
                    checked: true,
                    border: false
                }
            }]
        }],
        buttons: [{
            cls: 'primary-button',
            text: _('moddevtools_regenerate'),
            scope: this,
            handler: function () {
                var form = Ext.getCmp('moddevtools-panel-regenerateform').getForm();
                if (this.console === null || this.console === undefined || this.console.isDestroyed) {
                    this.console = MODx.load({
                        xtype: 'modx-console',
                        register: 'mgr',
                        topic: '/regenerate/'
                    });
                } else {
                    this.console.setRegister('mgr', '/regenerate/');
                }
                this.console.show(Ext.getBody());
                form.submit({
                    success: {
                        fn: function () {
                            this.console.fireEvent('complete');
                        },
                        scope: this
                    }
                });
            }
        }]
    });
    modDevTools.panel.RegenerateForm.superclass.constructor.call(this, config);
};
Ext.extend(modDevTools.panel.RegenerateForm, MODx.FormPanel, {});
Ext.reg('moddevtools-regenerate-form', modDevTools.panel.RegenerateForm);