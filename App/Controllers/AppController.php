<?php

    namespace App\Controllers;

    use NF\Controller\Action;
    use NF\Model\Container;

    class AppController extends Action
    {
        public function timeline()
        {
            if($this->validaAutentificao()) {
                $tweet = Container::getModel('tweet');
                
                $pagina = 1;

                if(isset($_GET['pag']) && $_GET['pag'] >= 1) {
                    $pagina = $_GET['pag'];
                }

                $registros_pagina = 10;
                $deslocamento = ($registros_pagina)*($pagina-1); 


                $tweet->__set('id_usuario', $_SESSION['id']);
                $tweets = $tweet->getPorPagina($registros_pagina, $deslocamento);
                $total_tweets = $tweet->getTotalRegistros();

                $total_paginas = ceil($total_tweets['total'] / $registros_pagina);

                $pagina > $total_paginas ? header("Location: ?pag=$total_paginas") : false;

                $this->view->total_paginas = $total_paginas;
                $this->view->pagina_atual = $pagina;

                $this->view->tweets = $tweets;

                $this->dadosUsuarios();

                $this->render('timeline');
            }
        }

        public function tweet()
        {
            if($this->validaAutentificao()) {
                $tweet = Container::getModel('Tweet');

                $tweet->__set('tweet', $_POST['tweet']);
                $tweet->__set('id_usuario', $_SESSION['id']);
                $tweet->salvar();

                header('Location: /timeline');
            }
        }

        public function removerTweet()
        {
            if($this->validaAutentificao()) {
                $tweet = Container::getModel('Tweet');

                $tweet->__set('id', $_GET['id_tweet']);
                $tweet->remover();
                
                header('Location: /timeline');
            }
        }
        
        public function validaAutentificao()
        {
            session_start();
            if(empty($_SESSION['id']) || empty($_SESSION['nome']) || !isset($_SESSION['nome']) || !isset($_SESSION['id'])) {
                header('Location: /');
            }else {
                return true;
            }
        }   

        public function quemSeguir()
        {
            $usuarios = [];
            
            if($this->validaAutentificao()) {
                $pesquisa = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';

                if($pesquisa != '') {
                    $usuario = Container::getModel('usuario');
                    $usuario->__set('nome', $pesquisa);
                    $usuario->__set('id', $_SESSION['id']);
                    $usuarios = $usuario->getAll();
                }

                $this->view->usuarios = $usuarios;

                $this->dadosUsuarios();

                $this->render('quemSeguir');
            }
        }

        public function acao()
        {
            if($this->validaAutentificao()) {
                $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
                $id_usuario_seguido = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

                $usuario = Container::getModel('usuario');
                $usuario->__set('id', $_SESSION['id']);

                if($acao == 'seguir') {
                    $usuario->seguirUsuario($id_usuario_seguido);
                } else if($acao == 'deixar_seguir') {
                    $usuario->deixarSeguirUsuario($id_usuario_seguido);
                }
 
                header('Location: /quem_seguir');

            }
        }

        protected function dadosUsuarios()
        {
            $usuario = Container::getModel('usuario');

            $usuario->__set('id', $_SESSION['id']);

            $this->view->nome_usuario = $usuario->getNomeUsuario();
            $this->view->total_seguidores = $usuario->getTotalSeguidores();
            $this->view->total_seguindo = $usuario->getTotalSeguindo();
            $this->view->total_tweets = $usuario->getTotalTweets();
        }

    
    }

?>