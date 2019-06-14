<?php

class Member_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getAllMembers() {
        return $this->db->get('membre')->result();
    }

    public function search($query, $max_results = 20) {
        $this->db->select('m.id_membre, p.nom, p.prenom, a.nom AS nom_abo, m.date_abo AS date_abo')
            ->from('membre AS m')
            ->join('fiche_personne AS p', 'm.id_fiche_perso = p.id_perso', 'inner')
            ->join('abonnement AS a', 'm.id_abo = a.id_abo', 'join')
            ->like('CONCAT(p.nom, " ", p.prenom)', $query);

        $this->db->limit($max_results);
        return $this->db->get()->result();
    }
}