<?php
  /**
   Gets the value for the given key from Shi configuration. The values
   are read from the files $_SERVER['SHI_SITEFILE'] and $_SERVER['SHI_ENVFILE'].
   
   The files are simply made of key value pairs, one pair on each line.
   The key and the value are separated by a single space.

   If the key is not set, the function returns an empty string.

   By convention, boolean false is 0 and boolean true is 1.
  */
function _shi($key)
{
        static $shi;
        if (!isset($shi)) {
                $shi = array();
                $shi['web_root'] = realpath(str_replace('//','/',dirname(__FILE__).'/../');
                $files_to_read = array();
                $files_to_read[] = str_replace('//','/',dirname(__FILE__).'/shi.conf');
                $files_to_read[] = str_replace('//','/',dirname(__FILE__).'/../shi.conf');
                $files_to_read[] = str_replace('//','/',dirname(__FILE__).'/../../shi.conf');
                if (isset($_SERVER['SHI_ENVFILE']))
                        $files_to_read[] = $_SERVER['SHI_ENVFILE'];
                foreach ($files_to_read as $i => $file) {
                        if (file_exists($file)) {
                                $lines = file($file);
                                foreach ($lines as $j => $line) {
                                        list($key, $value) = array_map('trim', explode(' ', $line));
                                        if ($key != '')
                                                $shi[$key] = $value;
                                }
                        }
                }
        }
        if (isset($shi[$key]))
                return $shi[$key];
        else
                return '';
}
?>