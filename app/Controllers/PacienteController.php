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
                echo "El campo direccion no puede estar vacío";
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

    //Función para calcular la cantidad de letras en una cadena
    public function cantidadLetras($entrada){

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
        //Variables obtenidas desde el post
        
        $cedula=$_POST['cedula'];
        $fechanac=$_POST['fechanac'];
        $email=$_POST['email'];
        $telefono=$_POST['telefono'];
        $policlinica=$_POST['policlinica'];
        $especialidad=$_POST['especialidad'];
        
        //Si los campos no están vacíos, verifica su existencia
        if(!empty($cedula) && !empty($fechanac)){
            $paciente= new PacienteModel();
            $existe_paciente = $paciente->verificarDatosPaciente($cedula, $fechanac);

                //Si el paciente existe redirigir a la pagina de programar cita, de lo contrario, error
                if($existe_paciente){
                    $paciente = new PacienteModel();
                    $cita_agendada = $paciente -> asignarCita($cedula, $email, $telefono, $policlinica, $especialidad);

                    if($cita_agendada){
                        $num_cita = $this->db->query("SELECT numero_cita FROM citas WHERE numero_cita = (SELECT MAX(numero_cita) FROM citas)");
                        $num= mysqli_fetch_array($num_cita);
                        $fecha = $this->db->query("SELECT fecha_cita FROM citas WHERE numero_cita = (SELECT MAX(numero_cita) FROM citas)");
                        $fec= mysqli_fetch_array($fecha);
                        require_once('Views/Paciente/cita-nueva-registrada.php');

                    }  else{
                        echo "Cita no agendada, retroceda a la página anterior";
                    }

                }else{
                    echo "No existe el paciente";
                    } 

        }else{
            echo "campos vacíos";
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

        $cedula=$_POST['cedula'];
        $fechanac=$_POST['fechanac'];
        $numero_cita=$_POST['numero_cita'];
        $verificador=$_POST['verificador'];

        switch ($verificador){

            case "Cancelar":

                $paciente= new PacienteModel();
                $datos_correctos = $paciente->verificarDatosCita($cedula, $fechanac, $numero_cita);
                    if($datos_correctos){
                        $paciente= new PacienteModel();
                        $datos_correctos = $paciente->cancelarCita($numero_cita);
                        require_once('Views/Paciente/cita-cancelada.php');
                    } else{
                        include_once('Views/Error/error-datos-incorrectos.php');
                    }
                break;

            case "cancelar":

                $paciente= new PacienteModel();
                $datos_correctos = $paciente->verificarDatosCita($cedula, $fechanac, $numero_cita);
                    if($datos_correctos){
                        $paciente= new PacienteModel();
                        $datos_correctos = $paciente->cancelarCita($numero_cita);
                        require_once('Views/Paciente/cita-cancelada.php');
                    } else{
                        include_once('Views/Error/error-datos-incorrectos.php');
                    }
                
                break;

            default:
            include_once('Views/Error/error-cancelar.php');
        }

    }

}

?>