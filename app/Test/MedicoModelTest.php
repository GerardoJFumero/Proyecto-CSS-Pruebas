<?php

require_once(__DIR__.'/../Models/MedicoModel.php');
require_once(__DIR__.'/../Models/PacienteModel.php');
require_once(__DIR__ . '/../Db/db.php');
use \PHPUnit\Framework\TestCase;

class MedicoModelTest extends TestCase
{
    public function usuarioProvider()
    {
        return [
            'Caso 1' => ["8-000-0001","clave321",true], //El usuario y contraseña son correctos
            'Caso 2' => ["8-000-0001","1234567",false] //La contraseña no es correcta
        ];
    }


    /**
     * @dataProvider usuarioProvider
     */
    public function testVerificarLogin($cedula,$contraseña, $esperado)
    {
        $paciente = new MedicoModel();
        $this->assertEquals($esperado, $paciente->verificarLogin($cedula,$contraseña));
    }
}