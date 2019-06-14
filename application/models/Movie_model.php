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
            ->join('genre', 'film.id_genre = genre.id_genre', 'inner')
            ->join('historique_membre', 'historique_membre.id_film = film.id_film', 'inner')
            ->where('film.id_film', $id)
            ->group_by('film.id_film');

        return $this->db->get()->row();
    }

    public function searchAdvanced($q, $g = null, $d = null, $max_results = 5, $from = 0) {
        $this->db->select('film.id_film, film.titre, film.resum, film.duree_min, genre.nom, COUNT(historique_membre.id_film) AS nb_fois_vu, COUNT(film.id_film) AS nb_total_films')
            ->from('film')
            ->like('titre', $q)
            ->join('genre', 'film.id_genre = genre.id_genre', 'left')
            ->join('historique_membre', 'historique_membre.id_film = film.id_film', 'left');

        if ($g !== null && $g !== 'all')
            $this->db->where('film.id_genre', $g);

        if ($d !== null && $d != 'all')
            $this->db->where('film.id_distrib', $d);

        $this->db->group_by('film.id_film')
            ->offset($from)
            ->limit($max_results);

        return $this->db->get()->result();
    }

    public function getUpcoming()
    {
        $this->db->select('f.id_film, f.titre, g.debut_sceance, g.fin_sceance')
            ->from('grille_programme AS g')
            ->join('film AS f', 'g.id_film = f.id_film', 'inner');

        return $this->db->get()->result();
    }
}