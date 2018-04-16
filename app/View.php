<?php
/**
 * Public Printer Control System
 *
 * Copyright © 2018 - 2019, Aaron Speer, aaron.speerfamily.ie ajamesspeer@gmail.com.
 * All Rights Reserved.
 */

function viewError($name, $content, $type) {
  return "<div class='alert alert-" . $type . "'> <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" . "<strong>" . $name . "</strong> " . $content . "</div>";
}

$function = new Twig_SimpleFunction('makeError', 'viewError');
$twig->addFunction($function);


$twig->addFunction(
    new \Twig_SimpleFunction(
        'form_token',
        function($lock_to = null) {
            static $csrf;
            if ($csrf === null) {
                $csrf = new AntiCSRF;
            }
            return $csrf->insertToken($lock_to, false);
        },
        ['is_safe' => ['html']]
    )
);
