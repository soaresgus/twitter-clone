<?php

    namespace App\Controllers;

    use NF\Controller\Action;
    use NF\Model\Container;

    class IndexController extends Action
    {

        public function index()
        {
            $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
            $this->render('index');
        }
        
        public function inscreverse()
        {
            $this->view->erroCadastro = false;

            $this->view->usuario = [
                'nome' => '',
                'email' => '',
                'senha' => '',
            ];


            $this->render('inscreverse');
        }

        public function registrar()
        {
            if(empty($_POST)) {
                $this->view->erroCadastro = false;
                $this->view->usuario = [
                    'nome' => '',
                    'email' => '',
                    'senha' => '',
                ];
                $this->render('inscreverse');
            }else {

                $usuario = Container::getModel('Usuario');

                $usuario->__set('nome', $_POST['nome']);
                $usuario->__set('email', $_POST['email']);
                $usuario->__set('senha', md5($_POST['senha']));
                
                if($usuario->validarCadastro() && count($usuario->getUsuarioPorEmail()) == 0) {
                    $usuario->salvar();
                    
                    $this->render('cadastro');
                } else {
                    $this->view->usuario = [
                        'nome' => $_POST['nome'],
                        'email' => $_POST['email'],
                        'senha' => $_POST['senha'],
                    ];

                    $this->view->erroCadastro = true;

                    $this->render('inscreverse');
                }
            }
        }
    }

?>