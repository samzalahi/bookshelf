<?php
  //Database Params
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', 'loopLocalServer');
  define('DB_NAME', 'bookshelf');
  define('DB_CHARSET', 'utf8');
  
  // App Root
  define('APPROOT', dirname(dirname(__FILE__)));
  // URL Root
  define('URLROOT', 'http://localhost/bookshelfs');
  // Magazine Target Root
  define('MAGAZINEROOT', 'X:/magazines/');
  // File Target Root
  define('BOOKROOT', 'X:/books/');
  // Network drive
  define('SHAREFOLDER', '\\\\10.102.101.5\\Qatar_History\\TQ_Books');
  define('DOMINE', 'Qatar-History');
  define('USERNAME', 'mohamed.zalahi');
  define('PASSWORD', 'loopLocalServer1');
  // Book caver path
  define('BOOKCOVERROOT', $_SERVER['DOCUMENT_ROOT'] . '/bookshelfs/public/img/book_covers/');
  // Magazine caver path
  define('MAGCOVERROOT', $_SERVER['DOCUMENT_ROOT'] . '/bookshelfs/public/img/mag_covers/');
  // File upload function not support HTTP & https based file path
  // Site Name
  define('SITENAME', 'Book Shelf');
  // Site Version
  define('APPVERSION', '2.0.0');
