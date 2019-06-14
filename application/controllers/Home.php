<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function index()
	{
        $this->load->model('Movie_model');
        $grille = $this->Movie_model->getAffiche();

		$this->twig->display('cinema/pages/homepage',
            ['affiche_films' => $grille ]);
	}
}
