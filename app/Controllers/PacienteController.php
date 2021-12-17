<?php


use PhpParser\Builder\Function_;
use PhpParser\Node\Expr\FuncCall;
require_once('Models/PacienteModel.php');
require_once('Models/MedicoModel.php');

class PacienteController
{   
    private $db;
    private $pacientes;

    function __construct()
    {
        $this->db = Conexion::conectar();
        $this->pacientes = array();
        
    }

    function index()
    {
        require_once('Views/Layouts/bienvenida.php');
    }

    function registrarse(){
        require_once('Views/Paciente/registrar-usuario.php');
    }

    //Esta función permite registrar un paciente siguiendo las reglas de modelo de negocio
    public function guardar(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            //Se verifica que el nombre no esté vacío
            if(empty($_POST['nombres'])){
                echo "El campo nombres no puede estar vacío";
            //Si no está vacía, se verifica que sólo contenga letras    
            } else {
                $val_nombre = $this->validarNombre($_POST['nombres']);
                switch ($val_nombre){
                    case "Letras":
                        echo "El campo nombres sólo puede contener letras\n";
                        break;
                    case "Cantidad":
                        echo "El campo nombres solo puede contener de 3 a 25 letras\n";
                        break;
                    case "Correcto";
                        $nombres=($_POST['nombres']);
                        break;
                }
            }

            //Verificador del apellido igual que el del nombre
            if(empty($_POST['apellidos'])){
                echo "El campo apellidos no puede estar vacío\n";
            } else{
                $val_apellido = $this->validarNombre($_POST['apellidos']);
                switch ($val_apellido){

                    case "Letras":
                        echo "El campo apellidos sólo puede contener letras\n";
                        break;
                    case "Cantidad":
                        echo "El campo apellidos solo puede contener de 3 a 25 letras\n";
                        break;
                    case "Correcto";
                        $apellidos=($_POST['apellidos']);
                        break;
                }
            }

            //Primero se verifica que el campo cédula no esté vacío
            if(empty($_POST['cedula'])){
                echo "El campo cédula no puede estar vacío\n";
            //Si no está vacío, se verifica que cumpla los parámetros establecidos de cédula panameña con la función validad cédula
            } else {
                $val_cedula = $this->validarCedula($_POST['cedula']);
                switch ($val_cedula){

                    case "Correcto":
                        $cedula=($_POST['cedula']);
                        break;

                    case "Incorrecto":
                        echo "La cédula debe seguir los parámetros de cédula panameña\n";
                        break;
                }
            }

            if(empty($_POST['fechanac'])){
                echo "El campo fecha de nacimiento no puede estar vacío\n";
            } else{
                $fechanac = $_POST['fechanac'];
            }

            if(empty($_POST['tipo_sangre'])){
                echo "El campo tipo de sangre no puede estar vacío";
            } else {
                $tipo_sangre=($_POST['tipo_sangre']);
            }

            if(empty($_POST['direccion'])){
                echo "El campo dirección no puede estar vacío";
            } else{
                if ((strlen($_POST['direccion']))>100){
                    echo "La dirección no puede tener más de 100 caracteres";
                } else {
                    $direccion=($_POST['direccion']);
                }
            }
        }

        if(!empty($nombres) && !empty($apellidos) && !empty($cedula) && !empty($fechanac) && !empty($tipo_sangre) && !empty($direccion)){
            
            $paciente= new PacienteModel();
            $existe_paciente = $paciente->verificarPaciente($cedula);

                if (!$existe_paciente){
                    $paciente=new PacienteModel();
                    $paciente->registrarPaciente($nombres,$apellidos,$cedula,$fechanac,$tipo_sangre,$direccion);
                        if ($paciente==true){
                            $datos = $paciente->Info($cedula);
                            require_once('Views/Paciente/registro-completo.php');
                        } else {
                            echo "Registro no Completado";
                        }
                    
                }else{
                    echo "La cédula ya se encuentra registrada";
                }
        }
    }


    public function validarCedula($entrada){

        $resultado="";
        //Preg_match permite definir parámetros específicos de entrada a un input
        if(!preg_match(('/^(([1][0-3]-[0-9]+-[0-9]+)|([1-9]-[0-9]+-[0-9]+))+$/'),$entrada)){
            return $resultado="Incorrecto";
        }else{
            return $resultado="Correcto";
        }
    }

    public function validarNombre($entrada){

        $resultado="";

        switch ($entrada){

            case (!preg_match(('/^[a-z A-ZáéíóúüñÁÉÍÓÚÜÑ]+$/'),$entrada)):
                return $resultado="Letras";
            break;
    
            default:
                if ((strlen($entrada)<3) || (strlen($entrada)>25)){
                    return $resultado="Cantidad";
                }   else{
                    return $resultado="Correcto";
                }
            break;
        }
    }

    public function confirmar(){
        require_once('Views/Paciente/confirmar.php');
    }

    public function error(){
        require_once('Views/Error/error.php');
    }

    public function cita_nueva(){
        require_once('Views/Paciente/cita-nueva.php');
    }

    //Esta funcion verifica que los datos del cliente exitan en la base de datos
    //Una vez revise del POST la cedula y la fecha de nacimiento, verifica mediante verificarDatosPaciente su existencia.
    public function cita_nueva_solicitada(){

        
        $policlinica=$_POST['policlinica'];
        $especialidad=$_POST['especialidad'];

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            //Las variables cédula y fecha de nacimiento deben ser verificadas antes de probar todos los datos, por ende no pueden estar vacías
            if(empty($_POST['cedula'])){
                echo "El campo cédula no puede estar vacío";
            } else{
                $cedula=$_POST['cedula'];
            }        
            //Se debe verificar todos los datos no estén vacíos, para cada dato se hará la verificación
            if(empty($_POST['fechanac'])){
                echo "El campo fecha de nacimiento no puede estar vacío";
            } else{
                $fechanac=$_POST['fechanac'];
            }

            //Si todos los datos no tuvieron campos vacíos, se empieza la verificación de datos
            if(!empty($cedula) && !empty($fechanac) && !empty($policlinica) && !empty($especialidad)){
                //Se verifica que la cédula se encuentre registrada en la BDD
                $paciente= new PacienteModel();
                $existe_paciente = $paciente->verificarPaciente($cedula);
                //Se debe verificar que el paciente y la cédula correspondan en la BDD
                if ($existe_paciente==true){
                    $paciente= new PacienteModel();
                    $coinciden = $paciente->verificarDatosPaciente(($cedula), ($fechanac));
                            if ($coinciden==true){
                            //Verificación del email
                            if(empty($_POST['email'])){
                                echo "El campo correo no puede estar vacío";

                            } else{
                                $val_email = $this->validarEmail($_POST['email']);
                                if($val_email==true){
                                    $email=$_POST['email'];
                                }   else{
                                        echo "El formato del correo no es válido";
                                    }
                            }

                            //Verificación del telefono
                            if(empty($_POST['telefono'])){
                                echo "El campo número de celular no puede estar vacío";
                            } else {
                                $longitud = $this->cantidadCaracter($_POST['telefono']);
                                    switch($longitud){
                                        case ($longitud>=7&&$longitud<9):
                                            $telefono=$_POST['telefono'];
                                            break;
                                        default:
                                            echo "El campo número debe tener de 6 a 9 caracteres numéricos";
                                    }
                            }
                            if(!empty($telefono) && !empty($email)){
                                $cita_agendada = $paciente -> asignarCita($cedula, $email, $telefono, $policlinica, $especialidad);
                                    if($cita_agendada){
                                        $num_cita = $this->db->query("SELECT numero_cita FROM citas WHERE numero_cita = (SELECT MAX(numero_cita) FROM citas)");
                                        $num= mysqli_fetch_array($num_cita);
                                        $fecha = $this->db->query("SELECT fecha_cita FROM citas WHERE numero_cita = (SELECT MAX(numero_cita) FROM citas)");
                                        $fec= mysqli_fetch_array($fecha);
                                        require_once('Views/Paciente/cita-nueva-registrada.php');
                                    }
                            }
                            } else{
                                echo "La fecha de nacimiento no corresponde al paciente";
                            }
                } else{
                    echo "La cedula no se encuentra registrada";
                }
            }
        }
    }

    public function cantidadCaracter($entrada){
        return (strlen($entrada));
    }

    public function validarEmail($entrada){

        $entrada=filter_var($entrada,FILTER_SANITIZE_EMAIL);
            if (filter_var($entrada, FILTER_VALIDATE_EMAIL)) {
                return true;
            } else{
                return false;
            }
    }

    public function consultar_cita(){
        require_once('Views/Paciente/consultar-estado.php');
    }

    //Esta función permite saber si existe una cita en la base de datos agregada al paciente
    public function cita_consultada(){
        
        $cedula=$_POST['cedula'];
        $fechanac=$_POST['fechanac'];
        $numero_cita=$_POST['numero_cita'];

        //Si los campos no están vacíos, verifica su existencia
        if(!empty($cedula) && !empty($fechanac) && !empty($numero_cita)){

            $paciente= new PacienteModel();
            $existe_cita = $paciente->verificarDatosCita($cedula, $fechanac, $numero_cita);

            if($existe_cita){

                $num_cita = $this->db->query("SELECT numero_cita FROM citas WHERE numero_cita = $numero_cita");
                $num= mysqli_fetch_array($num_cita);
                $fecha = $this->db->query("SELECT fecha_cita FROM citas WHERE numero_cita = $numero_cita");
                $fec= mysqli_fetch_array($fecha);
                $estado = $this->db->query("SELECT estado FROM citas WHERE numero_cita = $numero_cita");
                $est= mysqli_fetch_array($estado);

                include_once('Views/Paciente/cita-consultada.php');

            } else {
                require_once('Views/Error/paciente-noexiste.php');
            }
        }

    }

    public function ayuda(){
        require_once('Views/Layouts/ayuda.php');
    }

    public function cancelar(){
        require_once('Views/Paciente/cancelar-cita.php');
    }

    public function cita_cancelada(){

        if($_SERVER["REQUEST_METHOD"] == "POST"){

            //Las variables cédula y fecha de nacimiento deben ser verificadas antes de probar todos los datos, por ende no pueden estar vacías
            if(empty($_POST['cedula'])){
                echo "El campo cédula no puede estar vacío";
            } else{
            }        
            //Se debe verificar todos los datos no estén vacíos
            if(empty($_POST['numero_cita'])){
                echo "<br>";
                echo 'El campo numero de cita no puede estar vacío'.'<br>';

            } else{
                $numero_cita=$_POST['numero_cita'];
            }

            if(empty($_POST['verificador'])){
                echo "El captcha no puede estar vacío";
                echo "<br>";
            } else{
                $verificador=$_POST['verificador'];
                switch ($verificador){

                    case (($verificador=="Cancelar")||($verificador=="cancelar")): 
    
                        $paciente= new PacienteModel();
                        $cedula=$_POST['cedula'];
                        $existe_paciente = $paciente->verificarPaciente($cedula);
                        //Se debe verificar que el paciente y la cédula correspondan en la BDD
                        if ($existe_paciente==true){
                            
                            if (empty($_POST['fechanac'])){
                                echo "El campo fecha de nacimiento no puede estar vacío";
                            } else{
                                $fechanac=$_POST['fechanac'];
                            }
                            $paciente= new PacienteModel();
                            $coinciden = $paciente->verificarDatosPaciente(($cedula), ($fechanac));
                            
                                if ($coinciden==true){
    
                                    //Ahora se verifica que el número de cita corresponda a ese paciente
                                    $paciente= new PacienteModel();
                                    $datos_correctos = $paciente->verificarDatosCita($cedula, $numero_cita);
    
                                        if ($datos_correctos==false){
                                            echo "El número de cita no corresponde al paciente";
                                        } else {
                                            $paciente= new PacienteModel();
                                            $datos_correctos = $paciente->cancelarCita($numero_cita);
                                            if($datos_correctos==true){
                                                require_once('Views/Paciente/cita-cancelada.php');
                                            } else{
                                                echo "Solicitud fallida";
                                            }
                                            
                                        }
                                }else{
                                    echo "La fecha de nacimiento no correponde al paciente";
                                }
                        }else{
                            echo "La cédula no se encuentra registrada";
                        }
                    break;
                    
                    default:
                        echo "Captcha incorrecto";
                }
            }
        }
    }

}

?>