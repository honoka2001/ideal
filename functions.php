<?php

/**
 * @param string $str
 * @return string
 */
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}