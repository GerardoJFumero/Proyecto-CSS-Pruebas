<?php

use PharIo\Manifest\Requirement;

class MedicoModel
{
    private $medicos;
    private $db;

    public function __construct()
    {
        $this->medicos = array();
        $this->db = Conexion::conectar();
    }

    public function verificarUser($cedula){
        $consulta = $this->db->query("SELECT count(*) as contador from usuario where cedula_medico = '" . $cedula . "';"  );
        $existe = $consulta->fetch_assoc();
        if ($existe['contador'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function verificarlogin($cedula, $contraseña)
    {
        //Punteros que permiten verificar la existencia de información en la BDD
            $consulta = $this->db->query("SELECT count(*) as contador from usuario where cedula_medico = '" . $cedula . "' AND password = '" . $contraseña . "';"  );
            $existe = $consulta->fetch_assoc();

            if ($existe['contador'] > 0) {
                return true;
            } else {
                return false;
            }
    }


    public function listar($fecha){

        $consulta = $this->db->query("SELECT * FROM citas WHERE id_medico = 3 AND fecha_cita = $fecha");
        while ($filas = $consulta->fetch_assoc()){
            $medicos[] = $filas;
        }
        return $medicos;
    }

}