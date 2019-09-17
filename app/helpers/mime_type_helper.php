<?php

  function validateMimeType($tmpFilename, $mimeType)
  {
    
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    $allowedMimeArray = $mimeType;

    if (false === $ext = array_search(
        $finfo->file($tmpFilename),
        $allowedMimeArray,
        true
    )) {
        // Not valid formate
        return true;
    } else {
        // Valid formate
        return false;
    }
  }