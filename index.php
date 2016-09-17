<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';
spl_autoload_register( 'autoload' );
function autoload( $class, $dir = null ) {
 
    if ( is_null( $dir ) )
      $dir = __DIR__ . '/';
 
    foreach ( scandir( $dir ) as $file ) {
 
      // directory?
      if ( is_dir( $dir.$file ) && substr( $file, 0, 1 ) !== '.' )
        autoload( $class, $dir.$file.'/' );
 
      // php file?
      if ( substr( $file, 0, 2 ) !== '._' && preg_match( "/.php$/i" , $file ) ) {
 
        // filename matches class?
        if ( str_replace( '.php', '', $file ) == $class || str_replace( '.class.php', '', $file ) == $class ) {
 
            include $dir . $file;
        }
      }
    }
  }

session_start();

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/src/dependencies.php';

// Register middleware
require __DIR__ . '/src/middleware.php';

// Register routes
require __DIR__ . '/src/routes.php';

// Run app
$app->run();
