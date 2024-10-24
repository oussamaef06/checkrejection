<?php

include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

class modChequeRejection extends DolibarrModules
{
    function __construct($db)
    {
        global $langs;
        $this->db = $db;

        $this->numero = 104000; 
        $this->rights_class = 'chequerejection';
        $this->family = "financial";
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Module for cheque rejection with reason.";
        $this->version = '1.0';
        $this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
        $this->picto='chequerejection@mymodule';
        $this->module_parts = array();

        // Data directories to create when module is enabled
        $this->dirs = array();

        // Config pages
        $this->config_page_url = array("chequerejection_setup.php@multicompany");

        $this->langfiles = array("chequerejection@mymodule");

        // Dependencies
        $this->depends = array();
        $this->requiredby = array();
        $this->phpmin = array(5, 3);
        $this->need_dolibarr_version = array(3, 0);

        // Constants
        $this->const = array();

        // Define boxes
        $this->boxes = array();

        // Define rights
        $this->rights = array();
        $r = 0;
        $this->rights[$r][0] = 104001;
        $this->rights[$r][1] = 'Read mymodule';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 0;
        $this->rights[$r][4] = 'read';
        $r++;

        // Define menus
        $this->menu = array();
        $r = 0;

        // Main menu entry
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=compta',
            'type' => 'top',
            'titre' => 'My Module',
            'mainmenu' => 'mymodule',
            'leftmenu' => 'mymodule_top',
            'url' => '/custom/mymodule/page.php',
            'langs' => 'mymodule@mymodule',
            'position' => 50020, // Ensure unique value
            'enabled' => '$conf->mymodule->enabled',
            'perms' => '1',
            'target' => '',
            'user' => 2
        );
        $r++;

        // Submenu entry
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=mymodule',
            'type' => 'left',
            'titre' => 'Cheque Rejection',
            'mainmenu' => 'mymodule',
            'leftmenu' => 'mymodule_rejection',
            'url' => '/custom/mymodule/subpage.php',
            'langs' => 'mymodule@mymodule',
            'position' => 50012, // Ensure unique value
            'enabled' => '$conf->mymodule->enabled',
            'perms' => '1',
            'target' => '',
            'user' => 2
        );
    }

    function init($options = '')
    {
        $sql = array();
        $sql[] = "ALTER TABLE llx_paiement ADD reason_rejet_cheque VARCHAR(255) NULL;";

        dol_syslog("modChequeRejection: init called");

        return $this->_load_tables('/modChequeRejection/sql/') && $this->_init($sql, $options);
    }

    function remove($options = '')
    {
        $sql = array();
        $sql[] = "ALTER TABLE llx_paiement DROP COLUMN reason_rejet_cheque;";

        dol_syslog("modChequeRejection: remove called");

        return $this->_remove($sql, $options);
    }
}
