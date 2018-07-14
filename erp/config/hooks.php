<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// Check db connection for installation 
$hook['post_controller_constructor'] = array(
        'class'    => 'erp_hooks',
        'function' => 'check',
        'filename' => 'erp_hooks.php',
        'filepath' => 'hooks'
);

// Compress output
// $hook['display_override'] = array(
//     'class' => 'erp_hooks',
//     'function' => 'minify',
//     'filename' => 'erp_hooks.php',
//     'filepath' => 'hooks'
// );
