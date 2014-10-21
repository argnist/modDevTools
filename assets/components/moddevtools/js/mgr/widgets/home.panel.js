modDevTools.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        hideMode: 'offsets',
        cls: 'container',
        items: [{
            html: '<h2>' + _('moddevtools') + '</h2>',
            cls: 'modx-page-header',
            border: false,
            style: {margin: '18px 0'}
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
                    border: false,
                    bodyCssClass: 'panel-desc'
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