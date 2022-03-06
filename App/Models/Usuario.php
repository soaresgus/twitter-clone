<?php

    namespace App\Models;

    use NF\Model\Model;

    class Usuario extends Model
    {
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($name)
        {
            return $this->$name;
        }

        public function __set($name, $value)
        {
            $this->$name = $value;
        }

        public function salvar()
        {
            $query = 'INSERT INTO tb_usuarios(nome, email, senha) VALUES (:nome, :email, :senha)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha')); //Criptografia MD5
            $stmt->execute();

            return $this;
            
        }

        public function validarCadastro()
        {
            $valido = true;

            if(strlen($this->__get('nome')) <= 3) {
                $valido = false;
            }

            if(strlen($this->__get('email')) <= 3) {
                $valido = false;
            }

            if(strlen($this->__get('senha')) <= 3) {
                $valido = false;
            }

            return $valido;
        }

        public function getUsuarioPorEmail()
        {
            $query = 'SELECT nome, email FROM tb_usuarios WHERE email = :email';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function autentificar()
        {
            $query = 'SELECT id, nome, email FROM tb_usuarios WHERE email = :email AND senha = :senha';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if(!empty($usuario['id']) && !empty($usuario['nome'])) {
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }

            return $usuario;
        }

        public function getAll()
        {
            $query = 'SELECT u.id, u.nome, u.email, (SELECT count(*) FROM tb_usuarios_seguidores AS us WHERE us.id_usuario = :id_usuario AND us.id_usuario_seguindo = u.id) AS seguindo_sn FROM tb_usuarios AS u WHERE u.nome LIKE :nome AND u.id != :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome').'%');
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function seguirUsuario($id_usuario_seguido)
        {
            $query = 'INSERT INTO tb_usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES(:id_usuario, :id_usuario_seguindo)';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguido);
            $stmt->execute();

            return true;
        }

        public function deixarSeguirUsuario($id_usuario_seguido)
        {
            $query = 'DELETE FROM tb_usuarios_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguido);
            $stmt->execute();

            return true;
        }

        public function getNomeUsuario()
        {
            $query = 'SELECT nome FROM tb_usuarios WHERE id = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $_SESSION['id']);
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getTotalTweets()
        {
            $query = 'SELECT count(*) AS total_tweets FROM tb_tweets WHERE id_usuario = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $_SESSION['id']);
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getTotalSeguindo()
        {
            $query = 'SELECT count(*) AS total_seguindo FROM tb_usuarios_seguidores WHERE id_usuario = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $_SESSION['id']);
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        public function getTotalSeguidores()
        {
            $query = 'SELECT count(*) AS total_seguidores FROM tb_usuarios_seguidores WHERE id_usuario_seguindo = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $_SESSION['id']);
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }


?>