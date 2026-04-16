<?php

namespace Core;

class Autoload{
    public static function register(){
        spl_autoload_register(function($clase){
            // Mapeo de namespaces a carpetas
            $mapa =[
                'Core\\' =>__DIR__ . '/',
                'Config\\' =>__DIR__ . '/../config/',
                'Models\\' =>__DIR__ . '/../models/',
                'Controllers\\' =>__DIR__ . '/../controllers/'
            ];
            
            // Buscar en cada namespace
            foreach ($mapa as $prefijo =>$ruta_base){
                $longitud =strlen($prefijo);
                
                if (strncmp($prefijo, $clase, $longitud) ===0){
                    $clase_relativa =substr($clase, $longitud);
                    $archivo =$ruta_base . str_replace('\\', '/', $clase_relativa) . '.php';
                    
                    if (file_exists($archivo)){
                        require $archivo;
                        return;
                    }
                }
            }
        });
    }
}