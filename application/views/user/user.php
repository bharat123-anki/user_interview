<?php $this->load->view('template/header.php');    ?>
<style type="text/css">
   .addColor {
      background-color: red;
   }
</style>

<body class="hold-transition skin-blue sidebar-mini">
   <div class="wrapper">

      <?php $this->load->view('template/navigation.php') ?>
      <?php $this->load->view('template/sidebar.php');    ?>
      <!-- Left side column. contains the logo and sidebar -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
         <!-- Content Header (Page header) -->
         <div class="content-header">
            <div class="container-fluid">
               <div class="row mb-2">
                  <div class="col-sm-6">
                     <h1 class="m-0 text-dark">Directory</h1>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                     <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="javascript:void(0)" class="add_candidate_form"></a></li>
                     </ol>
                  </div><!-- /.col -->
               </div><!-- /.row -->
            </div><!-- /.container-fluid -->
         </div>

         <!-- Main content -->
         <section class="content">
            <!-- Info boxes -->

            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Search Form</h3>
                  <!-- /.card-tools -->
               </div>
               <!-- /.card-header -->
               <div class="card-body">
                  <div id="search_form_dash"> </div>
               </div>
               <!-- /.card-body -->

               <!-- /.card-footer -->
            </div>
            <div class="card">
               <div class="card-header">
                  <h3 class="card-title">Directory Listings</h3>
                  <div style="float: right;">
                     <button type="button" id="btn_export_user_data" name="btn_export_user_data" class="btn btn-success disp-b"><i class="fa fa-file"></i>&nbsp;Export To Excel</button>
                  </div>
                  <div class="card-tools">
                     <!-- Buttons, labels, and many other things can be placed here! -->
                     <!-- Here is a label for example -->

                  </div>
                  <!-- /.card-tools -->
               </div>
               <!-- /.card-header -->
               <div class="card-body">


                  <div class="table-responsive-sm table-responsive-md table-responsive">
                     <!-- begin: Datatable  -->
                     <table class="table table-striped- table-bordered table-hover table-checkable directory_data" id="directory_data">
                        <thead>
                           <tr>
                              <th scope="col">#</th>
                              <th scope="col">UserId</th>
                              <th scope="col">Title</th>
                              <th scope="col">Body</th>
                              <th scope="col">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                        </tbody>
                     </table>
                     <!--end: Datatable -->
                  </div>



                  <!-- 
          <div id="directory_data">

          </div> -->
               </div>
               <!-- /.card-body -->
               <div class="card-footer">
               </div>
               <!-- /.card-footer -->
            </div>

            <!-- /.row -->
         </section>
         <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <div id="addDirectoryModal" class="modal fade" tabindex="-1">
               <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title"> User Info</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                     </div>
                     <div class="modal-body">
                        <div id="modalData"></div>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                     </div>
                  </div>
               </div>
            </div>

         </div><!-- /.container-fluid -->
         <!-- /.content -->
      </div>

      <!-- /.content-wrapper -->



      <!-- Control Sidebar -->

      <!-- /.control-sidebar -->
      <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>

   </div>
   <!-- ./wrapper -->
   <?php $this->load->view('template/footer.php');    ?>
</body>

</html>
<script src="<?php echo base_url() ?>assets/js/custom.js"></script>
<script type="text/javascript">
   $(document).ready(function() {
      getSearchFormOnUser()
      var checkboxSelected = [];
      var oTable;
      oTable = $('#directory_data').dataTable({
         searching: false,
         processing: true,
         serverSide: true,
         ajax: 'getAllDirectoryInfoDatatable',

         columnDefs: [{
               "render": function(data, type, row) {

                  return '<a href="javascript:void(0)" class="candidateEditdata" data-id="' + data + '"><i class="fa fa-edit"></i> </a>|<a href="javascript:void(0)" class="candidatedeletedata" data-id="' + data + '"><i class="fa fa-trash"></i></a>|<a href="javascript:void(0)" class="candidatefileView" data-id="' + data + '"><i class="fa fa-file"></i></a>'
               },
               "targets": 4,
            },
            {
               "render": function(data, type, row) {
                  // return 'bharat';
                  var chekedOption = "checked";
                  if ($.inArray(row[4], checkboxSelected) == -1) {
                     chekedOption = "";
                  }
                  return '<input type="checkbox" name="rowchk" class="checkbox" id="' + row[4] + '"  ' + chekedOption + ' />'
               },
               "targets": 0,
            },
         ],
         "fnDrawCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {

            $("input[name=rowchk]").click(function(event) {
               var iId = event.target.id;

               if (checkboxSelected.indexOf(iId) == -1) {
                  checkboxSelected.push(iId)
                  // checkboxSelected[checkboxSelected.length++] = iId;
               } else {
                  var newArray = checkboxSelected.filter((i, index) => {
                     return i !== iId;
                  })
                  checkboxSelected = newArray
               }
               // console.log(checkboxSelected);

            });
         },
      })

      $('body').on('click', '#btn_export_user_data', function() {

         if (checkboxSelected.length === 0) {
            alert("Please Select Any Checkbox")
         } else {
            $.ajax({
               type: 'POST',
               url: 'getExcelInfoMultipleUser',
               data: {
                  id: checkboxSelected
               },
               success: function(resp) {
                  csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(resp);
                  var link = document.createElement("a");
                  link.setAttribute("href", csvData);
                  link.setAttribute("download", "user_list_data.csv");
                  document.body.appendChild(link); // Required for FF
                  link.click();

                  checkboxSelected = [];
                  var table = $('#directory_data').dataTable();
                  var passData = "?" + $('#searchUserForm').serialize();
                  table.fnReloadAjax("getAllDirectoryInfoDatatable" + passData);
               }
            })
         }
      })
   })
   $('body').on('submit', '#searchUserForm', function(e) {
      e.preventDefault();
      var table = $('#directory_data').dataTable();
      var passData = "?" + $('#searchUserForm').serialize();
      table.fnReloadAjax("getAllDirectoryInfoDatatable" + passData);
      // getAllDirectoryInfo(formData)
   })
</script>