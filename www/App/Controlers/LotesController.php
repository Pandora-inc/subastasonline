<?php
namespace www\App\Controlers;

use www\App\Core\AuthToken;
use www\App\Core\View;
use www\App\Modelo\OfertasLote;
use www\App\Modelo\Usuarios;
use www\App\Utilities\Validator;

class LotesController
{

    public function requireAuth()
    {
        // Requerimos que el usuario esté autenticado.
        $auth = new AuthToken();
        if (!$auth->estaAutenticado()) {
            View::renderJson([
                'status' => false,
                'error' => 'Tenés que iniciar sesión para poder acceder a esta pantalla.'
            ]);
            exit();
        }
    }

    public function Ofertar()
    {
        // $this->requireAuth();
        $Json = file_get_contents('php://input');
        $data = json_decode($Json, true);

        $auth = new AuthToken(); // $auth = new Token();
        $usuario = $auth->getUsuario();

        $data['id_usuario'] = $usuario->getId();
        // $data['id_usuario'] = 4;

        // print_r($data);

        $validation = new Validator($data, [
            'importe' => [
                'required'
            ],
            'id_usuario' => [
                'required'
            ],
            'lote' => [
                'required'
            ]
        ]);

        if (!$validation->passes()) {
            View::renderJson([
                'error' => 'error no se pudieron validar los datos',
                'descripcion' => $validation->getErrores()
            ]);
            return false;
        }

        $oferta = new OfertasLote();
        $oferta->setImporte($data['importe']);
        $oferta->setUsuario((new Usuarios())->getById($data['id_usuario']));

        if ($oferta->nuevo_id($data['lote'])) {
            View::renderJson([
                'success' => 'Se realizo la oferta.'
            ]);
            return true;
        } else {
            View::renderJson([
                'error' => 'No se pudo realizar la oferta.'
            ]);
            return false;
        }
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
}