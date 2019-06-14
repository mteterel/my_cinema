<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{
    public function view($id)
    {
        $this->twig->display('cinema/pages/member_view');
    }

    public function edit($id)
    {

    }

    public function search()
    {
        $this->load->model('Member_model');
        $user_query = $this->input->get('q');
        $max_results = $this->input->get('num_results');
        $max_results = min(max($max_results, 5), 100);

        $this->pagination->initialize([
            'total_rows' => 200,
            'per_page' => 20,
            'num_links' => 5,
            'attributes' => [ 'class' => 'item' ],
            'cur_tag_open' => '<a class="active item">',
            'cur_tag_close' => '</a>',
            'full_tag_open' => '<div class="ui pagination menu">',
            'full_tag_close' => '</div>',
            'page_query_string' => true,
            'use_page_numbers' => true,
            'query_string_segment' => 'page',
            'reuse_query_string' => true
        ]);

        $results = $this->Member_model->search($user_query);

        $view_data = ['search_results' => $results ?? null,
            'pagination_html' => $this->pagination->create_links(),
            'user_query' => $user_query];
        $this->twig->display('cinema/pages/member_search', $view_data);
    }
}