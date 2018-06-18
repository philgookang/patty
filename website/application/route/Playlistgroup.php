<?php

Map::path('playlist/group', function() {

    $data = array();
    $date['playlist_group_list'] = PlaylistGroupM::new()->getList();

    $this->load->html('template/head', array('page' => 'playlist=>group'));
    $this->load->html('page/playlist_group/index', $date);
    $this->load->html('template/foot');
});