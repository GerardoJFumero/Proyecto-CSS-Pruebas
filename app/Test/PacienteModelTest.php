<?php

require_once(__DIR__.'/../Models/PacienteModel.php');
require_once(__DIR__ . '/../Db/db.php');
use \PHPUnit\Framework\TestCase;


class PacienteModelTest extends TestCase
{   

    public function cedulasProveedor()
    {
        return [
            'Caso 1' => ["8-000-0010",true], //La cédula se encuentra registrada en la BDD por lo que debe recibir true
            'Caso 2' => ["7-040-0010",false], //La cédula no se encuentra registrada por lo que debe recibir false
        ];
    }

    /**
     * @dataProvider cedulasProveedor
     */
    public function testVerificarPaciente($cedula_paciente, $esperado)
    {
        $paciente = new PacienteModel();
        $this->assertEquals($esperado, $paciente->verificarPaciente($cedula_paciente));
    }

    public function pacientesProveedor(){

        return[
            'Caso 1' => ["8-000-0010","06-04-2000",true], //La cédula y la fecha de nacimiento coinciden en la tabla Pacientes 
            'Caso 2' => ["8-000-0010","06-04-2001",false], //La cédula y la fecha de nacimiento no coinciden en la base de datos
            'Caso 3' => ["8-001-0010","07-04-2001",false],  //Los datos no se encuentran registrados en la base de datos 
        ];
    }
    /**
     * @dataProvider pacientesProveedor
     */
    public function TestVerificarDatos($cedula, $fechanac, $esperado){
        $paciente = new PacienteModel();
        $this->assertEquals($esperado, $paciente->verificarDatosPaciente($cedula,$fechanac));
    }



}