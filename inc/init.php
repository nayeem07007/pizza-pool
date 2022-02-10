<?php 
/**
 * autoload class
 * @package autoload pizza pool class
 */

function my_autoloader($class) {
  $file_extention = '.php';
  $file_prefix = 'class-';
  $get_file_name = explode('\\', $class);
  $file_name = end($get_file_name);
  $file = __DIR__.'/classes/'.$file_prefix.$file_name.$file_extention;
  if(file_exists($file)) {
     require_once $file;
  }
}

spl_autoload_register('my_autoloader');


new \pizza\pool\delivery;
