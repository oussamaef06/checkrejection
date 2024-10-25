<?php
include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

class modchequerejection extends DolibarrModules {
    function __construct($db) {
        global $langs;
        $this->db = $db;
        $this->numero = 104000; 
        $this->rights_class = 'chequerejection';
        $this->family = "financial";
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        $this->description = "Module for cheque rejection with reason.";
        $this->version = '1.0';
        $this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
        $this->picto='chequerejection@chequerejection';
        $this->module_parts = array();
        $this->dirs = array('/chequerejection');
        $this->config_page_url = array("chequerejection_setup.php@chequerejection");
        $this->langfiles = array("chequerejection@chequerejection");
        $this->depends = array(); 
        $this->requiredby = array();
        $this->conflictwith = array();
        $this->phpmin = array(5, 3);
        $this->need_dolibarr_version = array(3, 0);

        // Constants
        $this->const = array();

        // Define rights
        $this->rights = array();
        $r = 0;
        $this->rights[$r][0] = 104001;
        $this->rights[$r][1] = 'Read chequerejection';
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
            'titre' => 'Cheque Rejection',
            'mainmenu' => 'chequerejection',
            'leftmenu' => 'chequerejection_top',
            'url' => '/custom/chequerejection/page.php',
            'langs' => 'chequerejection@chequerejection',
            'position' => 50010,
            'enabled' => '$conf->chequerejection->enabled',
            'perms' => '1',
            'target' => '',
            'user' => 2
        );
        $r++;
        // Submenu entry
        $this->menu[$r] = array(
            'fk_menu' => 'fk_mainmenu=chequerejection',
            'type' => 'left',
            'titre' => 'Cheque Rejection',
            'mainmenu' => 'chequerejection',
            'leftmenu' => 'chequerejection_rejection',
            'url' => '/custom/chequerejection/subpage.php',
            'langs' => 'chequerejection@chequerejection',
            'position' => 50011,
            'enabled' => '$conf->chequerejection->enabled',
            'perms' => '1',
            'target' => '',
            'user' => 2
        );
    }

    function init($options = '') {
        $sql = array();

        // Add reason_rejet_cheque column if not exists
        $result = $this->db->query("SHOW COLUMNS FROM llx_paiement LIKE 'reason_rejet_cheque'");
        if ($this->db->num_rows($result) == 0) {
            $sql[] = "ALTER TABLE llx_paiement ADD reason_rejet_cheque TEXT;";
        }

        // Hide original cheque folder
        $originalChequeFolder = DOL_DOCUMENT_ROOT . '/compta/paiement/cheque';
        $backupChequeFolder = DOL_DOCUMENT_ROOT . '/compta/paiement/cheque_backup';
        if (!file_exists($backupChequeFolder)) {
            rename($originalChequeFolder, $backupChequeFolder);
        }

        // Link custom cheque folder
        symlink(DOL_DOCUMENT_ROOT . '/custom/chequerejection/cheque', $originalChequeFolder);

        return $this->_init($sql, $options);
    }

    function remove($options = '') {
        $sql = array();
        $sql[] = "ALTER TABLE llx_paiement DROP COLUMN reason_rejet_cheque;";

        // Remove the custom cheque folder link
        $originalChequeFolder = DOL_DOCUMENT_ROOT . '/compta/paiement/cheque';
        $backupChequeFolder = DOL_DOCUMENT_ROOT . '/compta/paiement/cheque_backup';
        if (is_link($originalChequeFolder)) {
            unlink($originalChequeFolder);
        }

        // Restore the original cheque folder
        if (file_exists($backupChequeFolder)) {
            rename($backupChequeFolder, $originalChequeFolder);
        }

        return $this->_remove($sql, $options);
    }
}

