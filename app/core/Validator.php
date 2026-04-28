<?php

namespace Core;

class Validator{
    
    // Validar contraseña
    public static function validarPassword($password){
        if (strlen($password) < 6) return false;
        if (!preg_match('/[A-Z]/', $password)) return false;
        if (!preg_match('/[0-9]/', $password)) return false;
        if (!preg_match('/[\$\-\/\*\.]/', $password)) return false;
        return true;
    }
    
    public static function getPasswordErrorMessage(){
        return 'La contraseña debe tener: mínimo 6 caracteres, al menos una mayúscula, un número y un carácter especial ($ - / * .)';
    }
    
    // Validar email
    public static function validarEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL) !==false;
    }
    
    // Validar cédula venezolana
    public static function validarCedula($cedula){
        return preg_match('/^[VEJPGvejpg]-?\d{5,10}$/', $cedula);
    }
    
    // Validar teléfono venezolano
    public static function validarTelefono($telefono){
        if(empty($telefono)) return true;
        return preg_match('/^(04(12|14|16|24|26|22)-\d{7}|0\d{3}-\d{7})$/', $telefono);
    }
    
    //  Validar solo letras y espacios
    public static function validarTexto($texto, $min =2, $max =100){
        $texto =trim($texto);
        $longitud = strlen($texto);
        if($longitud < $min || $longitud >$max) return false;
        return preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $texto);
    }
    
    // Validar número decimal
    public static function validarMonto($monto){
        return is_numeric($monto) && $monto >0;
    }
    
    // Validar fecha en formato Y-m-d
    public static function validarFecha($fecha){
        $d = \DateTime::createFromFormat('Y-m-d', $fecha);
        return $d && $d ->format('Y-m-d') ===$fecha;
    }
    
    // Validar que un campo no esté vacío
    public static function required($valor){
        return !empty(trim($valor));
    }
    
    // Validar longitud máxima
    public static function maxLength($valor, $max){
        return strlen($valor) <=$max;
    }
    
    //  Validar longitud mínima
    public static function minLength($valor, $min){
        return strlen($valor) >=$min;
    }
    
    // Validar RIF
    public static function validarRIF($rif){
        return preg_match('/^[JGVEPjgvep]-\d{8,9}-\d$/', $rif);
    }
    
    // SANITIZACIÓN
    
    // Sanitizar string para salida HTML 
    public static function sanitizarOutput($valor){
        if(is_array($valor)){
            return array_map([self::class, 'sanitizarOutput'], $valor);
        }
        return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
    }
    
    // Sanitizar string para entrada a base de datos
    public static function sanitizarInput($valor){
        if(is_array($valor)){
            return array_map([self::class, 'sanitizarInput'], $valor);
        }
        $valor =trim($valor ?? '');
        $valor =strip_tags($valor);
        return $valor;
    }
    
    // Sanitizar email
    public static function sanitizarEmail($email){
        $email =self::sanitizarInput($email);
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
    
    // Sanitizar número
     
    public static function sanitizarNumero($numero){
        return filter_var($numero, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
    
    // Sanitizar entero
    public static function sanitizarEntero($numero){
        return filter_var($numero, FILTER_SANITIZE_NUMBER_INT);
    }
    
    // Sanitizar para nombre
    public static function sanitizarNombre($nombre){
        $nombre =self::sanitizarInput($nombre);
        return preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/', '', $nombre);
    }
    
    // Sanitizar cédula
    public static function sanitizarCedula($cedula){
        $cedula =self::sanitizarInput($cedula);
        return strtoupper(preg_replace('/[^VEJPGvejpg0-9]/', '', $cedula));
    }
    
    // VALIDACIÓN COMPLETA DE FORMULARIOS
    
    // Validar y sanitizar datos de registro de cliente
    public static function validarRegistroCliente($data){
        $errors =[];
        $sanitized =[];
        
        // Nombre
        $nombre =self::sanitizarNombre($data['nombre'] ?? '');
        if(!self::validarTexto($nombre, 2, 50)){
            $errors['nombre'] ='Nombre inválido (solo letras, 2-50 caracteres)';
        }
        $sanitized['nombre'] =$nombre;
        
        // Apellido
        $apellido =self::sanitizarNombre($data['apellido'] ?? '');
        if(!self::validarTexto($apellido, 2, 50)){
            $errors['apellido'] ='Apellido inválido (solo letras, 2-50 caracteres)';
        }
        $sanitized['apellido'] =$apellido;
        
        // Cédula
        $cedula =self::sanitizarCedula($data['cedula'] ?? '');
        if(!self::validarCedula($cedula)){
            $errors['cedula'] ='Formato de cédula inválido. Ejemplo: V-12345678';
        }
        $sanitized['cedula'] = $cedula;
        
        // Teléfono
        $telefono =self::sanitizarInput($data['telefono'] ?? '');
        if(!empty($telefono) && !self::validarTelefono($telefono)){
            $errors['telefono'] ='Formato de teléfono inválido. Ejemplo: 0412-1234567';
        }
        $sanitized['telefono'] = $telefono;
        
        // Email
        $email =self::sanitizarEmail($data['email'] ?? '');
        if(!self::validarEmail($email)){
            $errors['email'] ='Correo electrónico inválido';
        }
        $sanitized['email'] =$email;
        
        // Dirección 
        $direccion =self::sanitizarInput($data['direccion'] ?? '');
        $sanitized['direccion'] =$direccion;
        
        // Contraseña
        $password =$data['password'] ?? '';
        if(!self::validarPassword($password)){
            $errors['password'] =self::getPasswordErrorMessage();
        }
        
        // Confirmar contraseña
        $confirmar =$data['confirm_password'] ?? '';
        if($password !==$confirmar){
            $errors['confirm_password'] ='Las contraseñas no coinciden';
        }
        $sanitized['password'] =$password;
        
        return [
            'success' =>empty($errors),
            'errors' =>$errors,
            'data' =>$sanitized
        ];
    }
    
    // Validar datos de login
    public static function validarLogin($data){
        $errors =[];
        $sanitized =[];
        
        $email =self::sanitizarEmail($data['email'] ?? '');
        if(!self::validarEmail($email)){
            $errors['email'] ='Correo electrónico inválido';
        }
        $sanitized['email'] =$email;
        
        $password =$data['password'] ?? '';
        if(empty($password)){
            $errors['password'] ='La contraseña es requerida';
        }
        $sanitized['password'] =$password;
        
        return [
            'success' =>empty($errors),
            'errors' =>$errors,
            'data' =>$sanitized
        ];
    }
    
    // Validar datos de requerimiento
    public static function validarRequerimiento($data){
        $errors =[];
        $sanitized =[];
        
        $titulo =self::sanitizarInput($data['titulo'] ?? '');
        if(!self::required($titulo) || !self::maxLength($titulo, 200)){
            $errors['titulo'] ='Título requerido (máximo 200 caracteres)';
        }
        $sanitized['titulo'] =$titulo;
        
        $descripcion =self::sanitizarInput($data['descripcion'] ?? '');
        if(!self::required($descripcion)){
            $errors['descripcion'] ='Descripción requerida';
        }
        $sanitized['descripcion'] =$descripcion;
        
        return [
            'success' =>empty($errors),
            'errors' =>$errors,
            'data' =>$sanitized
        ];
    }
    
    // Validar datos de perfil
    public static function validarPerfil($data){
        $errors =[];
        $sanitized =[];
        
        $nombre =self::sanitizarNombre($data['nombre'] ?? '');
        if(!self::validarTexto($nombre, 2, 50)){
            $errors['nombre'] ='Nombre inválido';
        }
        $sanitized['nombre'] =$nombre;
        
        $apellido =self::sanitizarNombre($data['apellido'] ?? '');
        if(!self::validarTexto($apellido, 2, 50)){
            $errors['apellido'] ='Apellido inválido';
        }
        $sanitized['apellido'] =$apellido;
        
        $telefono =self::sanitizarInput($data['telefono'] ?? '');
        if(!empty($telefono) && !self::validarTelefono($telefono)){
            $errors['telefono'] ='Formato de teléfono inválido';
        }
        $sanitized['telefono'] =$telefono;
        
        $direccion =self::sanitizarInput($data['direccion'] ?? '');
        $sanitized['direccion'] =$direccion;
        
        return [
            'success' =>empty($errors),
            'errors' =>$errors,
            'data' =>$sanitized
        ];
    }
    
    // Validar datos de factura
    public static function validarFactura($data){
        $errors =[];
        $sanitized =[];
        
        $monto =self::sanitizarNumero($data['monto_total'] ?? 0);
        if(!self::validarMonto($monto)){
            $errors['monto_total'] ='Monto inválido';
        }
        $sanitized['monto_total'] =$monto;
        
        $descripcion =self::sanitizarInput($data['descripcion'] ?? '');
        if(!self::required($descripcion)){
            $errors['descripcion'] ='Descripción requerida';
        }
        $sanitized['descripcion'] =$descripcion;
        
        return [
            'success' =>empty($errors),
            'errors' =>$errors,
            'data' =>$sanitized
        ];
    }
    
    // MÉTODOS DE SEGURIDAD
    
    // Sanitizar array completo de datos POST
    public static function sanitizarPOST(){
        $sanitized =[];
        foreach($_POST as $key =>$value){
            if(is_array($value)){
                $sanitized[$key] =self::sanitizarInput($value);
            } else {
                $sanitized[$key] =self::sanitizarInput($value);
            }
        }
        return $sanitized;
    }
    
    // Sanitizar array completo de datos GET
    public static function sanitizarGET(){
        $sanitized =[];
        foreach($_GET as $key =>$value){
            $sanitized[$key] =self::sanitizarInput($value);
        }
        return $sanitized;
    }
}