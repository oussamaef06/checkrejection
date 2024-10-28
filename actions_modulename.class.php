<?php

class ActionsModulename
{
    public function addLeftMenu(&$menuEntries, $langs, $conf, $user, $bc)
    {
        if ($conf->modulename->enabled) {
            foreach ($menuEntries as &$entry) {
                if ($entry['url'] == '/compta/paiement/cheque/index.php?leftmenu=checks&mainmenu=bank') {
                    $entry['url'] = '/custom/modulename/cheque/index.php?leftmenu=checks&mainmenu=bank';
                }
            }
        }
        return 1;
    }
}
?>
