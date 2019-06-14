<?php

class Abo_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getAllAbos() {
        return $this->db->get('abonnement')->result();
    }
}