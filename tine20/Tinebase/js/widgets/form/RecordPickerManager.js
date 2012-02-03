/*
 * Tine 2.0
 * 
 * @package     Tinebase
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Alexander Stintzing <alex@stintzing.net>
 * @copyright   Copyright (c) 2009-2011 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 */

Ext.ns('Tine.widgets.form');
 
 /**
 * @namespace   Tine.widgets.form
 * @class       Tine.widgets.form.RecordPickerManager
 * @author      Alexander Stintzing <a.stintzing@metaways.de>
 */

Tine.widgets.form.RecordPickerManager = {

    items: {},
    
    /**
     * returns a registered recordpicker or creates the default one
     * @param {String/Tinebase.Application} appName      expands to recordClass: Tine[appName].Model[modelName],
     * @param {String/Tinebase.data.Record} modelName               recordProxy: Tine[appName][modelName.toLowerCase() + 'Backend'])
     * @param {Object} config       additional Configuration
     * @return {Object} recordpicker
     */
    get: function(appName, modelName, config) {
        try {
            if(!config) var config = {};

            if (!appName) {
                Tine.log.debug('Tine.widgets.form.RecordPickerManager::get - No appName given!');
                return {};
            }

            if (!modelName) {
                Tine.log.debug('Tine.widgets.form.RecordPickerManager::get - No modelName given!');
                return {};
            }            
            
            if(Ext.isObject(appName)) {
                appName = appName.name;
            }
            Tine.log.err('app',appName);
            if(Ext.isObject(modelName)) {
                modelName = modelName.getMeta('modelName');
            }
            
            var key = appName+modelName;
            
            if(this.items[key]) {   // if registered
                if(Ext.isString(this.items[key])) { // xtype
                    return Ext.ComponentMgr.create(config, this.items[key]);
                } else { 
                    return new this.items[key](config);   
                }
            } else {    // not registered, create default
                var defaultconfig = {
                    recordClass: Tine[appName].Model[modelName],
                    recordProxy: Tine[appName][modelName.toLowerCase() + 'Backend'],
                    loadingText: _('Searching...')
                };
                Ext.apply(defaultconfig, config);
                return new Tine.Tinebase.widgets.form.RecordPickerComboBox(defaultconfig);
            }
        } catch(e) {
            Tine.log.error('Tine.widgets.form.RecordPickerManager::get');
            Tine.log.error(e.stack ? e.stack : e);
        }
    },
    
    /**
     * Registers a component
     * @param {String} appName          the application registered for
     * @param {String} modelName        the registered model name
     * @param {String/Object} component the component or xtype to register 
     */
    register: function(appName, modelName, component) {
        try {
            
            if (!appName) {
                Tine.log.debug('Tine.widgets.form.RecordPickerManager::register - No appName given!');
                return {};
            }

            if (!modelName) {
                Tine.log.debug('Tine.widgets.form.RecordPickerManager::register - No modelName given!');
                return {};
            }            
            
            if(Ext.isObject(appName)) {
                appName = appName.name;
            }
            if(Ext.isObject(modelName)) {
                modelName = modelName.getMeta('modelName');
            }
            
            var key = appName+modelName;
            if(!this.items[key]) {
                Tine.log.debug('RecordPickerManager::registerItem: ' + appName + modelName);
                this.items[key] = component;
            }
         } catch(e) {
            Tine.log.error('Tine.widgets.form.RecordPickerManager::register');
            Tine.log.error(e.stack ? e.stack : e);
        }
    }

};