<?php
    function validateLatin($string) {
        $result = false;

        if (preg_match("/^[\w\d\s.,-]*$/", $string)) {
            $result = true;
        }

        return $result;
    }
?>