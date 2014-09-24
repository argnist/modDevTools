var modDevTools = function(config) {
	config = config || {};
	modDevTools.superclass.constructor.call(this,config);
};
Ext.extend(modDevTools,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},view: {}
});
Ext.reg('moddevtools',modDevTools);

modDevTools = new modDevTools();
modDevTools.modx23 = typeof MODx.config.connector_url != 'undefined' ? true : false;