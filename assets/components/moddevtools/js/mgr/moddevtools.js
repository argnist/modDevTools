var modDevTools = function(config) {
	config = config || {};
	modDevTools.superclass.constructor.call(this,config);
};
Ext.extend(modDevTools,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},view: {},utils: {}
});
Ext.reg('moddevtools',modDevTools);

modDevTools = new modDevTools();
if (typeof modDevTools.modx23 == 'undefined') {
    modDevTools.modx23 = typeof MODx.config.connector_url != 'undefined' ? true : false;
}
