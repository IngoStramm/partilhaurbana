<?php

#region Constants
define('PU_DIR', get_template_directory());
define('PU_URL', get_template_directory_uri());

#endregion Constants

#region Classes

require_once(PU_DIR . '/classes/classes.php');

#endregion Classes

#region Requires

// Theme Functions
require_once(PU_DIR . '/functions/functions.php');

// Style/Scripts include
require_once(PU_DIR . '/scripts.php');

#endregion Requires
