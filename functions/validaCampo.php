<?php
    $error = "";
    function validaCampo($campo){
        if(empty($campo)){
            $error = true;
        } else {
            $error = false;
        }
        return $error;
    }