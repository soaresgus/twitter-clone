<?php

    namespace App;

    use NF\Init\Bootstrap;

    class Route extends Bootstrap
    {
        //Iniciando rota e definindo o seu controlador
        protected function initRoutes() 
        {
            $routes['home'] = [
                'route' => '/',
                'controller' => 'indexController',
                'action' => 'index'
            ];

            $routes['inscreverse'] = [
                'route' => '/inscreverse',
                'controller' => 'indexController',
                'action' => 'inscreverse'
            ];
            
            $routes['registrar'] = [
                'route' => '/registrar',
                'controller' => 'indexController',
                'action' => 'registrar'
            ];

            $routes['autentificar'] = [
                'route' => '/autentificar',
                'controller' => 'authController',
                'action' => 'autentificar'
            ];

            $routes['timeline'] = [
                'route' => '/timeline',
                'controller' => 'appController',
                'action' => 'timeline'
            ];

            $routes['sair'] = [
                'route' => '/sair',
                'controller' => 'authController',
                'action' => 'sair'
            ];

            $routes['tweet'] = [
                'route' => '/tweet',
                'controller' => 'appController',
                'action' => 'tweet'
            ];

            $routes['remover_tweet'] = [
                'route' => '/remover_tweet',
                'controller' => 'appController',
                'action' => 'removerTweet'
            ];

            $routes['quem_seguir'] = [
                'route' => '/quem_seguir',
                'controller' => 'appController',
                'action' => 'quemSeguir'
            ];

            $routes['acao'] = [
                'route' => '/acao',
                'controller' => 'appController',
                'action' => 'acao'
            ];

            

            $this->setRoutes($routes);
        }
    }

?>