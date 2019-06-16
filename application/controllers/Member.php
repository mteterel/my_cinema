<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{
    public function view($id)
    {
        $this->load->model('Member_model');
        $membre = $this->Member_model->getMember($id);
        $historique = $this->Member_model->getWatchedHistory($id);

        $view_data = [
            'membre' => $membre,
            'historique' => $historique
        ];

        $this->twig->display('cinema/pages/member_view', $view_data);
    }

    public function edit($id)
    {
        $this->load->model('Member_model');
        $should_update = $this->input->get('update');
        if ($should_update == 1)
        {
            $abo_id = $this->input->get('abo');
            $this->Member_model->changeAbo($id, $abo_id);
            redirect(site_url(['member', $id, 'view']));
        }
        else
        {
            $this->load->model('Abo_model');
            $all_abos = $this->Abo_model->getAllAbos();
            $membre = $this->Member_model->getMember($id);

            $view_data = [
                'abonnements' => $all_abos,
                'membre' => $membre
            ];

            $this->twig->display('cinema/pages/member_edit', $view_data);
        }
    }

    public function search()
    {
        $this->load->model('Member_model');
        $user_query = $this->input->get('q');
        $max_results = $this->input->get('num_results');
        $max_results = min(max($max_results, 10), 100);

        $results = $this->Member_model->search($user_query);
        $num_results = count($results);

        $this->pagination->initialize([
            'total_rows' => $num_results,
            'per_page' => $max_results,
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

        $page = $this->input->get('page') ?? 1;
        $from = (($page ?? 1) - 1) * $max_results;
        $results = array_splice($results, $from, $max_results);

        $view_data = [
            'num_results' => $num_results,
            'search_results' => $results ?? null,
            'pagination_html' => $this->pagination->create_links(),
            'user_query' => $user_query];
        $this->twig->display('cinema/pages/member_search', $view_data);
    }
}