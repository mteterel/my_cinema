<?php

class Member_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getAllMembers() {
        return $this->db
            ->select('*')
            ->from('membre')
            ->order_by('id_membre')->get()->result();
    }

    public function getMember($id) {
        $this->db->select('f.nom, f.prenom, m.id_abo, a.nom AS nom_abo, m.date_abo, m.date_inscription')
            ->from('membre AS m')
            ->join('fiche_personne AS f', 'm.id_fiche_perso = f.id_perso', 'inner')
            ->join('abonnement AS a', 'm.id_abo = a.id_abo', 'inner')
            ->where('id_membre', $id);

        return $this->db->get()->row();
    }

    public function getWatchedHistory($id) {
        $this->db
            ->select('f.id_film, f.titre, COUNT(f.id_film)')
            ->from('historique_membre AS h')
            ->join('film AS f', 'h.id_film = f.id_film', 'inner')
            ->join('membre AS m', 'h.id_membre = m.id_membre', 'inner')
            ->where('m.id_membre', $id)
            ->group_by('h.id_film');

        return $this->db->get()->result();
    }

    public function changeAbo($id_membre, $id_abo) {
        $this->db
            ->set('id_abo', $id_abo)
            ->set('date_abo', 'NOW()')
            ->where('id_membre', $id_membre)
            ->update('membre');
    }

    public function search($query, $max_results = 20) {
        $this->db->select('m.id_membre, p.nom, p.prenom, a.nom AS nom_abo, m.date_abo AS date_abo')
            ->from('membre AS m')
            ->join('fiche_personne AS p', 'm.id_fiche_perso = p.id_perso', 'inner')
            ->join('abonnement AS a', 'm.id_abo = a.id_abo', 'join')
            ->like('CONCAT(p.nom, " ", p.prenom)', $query)
            ->order_by('id_membre');

        //$this->db->limit($max_results);
        return $this->db->get()->result();
    }
}