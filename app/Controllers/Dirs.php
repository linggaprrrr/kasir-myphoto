<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DirectoryModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dirs extends BaseController
{

    protected $dirModel;

    public function __construct()
    {
        $this->dirModel = new DirectoryModel();
    }

    public function index() {
        $params['draw'] = $_REQUEST['draw'];
        $start = $_REQUEST['start'];
        $length = $_REQUEST['length'];
        $search_value = $_REQUEST['search']['value'];

        $filtered_count = $this->dirModel->totalCount($search_value); // return integer
        $data = $this->dirModel->totalCountData($search_value, $start, $length);
        $total_count = $this->dirModel->totalCount(); // count all (tanpa filter)

        $json_data = array(
            "draw" => intval($params['draw']),
            "recordsTotal" => $total_count,
            "recordsFiltered" => $filtered_count,
            "data" => $data
        );

        echo json_encode($json_data);
}

    
    
}
