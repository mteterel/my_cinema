<?php

class Movie_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getAllMovies() {
        return $this->db->get('film')->result();
    }

    public function getMovie($id) {
        $this->db->select('film.*, genre.nom as nom_genre, COUNT(historique_membre.id_film) AS nb_fois_vu')
            ->from('film')
            ->join('genre', 'film.id_genre = genre.id_genre', 'left')
            ->join('historique_membre', 'historique_membre.id_film = film.id_film', 'left')
            ->where('film.id_film', $id)
            ->group_by('film.id_film');

        return $this->db->get()->row();
    }

    public function searchAdvanced($q, $g = null, $d = null) {
        $this->db->select('f.id_film, f.titre, f.resum, f.duree_min, f.date_debut_affiche, g.nom, COUNT(h.id_film) AS nb_fois_vu')
            ->from('film AS f')
            ->like('titre', $q)
            ->join('genre AS g', 'f.id_genre = g.id_genre', 'left')
            ->join('historique_membre AS h', 'h.id_film = f.id_film', 'left');

        if ($g !== null && $g !== 'all')
            $this->db->where('f.id_genre', $g);

        if ($d !== null && $d != 'all')
            $this->db->where('f.id_distrib', $d);

        $this->db->group_by('f.id_film')
            ->order_by('f.date_debut_affiche DESC');
        return $this->db->get()->result();
    }

    public function getAffiche()
    {
        $this->db->select('f.id_film, f.titre, f.date_debut_affiche, f.date_fin_affiche')
            ->from('film AS f')
            ->where('f.date_debut_affiche <=', 'NOW()', false)
            ->where('f.date_fin_affiche >=', 'NOW()', false);

        return $this->db->get()->result();
    }
}