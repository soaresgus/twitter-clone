<?php

    namespace App\Models;

    use NF\Model\Model;

    class Tweet extends Model
    {
        private $id;
        private $id_usuario;
        private $tweet;
        private $data;

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
            $query = 'INSERT INTO tb_tweets(id_usuario, tweet) VALUES(:id_usuario, :tweet)';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':tweet', $this->__get('tweet'));
            $stmt->execute();

            return $this;
        }

        public function remover()
        {
            $query = 'DELETE FROM tb_tweets WHERE id = :id_tweet AND id_usuario = :id_usuario';
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_tweet', $this->__get('id'));
            $stmt->bindValue(':id_usuario', $_SESSION['id']);
            $stmt->execute();

            return true;
        }

        public function getAll()
        {
            $query = 'SELECT t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, "%d/%m/%Y %H:%i") as data FROM tb_tweets as t LEFT JOIN tb_usuarios as u ON (t.id_usuario = u.id) WHERE id_usuario = :id_usuario OR t.id_usuario IN (SELECT id_usuario_seguindo FROM tb_usuarios_seguidores WHERE id_usuario = :id_usuario) ORDER BY t.data DESC';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getPorPagina($limit, $offset)
        {
            $query = 'SELECT t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, "%d/%m/%Y %H:%i") as data FROM tb_tweets as t LEFT JOIN tb_usuarios as u ON (t.id_usuario = u.id) WHERE id_usuario = :id_usuario OR t.id_usuario IN (SELECT id_usuario_seguindo FROM tb_usuarios_seguidores WHERE id_usuario = :id_usuario) ORDER BY t.data DESC LIMIT '.$offset.', '.$limit;

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getTotalRegistros()
        {
            $query = 'SELECT count(*) as total FROM tb_tweets as t LEFT JOIN tb_usuarios as u ON (t.id_usuario = u.id) WHERE id_usuario = :id_usuario OR t.id_usuario IN (SELECT id_usuario_seguindo FROM tb_usuarios_seguidores WHERE id_usuario = :id_usuario)';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }

?>