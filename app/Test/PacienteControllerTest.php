<?php

require_once(__DIR__.'/../Models/MedicoModel.php');
require_once(__DIR__.'/../Models/PacienteModel.php');
require_once(__DIR__ . '/../Db/db.php');
require_once(__DIR__.'/../Controllers/PacienteController.php');

use \PHPUnit\Framework\TestCase;

class PacienteControllerTest extends TestCase
{

    public function emailProvider(){
        return [
            'Caso 1' => ["sofia@gmail.com",true], 
            'Caso 2' => ["sofia@gmail",false],
            'Caso 3' => ["",false]
        ];
    }

    /**
     * @dataProvider emailProvider
     */
    public function testValidarEmail($entrada,$salida)
    {
        $paciente = new PacienteController();
        $this->assertEquals($salida, $paciente->validarEmail($entrada));
    }
}