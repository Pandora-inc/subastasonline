<?php
namespace www\App\Controlers;

use www\App\Core\AuthToken;
use www\App\Core\View;
use www\App\Modelo\Usuarios;
use www\App\Utilities\Validator;

class UsuariosController
{

    public function login()
    {
        // Obtenemos la informaciónj provista por el usuario
        $jsonData = file_get_contents('php://input');
        $postData = json_decode($jsonData, true);

        // Invocamos al authToken y tratamos de logear al usuario
        $auth = new AuthToken();
        if (!$auth->login($postData['email'], $postData['password'])) {
            View::renderJson([
                'success' => false,
                'error' => 'Las credenciales ingresadas no coinciden con nuestros registros.'
            ]);
            exit();
        }
        // Retornamos el resultado junto con la información pública del usuario.
        $usuario = $auth->getUsuario();
        // print_r($usuario);

        if (!$usuario->isEstado()) {
            View::renderJson([
                'success' => false,
                'error' => 'El usuario esta desactivado.'
            ]);
            exit();
        }
        
        
        View::renderJson([
            'success' => true,
            'data' => [
                'usuario' => [
                    'id' => $usuario->getId(),
                    'usuario' => $usuario->getNombre(),
                    'email' => $usuario->getEmail(),
                    'admin' => $usuario->isAdmin()
                ]
            ]
        ]);
    }

    public function registrarUsuarios()
    {
        $Json = file_get_contents('php://input');
        $data = json_decode($Json, true);

        $validation = new Validator($data, [
            'nombre' => [
                'required',
                'min:5'
            ],
            'apellido' => [
                'required',
                'min:3'
            ],
            'email' => [
                'required'
            ],
            'documento' => [
                'required',
                'numeric'
            ],
            'direccion' => [
                'required'
            ],
            'localidad' => [
                'required'
            ],
            'provincia' => [
                'required'
            ],
            'pais' => [
                'required'
            ],
            'celular' => [
                'required',
                'numeric'
            ],
            'prefijo' => [
                'required',
                'numeric'
            ],
            'apodo' => [
                'required'
            ],
            'password' => [
                'required',
                'min:8'
            ],
            'conf_password' => [
                'required',
                'min:8'
            ]
        ]);
        // print_r($data);
        if (!$validation->passes()) {

            View::renderJson([
                'error' => 'error no se pudieron validar los datos',
                'errores' => $validation->getErrores()
            ]);

            return false;
        }

        $usuario = new Usuarios();

        $usuario->setNombre($data['nombre']);
        $usuario->setApellido($data['apellido']);
        $usuario->setEmail($data['email']);
        $usuario->setDocumento($data['documento']);
        $usuario->setDireccion($data['direccion']);
        $usuario->setLocalidad($data['localidad']);
        $usuario->setProvincia($data['provincia']);
        $usuario->setPais($data['pais']);
        $usuario->setCelular($data['celular']);
        $usuario->setPrefijo($data['prefijo']);
        $usuario->setApodo($data['apodo']);
        $usuario->setPassword($data['password']);

        if ($usuario->nuevo()) {
            View::renderJson([
                'data' => 'alta de usuario'
            ]);
            return true;
        } else {
            View::renderJson([
                'error' => 'no se pudo de dar de alta'
            ]);
            return false;
        }
    }

    public function getlistaUsuarios()
    {
        $usuario = new Usuarios();
        return $usuario->listar();
    }

    public function desLogearUsuario()
    {
        $auth = new AuthToken();
        $auth->logout();

        $_SESSION['token'] = null;

        session_destroy();

        View::renderJson([
            'success' => true,
            'data' => 'Deslogeo correcto.'
        ]);
    }
}