<?php
class Conexion
{
        public static function conectar()
        {
            $conexion = new mysqli("mysql", "root", "clave123", "gestion_citas");
            return $conexion;
        }
}
