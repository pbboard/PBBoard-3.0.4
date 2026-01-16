<?php
/**
 * DATABASE TYPE
 *
 * Please see the PBBoard Docs for advanced
 * database configuration for larger installations
 * https://pbboard.info/
 */

$config['db']['name'] = '2026';
$config['db']['server'] = 'localhost';
$config['db']['username'] = 'root';
$config['db']['password'] = '';
$config['db']['prefix'] = 'pbb_';
$config['db']['dbtype'] = 'mysqli';


/**
 * Admin CP directory
 *  For security reasons, it is recommended you
 *  rename your Admin CP directory. You then need
 *  to adjust the value below to point to the
 *  new directory.
 */

$config['Misc']['admincpdir'] = 'admincp';

/**
 * To disable the plugin/hooks system
 */

$config['HOOKS']['DISABLE_HOOKS'] = '1';

/**
 * Super Administrators
 *  A comma separated list of user IDs who cannot
 *  be edited, deleted or banned in the Admin CP.
 *  The administrator permissions for these users
 *  cannot be altered either.
 */

$config['SpecialUsers']['superadministrators'] = '1';

/**
* The database encoding
*/

$config['db']['encoding'] = 'utf8mb4';

?>