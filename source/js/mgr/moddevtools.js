var moddevtools = function (config) {
    config = config || {};
    Ext.applyIf(config, {});
    moddevtools.superclass.constructor.call(this, config);
    return this;
};
Ext.extend(moddevtools, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, util: {}
});
Ext.reg('moddevtools', moddevtools);
modDevTools = new moddevtools();
