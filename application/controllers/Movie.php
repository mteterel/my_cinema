<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movie extends CI_Controller
{
    public function view($id) {
        $this->load->model('Movie_model');
        $movie = $this->Movie_model->getMovie($id);

        if (empty($movie))
            show_404();
        else
            $this->twig->display('cinema/pages/movie_view', ['movie' => $movie]);
    }

    public function edit($id) {
        $this->twig->display('cinema/movie_edit');
    }

    public function random() {
        $this->load->model('Movie_model');
        $movies = $this->Movie_model->getAllMovies();

        $random_id = mt_rand(0, count($movies) - 1);
        redirect('/movie/' . $movies[$random_id]->id_film);
    }

    public function search() {
        $this->load->model('Distrib_model');
        $this->load->model('Genre_model');
        $all_d = $this->Distrib_model->getAllDistribs();
        $all_g = $this->Genre_model->getAllGenres();

        $search_filters = [
            'query' => $this->input->get('q'),
            'genre' => $this->input->get('genre'),
            'distrib' => $this->input->get('distrib'),
            'max_results' => $this->input->get('num_results'),
        ];

        $num_max_results = max(min($search_filters['max_results'], 100), 5);

        $this->load->model('Movie_model');
        $results = $this->Movie_model->searchAdvanced(
            is_string($search_filters['query']) ? $search_filters['query'] : '',
            $search_filters['genre'],
            $search_filters['distrib'],
            $num_max_results
        );
        $num_results = count($results);

        $this->pagination->initialize([
            'total_rows' => $num_results,
            'per_page' => $num_max_results,
            'num_links' => 5,
            'attributes' => [ 'class' => 'item' ],
            'full_tag_open' => '<div class="ui pagination menu">',
            'full_tag_close' => '</div>',
            'cur_tag_open' => '<a class="active item">',
            'cur_tag_close' => '</a>',
            'page_query_string' => true,
            'use_page_numbers' => true,
            'reuse_query_string' => true,
            'query_string_segment' => 'page',
        ]);

        $page = $this->input->get('page') ?? 1;
        $from_offset = (($page ?? 1) - 1) * $num_max_results;
        $results = array_splice($results, $from_offset, $num_max_results);

        $view_data = [
            'distributors' => $all_d,
            'genres' => $all_g,
            'num_results' => $num_results,
            'search_results' => $results ?? null,
            'user_input' => $search_filters,
            'pagination_html' => $this->pagination->create_links()
        ];
        $this->twig->display('cinema/pages/movie_search', $view_data);
    }
}