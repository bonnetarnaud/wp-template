<?php
/*
Plugin Name: debug symfony
Description: Ajoute le var_dumper de symfony
Version:     1.0
Author:      Arnaud Bonnet
Author URI:  https://arnaudbonnet.fr
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/


add_action('plugins_loaded', 'load_synfony_debug');
function load_synfony_debug() {

    /**
     * require symfony/var-dumper
     * @link https://symfony.com/doc/current/components/var_dumper.html
     */
    require __DIR__.'/vendor/autoload.php';
}