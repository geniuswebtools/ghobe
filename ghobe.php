<?php

/**
 * Copyright 2013 ghobe' Brute Force Blocker (email : marion.dorsett@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, version 3, as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51 Franklin
 * St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/**
 * @package ghobe' Brute Force Blocker
 * @author Marion Dorsett <marion.dorsett@gmail.com>
 * @license http://www.gnu.org/licenses/licenses.html GPL3
 *
 * ghobe' translated from Kligon is `No`.
 *
 * The purpose of this file is to make a valid attempt to stop brute force login
 * attacks against your WordPress installation.
 *
 * If you have not yet installed the WordFence plugin by Mark Maunder, you should
 * do that as well:
 *
 * http://www.wordfence.com/
 *
 * The WordFence plugin will provide you with much better protection, and it will
 * tell you the `brutes` trying to hack their way in to your WordPress setup.
 *
 *
 * Directions:
 *
 * Place this file in the same folder as your wp-login.php file, and add the
 * following rewrite rule to your htaccess file:
 *

    # Protect from brute force attack
    RewriteCond %{REQUEST_FILENAME} ^.*(wp-login\.php).*$
    RewriteRule . /ghobe.php [L]

 * You may need to adjust the path to the ghobe.php file to resolve the path
 * correctly for you server setup.
 *
 */
class ghobe
{
  private
    $brutes,
    $bullies,
    $brute,
    $admin,
    $HIja;

  const
    Version = 0.1,
    PoweredBy = "ghobe' Brute Force Blocker";

  public function __construct($brutes = null, $header = true)
  {
    $this->poweredby($header);

    $this->HIja = false;
    $this->bullies = 'adm aaa developer manager qwerty root support test user';
    $this->brutes = array_flip(array_unique(explode(' ', trim($this->bullies . ' ' . $brutes))));

    $this->findbrute();
    $this->checkadmin();
    $this->_HIja();
  } // end __construct

  public function HIja()
  {
    return $this->HIja;
  } // end HIja

  private function findbrute()
  {
    $brute = null;
    if(isset($_REQUEST['log'])) { $brute = $_REQUEST['log']; } // end if
    elseif(isset($_POST['log'])) { $brute = $_POST['log']; } // end if
    elseif(isset($_GET['log'])) { $brute = $_GET['log']; } // end if

    $this->brute = $brute;
  } // end findbrute

  private function checkadmin()
  {
    $admin = trim($_REQUEST['log'] . $_POST['log'] . $_GET['log']);
    $this->admin = (strstr($admin, 'admin')) ? true : false;
  } // end checkadmin

  private function _HIja()
  {
    if( ($this->admin === true) || (array_key_exists($this->brute, $this->brutes)) )
    {
      // Access Denied
      $this->poweredby();
      header('HTTP/1.1 403 Forbidden');
      exit;
    } // end if

    $this->HIja = true;
  } // end _HIja

  private function poweredby($expose = true)
  {
    if($expose === false) { return; } // end if

    header('X-Powered-By: ' . self::PoweredBy . ' v.' . self::Version);
  } // end poweredby

} // end ghobe


$Brute = new ghobe();
if($Brute->HIja() === true) { include_once('./wp-login.php'); } // end if
