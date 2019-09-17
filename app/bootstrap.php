<?php
  // Load Config
  require_once 'config/config.php';
  
  // Load Helpers
  require_once 'helpers/url_helper.php';
  require_once 'helpers/session_helper.php';
  require_once 'helpers/mime_type_helper.php';
  require_once 'helpers/file_upload_helper.php';
  require_once $_SERVER['DOCUMENT_ROOT'] . '/bookshelftp/public/vendor/autoload.php';
  
  // Load Libraries
  // require_once 'libraries/Core.php';
  // require_once 'libraries/Controller.php';
  // require_once 'libraries/Database.php';
  if (!is_dir(BOOKROOT)) {
    mount();
  }

  // Autoload Core Libraries
  spl_autoload_register(function ($className) {
      require_once 'libraries/'. $className .'.php';
  });
