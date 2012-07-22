<?php
/**
 * Syncroton
 *
 * @package     Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Sync command
 *
 * @package     Model
 */

class Syncroton_Data_Tasks extends Syncroton_Data_AData
{
    public function appendXML(DOMElement $_domParrent, $_collectionData, $_serverId)
    {
        $_domParrent->ownerDocument->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:Tasks', 'uri:Tasks');
    }
    
    protected function _initData()
    {
        /**
        * used by unit tests only to simulated added folders
        */
        Syncroton_Data_AData::$folders[get_class($this)] = array(
            'tasksFolderId' => array(
                'folderId'    => 'tasksFolderId',
                'parentId'    => null,
                'displayName' => 'Default Tasks Folder',
                'type'        => Syncroton_Command_FolderSync::FOLDERTYPE_TASK
            )
        );
        
        /**
         * used by unit tests only to simulated added folders
         */
        Syncroton_Data_AData::$entries[get_class($this)] = array(
            'tasksFolderId' => array(
            )
        );
    }
}

