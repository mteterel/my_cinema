<?php

class Genre_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getAllGenres() {
        return $this->db->get('genre')->result();
    }
}