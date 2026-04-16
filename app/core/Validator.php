<?php

namespace Core;

class Validator{
    // Validar contraseña con requisitos específicos
    public static function validarPassword($password){
        // Mínimo 6 caracteres
        if (strlen($password) < 6){
            return false;
        }
        
        // Al menos 1 letra mayúscula
        if (!preg_match('/[A-Z]/', $password)){
            return false;
        }
        
        // Al menos 1 número
        if (!preg_match('/[0-9]/', $password)){
            return false;
        }
        
        // Al menos 1 carácter especial ($ - / * .)
        if (!preg_match('/[\$\-\/\*\.]/', $password)){
            return false;
        }
        return true;
    }
    
    public static function getPasswordErrorMessage(){
        return 'La contraseña debe tener: mínimo 6 caracteres, al menos una mayúscula, un número y un carácter especial ($ - / * .)';
    }
}