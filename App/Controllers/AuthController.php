<?php

    namespace App\Controllers;

    use NF\Controller\Action;
    use NF\Model\Container;

    class AuthController extends Action
    {
        public function autentificar()
        {
            $usuario = Container::getModel('Usuario');

            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', md5($_POST['senha']));

            $usuario->autentificar();

            if(!empty($usuario->__get('id')) && !empty($usuario->__get('nome'))) {
                session_start();

                $_SESSION['id'] = $usuario->__get('id');
                $_SESSION['nome'] = $usuario->__get('nome');

                header('Location: /timeline');
            }else {
                header('Location: /?login=erro');
            }
        }

        public function sair()
        {
            session_start();
            session_destroy();
            header('Location: /');
        }
    }

?>