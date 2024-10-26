<?php

class ActionsModulename
{
    public function addLeftMenu(&$menuEntries, $langs, $conf, $user, $bc)
    {
        // Check if the module is enabled
        if ($conf->modulename->enabled) {
            foreach ($menuEntries as &$entry) {
                // Check if the entry is for the deposit slips
                if ($entry['url'] == '/compta/paiement/cheque/index.php') {
                    $entry['url'] = '/custom/modulename/cheque/index.php';
                }
            }
        }

        return 1;
    }
}

