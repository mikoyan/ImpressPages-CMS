<?php
/**
 * @package   ImpressPages
 *
 *
 */

namespace Modules\standard\menu_management;


if (!defined('CMS')) exit;

/**
 *
 * @author Mangirdas Skripka
 *
 */
class Remotes
{
    /**
     *
     * Here you can add remote websites that you like to add to your menu management tree.
     */
    static function getRemotes()
    {
        $remotes = array();

        //exmple
        //$remotes[] = array('url' => 'http://www.example.com', 'username' => '', 'password' => '');

        return $remotes;
    }


}