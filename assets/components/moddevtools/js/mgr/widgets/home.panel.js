modDevTools.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		baseCls: 'modx-formpanel',
        layout: 'anchor',
		items: [{
			html: '<h2>'+_('moddevtools')+'</h2>',
			border: false,
			cls: 'modx-page-header container'
		},{
			xtype: 'modx-tabs',
			bodyStyle: 'padding: 10px',
			defaults: { border: false, autoHeight: true },
			border: true,
			activeItem: 0,
			hideMode: 'offsets',
			items: [{
				title: _('moddevtools_items'),
				items: [{
					html: _('moddevtools_intro_msg')
					,border: false
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				},{
					xtype: 'moddevtools-grid-items',
					preventRender: true
				}]
			}]
		}]
	});
	modDevTools.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(modDevTools.panel.Home, MODx.Panel);
Ext.reg('moddevtools-panel-home', modDevTools.panel.Home);
