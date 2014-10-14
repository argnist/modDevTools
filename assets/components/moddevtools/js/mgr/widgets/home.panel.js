modDevTools.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('moddevtools') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('search'),
                layout: 'anchor',
                items: [{
                    html: _('moddevtools_search_desc'),
                    cls: 'panel-desc'
                },{
                    xtype: 'moddevtools-search-form'
                }]
            }]
        }]
    });
    modDevTools.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(modDevTools.panel.Home, MODx.Panel);
Ext.reg('moddevtools-panel-home', modDevTools.panel.Home);