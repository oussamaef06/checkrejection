<?php

include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

class modChequeRejection extends DolibarrModules
{
    function __construct($db)
    {
        global $langs, $conf;

        $this->db = $db;
        $this->numero = 105000;
        $this->rights_class = 'chequerejection';
        $this->family = "financial";
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Module for cheque rejection with reason.";
        $this->version = '1.0';
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        $this->picto = 'chequerejection@chequerejection';
        $this->module_parts = array('hooks' => array('compta/paiement/cheque'));
        $this->dirs = array();

        $this->langfiles = array("chequerejection@chequerejection");

        $this->rights = array();
        $r = 0;
        $this->rights[$r][0] = 104001;
        $this->rights[$r][1] = 'Read chequerejection';
        $this->rights[$r][2] = 'r';
        $this->rights[$r][3] = 0;
        $this->rights[$r][4] = 'read';
        $r++;
        $this->rights[$r][0] = 104002;
        $this->rights[$r][1] = 'Write chequerejection';
        $this->rights[$r][2] = 'w';
        $this->rights[$r][3] = 0;
        $this->rights[$r][4] = 'write';

        $this->menu = array();
        $r = 0;
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=compta',
            'type' => 'top',
            'titre' => 'Cheque Rejection',
            'mainmenu' => 'chequerejection',
            'leftmenu' => 'chequerejection_top',
            'url' => '/custom/chequerejection/page.php',
            'langs' => 'chequerejection@chequerejection',
            'position' => 50020,
            'enabled' => '$conf->chequerejection->enabled',
            'perms' => '1',
            'target' => '',
            'user' => 2
        );
        $r++;
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
