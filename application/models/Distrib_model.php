<?php

class Distrib_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function getAllDistribs() {
        return $this->db->get('distrib')->result();
    }
}