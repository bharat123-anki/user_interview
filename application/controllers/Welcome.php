<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Welcome extends CI_Controller
{

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *       http://example.com/index.php/welcome
   *   - or -
   *       http://example.com/index.php/welcome/index
   *   - or -
   * Since this controller is set as the default controller in
   * config/routes.php, it's displayed at http://example.com/
   *
   * So any other public methods not prefixed with an underscore will
   * map to /index.php/welcome/<method_name>
   * @see https://codeigniter.com/user_guide/general/urls.html
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('UserInfo_model');
  }
  public function index()
  {

    $this->load->view('user/user');
  }


  public function getUserAddModal()
  {
    if ($this->input->is_ajax_request()) {

      $id = $this->input->post('id');
      $directory_data = [];
      if (!empty($id)) {
        $directory_data = $this->UserInfo_model->getUserDataById($id);
        $userIdList = $this->UserInfo_model->getAllUserId($id);
      }

      $user_id = array_column($userIdList, 'userId');
      $data['directory_data'] = $directory_data;
      $data['userId'] = $user_id;
      $this->load->view('user/user_add_modal', $data);
    }
  }


  public function UserAdd()
  {
    $response = array('status' => 500, 'msg' => 'Some Internal Error');
    $required = ['userId', 'title', 'body'];
    $proceed = 1;

    foreach ($required as $key => $val) {
      if (empty($_POST[$val])) {
        $data =  ['field' => $val, 'msg' => 'Field Is Required'];
        $response['status'] = 201;
        $response['msg'] = "No Fields";
        $response['err'][] = $data;
        $proceed = 0;
      }
    }
    if ($proceed) {

      $id = $this->input->post('id');
      $userId = $this->input->post('userId');
      $title = $this->input->post('title');
      $body = $this->input->post('body');

      $type = "New";
      if (isset($id) && !empty($id)) {
        $type = "Update";
      }
      // print_r($type);

      // exit;
      if ($type == "New") {

        $this->db->insert('user_info', array('userId' => $userId,  'title' => $title, 'body' => $body));
      } else {
        // for image manipulation
        $this->db->where('id', $id);
        $this->db->update('user_info', array('userId' => $userId,  'title' => $title, 'body' => $body));
      }
      // print_r($this->db->last_query());

      $disp_msg = "Data Added Sucessfully";
      if ($type != "New") {
        $disp_msg = "Data Updated Sucessfully";
      }
      if ($this->db->affected_rows() > 0) {
        $response = array('status' => 200, 'msg' => $disp_msg);
      } else {
        $response = array('status' => 200, 'msg' => $disp_msg);
      }
    }

    echo json_encode($response);
  }

  public function validate_phone_number($landline = '')
  {
    if (!preg_match("/^[0-9]{11}/", $landline)) {
      return false;
    } else {
      return true;
    }
  }
  public function deleteUserData($value = '')
  {
    $response = array('status' => 500, 'msg' => 'Some Internal Error');
    $id = $this->input->post('id');
    if (!empty($id)) {
      $this->db->where('id', $id);
      $this->db->update(
        'user_info',
        array('is_deleted' => 1),
      );
      $response = array('status' => 200, 'msg' => 'Data Deleted Sucessfully');
    }
    echo json_encode($response);
  }

  public function getViewDirectory()
  {
    if ($this->input->is_ajax_request()) {

      $id = $this->input->post('id');
      $directory_data = [];
      if (!empty($id)) {

        // $increasecount = $this->UserInfo_model->increaseviewCount($id);
        $directory_data = $this->UserInfo_model->getDirectoryDataById($id);
      }

      $data['directory_data'] = $directory_data;
      $this->load->view('user/user_view_modal', $data);
    }
  }



  public function getAllUserInfoDatatable()
  {
    $request = $_REQUEST;
    // echo "<pre>";
    //     print_r($request);
    //     exit;
    $start = $request['start'] ? (int) $request['start'] : (int) 0;
    $length = $request['length'] ? (int) $request['length'] : (int) 0;
    $draw = $request['draw'] ? (int) $request['draw'] : (int) 0;
    $searchFilter = array(
      'userId' => array('type' => 'text', 'value' => isset($request['userId']) ? $request['userId'] : ''),
    );
    // print_r($searchFilter);
    // print_r($_REQUEST);
    $order = [];
    $orderBy = $request['order'] ? $request['order'] : array();
    if (!empty($orderBy)) {
      $columns = array('', 'userId', 'title', 'body',);


      $orderBy = $orderBy[0];
      $columnIndex = $orderBy['column'];
      if (array_key_exists($columnIndex, $columns)) {
        // echo "string";
        if (!empty($columns[$columnIndex])) {
          $column = $columns[$columnIndex];
          $order['order_by'] = $column;
          $order['order_type'] = $orderBy['dir'];
        } else {
          $order['order_by'] = 'userId';
          $order['order_type'] = 'Asc';
        }
      } else {
        $order['order_by'] = 'userId';
        $order['order_type'] = 'Asc';
      }
    }
    // print_r($request);
    $directory_info = $this->UserInfo_model->getAllUserInfoData($draw, $searchFilter, array($start, $length), $order);
    $directory_info = json_encode($directory_info);
    echo ($directory_info);
  }

  public function getSearchViewDash()
  {
    $result = $this->UserInfo_model->getSearchFormData();
    $user_id = array_unique(array_column($result, 'userId'));
    $this->load->view('user/search_form_dash', array('user_id' => $user_id));
  }

  public function exportJsonDataToSql()
  {
    $json_data = file_get_contents("https://jsonplaceholder.typicode.com/posts");
    $jsonToArray = json_decode($json_data, true);
    // echo "<pre>";
    foreach ($jsonToArray as $key => $value) {
      unset($value['id']);
      // print_r($value);
      // $this->db->insert('user_info',$value);
    }
  }

  public function getExcelUserInfoSingle()
  {

    if ($this->input->is_ajax_request()) {


      $id = $this->input->post('id');
      $user_data = [];
      if (!empty($id)) {
        $user_data = $this->UserInfo_model->getUserDataById($id);
      }
      $data[] = $user_data;
      // print_r($data);
      $this->getExcelSheet($data);
    }
  }

  private function getExcelSheet($data = [])
  {

    $this->load->library('excel');
    $objPHPExcel = new PHPExcel();
    $fileName = 'data-' . time() . '.xlsx';
    $objPHPExcel->setActiveSheetIndex(0);
    // set Header
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'UserId');
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Title');
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Body');
    // set Row
    $rowCount = 2;
    // print_r($empInfo);
    // exit;
    foreach ($data as $element) {
      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $element['userId']);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $element['title']);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $element['body']);
      $rowCount++;
    }
    header('Content-Type: application/vnd.ms-excel');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save('php://output');
  }

  public function getExcelInfoMultipleUser()
  {
    if ($this->input->is_ajax_request()) {


      $id = $this->input->post('id');
      $ids_list = array_filter($id);
      $user_data = [];
      if (!empty($ids_list)) {
        $user_data = $this->UserInfo_model->getUserDataByIdList($ids_list);
      }
      $this->getExcelSheet($user_data);
    }
  }
}
