<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function index()
	{
        $this->load->model('Movie_model');
        $grille = $this->Movie_model->getUpcoming();

		$this->twig->display('cinema/pages/homepage',
            ['grille_programme' => $grille ]);
	}
}
