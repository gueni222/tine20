<?php
/**
 * expands records based on provided definition
 *
 * @package     Tinebase
 * @subpackage  Record
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Paul Mehrer <p.mehrer@metaways.de>
 * @copyright   Copyright (c) 2020 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 */

use Tinebase_ModelConfiguration_Const as MCC;

class Tinebase_Record_Expander_RefIdProperty extends Tinebase_Record_Expander_Property
{
    protected $_mccCfg = null;
    protected $_singleRecord = false;

    public function __construct($_model, $_property, $_expanderDefinition, $_rootExpander,
                                $_prio = self::DATA_FETCH_PRIO_DEPENDENTRECORD, $_singleRecord = false)
    {
        $this->_mccCfg = $_expanderDefinition['fieldDefConfig'];
        $this->_singleRecord = $_singleRecord;

        parent::__construct($_model, $_property, $_expanderDefinition, $_rootExpander, $_prio);
    }

    // this is against the whole concept of expanders :-/ what can we do?!
    protected function _lookForDataToFetch(Tinebase_Record_RecordSet $_records)
    {
        $dataFound = new Tinebase_Record_RecordSet($this->_model);
        /** @var Tinebase_Controller_Record_Abstract $ctrl */
        $ctrl = Tinebase_Core::getApplicationInstance($this->_model, '', true);
        $filter = [['field' => $this->_mccCfg[MCC::REF_ID_FIELD], 'operator' => 'equals', 'value' => null]];
        if (isset($this->_mccCfg[MCC::ADD_FILTERS])) {
            $filter = array_merge($filter, $this->_mccCfg[MCC::ADD_FILTERS]);
        }
        foreach ($_records as $record) {
            $filter[0]['value'] = $record->getId();
            if ($this->_singleRecord) {
                if (!$record->{$this->_property} instanceof Tinebase_Record_Interface) {
                    $record->{$this->_property} = $ctrl->search(
                        Tinebase_Model_Filter_FilterGroup::getFilterForModel($this->_model, $filter),
                        isset($this->_mccCfg[MCC::PAGING]) ? new Tinebase_Model_Pagination($this->_mccCfg[MCC::PAGING]) :
                            null)->getFirstRecord();
                }
                if ($record->{$this->_property} instanceof Tinebase_Record_Interface) {
                    $dataFound->addRecord($record->{$this->_property});
                }
            } else {
                if (!$record->{$this->_property} instanceof Tinebase_Record_RecordSet) {
                    $record->{$this->_property} = $ctrl->search(
                        Tinebase_Model_Filter_FilterGroup::getFilterForModel($this->_model, $filter),
                        isset($this->_mccCfg[MCC::PAGING]) ? new Tinebase_Model_Pagination($this->_mccCfg[MCC::PAGING]) :
                            null);
                }
                $dataFound->mergeById($record->{$this->_property});
            }
        }

        if ($dataFound->count() > 0) {
            $this->expand($dataFound);
        }
    }

    protected function _setData(Tinebase_Record_RecordSet $_data)
    {
    }
}
