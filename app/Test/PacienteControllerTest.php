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

    public function NombresProvider(){
        return[
            'Caso 1' => ['',"Cantidad"],
            'Caso 2' => ['Melissa',"Correcto"],
            'Caso 3' => ['H3enry',"Letras"],
            'Caso 4' => ['Me',"Cantidad"],
            'Caso 5' => ['MelissaMelissaMelissaMelissa',"Cantidad"]
        ];
    }
        /**
        * @dataProvider NombresProvider
        */
        public function testValidarNombre($entrada,$resultado){
            $prueba = new PacienteController();
            $this->assertSame($resultado,$prueba->ValidarNombre($entrada));
        }

        public function CedulaProvider(){
            return[
                'Caso 1' => ['8-123-1235',"Correcto"],
                'Caso 2' => ['81231235',"Incorrecto"],
                'Caso 3' => ['8',"Incorrecto"],
                'Caso 4' => ['8183818288',"Incorrecto"]
            ];
        }
            /**
            * @dataProvider CedulaProvider
            */
        public function testValidarCedula($entrada,$resultado){
            $prueba = new PacienteController();
            $this->assertEquals($resultado,$prueba->validarCedula($entrada));
        }

}