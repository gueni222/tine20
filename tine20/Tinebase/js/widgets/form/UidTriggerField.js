/* 
 * Tine 2.0
 * 
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Michael Spahn <m.spahn@metaways.de>
 * @copyright   Copyright (c) 2012 Metaways Infosystems GmbH (http://www.metaways.de)
 */

/*global Ext, Tine*/

Ext.ns('Tine.Tinebase.widgets.form');

/**
 * This is a TriggerField which generates a random UID on click
 * (UID generated by Tine.Tinebase.data.Record.generateUID())
 * 
 * @namespace   Tine.Tinebase.widgets.form
 * @author      Michael Spahn <m.spahn@metaways.de>
 * @class       Tine.Tinebase.widgets.form.UidTriggerField
 * @extends     Ext.form.TriggerField
 */
Tine.Tinebase.widgets.form.UidTriggerField = Ext.extend(Ext.form.TriggerField, {

    itemCls: 'tw-uidTriggerField',
    enableKeyEvents: true,

    /**
     * Overrides initComponent to reenable field if it is empty
     */
    initComponent: function () {
        this.on('keyup', function() {
            this.setHideTrigger(!!this.getValue())
        }, this);

        Tine.Tinebase.widgets.form.UidTriggerField.superclass.initComponent.call(this);
    },

    /**
     * Overrides setValue and shows trigger field if value is empty and hides if it is filled
     *
     * @return {Ext.form.Field} this
     * @param value
     */
    setValue: function (value) {
        this.setHideTrigger(this.getValue());
        return Tine.Tinebase.widgets.form.UidTriggerField.superclass.setValue.call(this, value);
    },

    /**
     * Fills field on click with a random, unique checksum and hides trigger button
     */
    onTriggerClick: function () {
        if (!this.getValue()) {
            this.setValue(Tine.Tinebase.data.Record.generateUID());
            this.setHideTrigger(true);
        }
    }
});

Ext.reg('tw-uidtriggerfield',Tine.Tinebase.widgets.form.UidTriggerField);