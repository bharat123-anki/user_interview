<form action="" method="POST" id="searchUserForm">
  <div class="row">

    <div class="form-group col-md-4">
      <label for="exampleInputEmail1">UserId</label>
      <select name="userId" class="form-control userId" id="userId">
        <option value="">--Select Any One--</option>
        <?php foreach (array_unique($user_id) as $key => $value) { ?>
          <option value="<?php echo $value ?>"><?php echo $value;  ?></option>
        <?php } ?>
      </select>
    </div>


    <div class="form-group col-md-4">
      <label for="exampleInputEmail1">&nbsp;</label>
      <label for="exampleInputEmail1">&nbsp;</label>
      <label for="exampleInputEmail1">&nbsp;</label>
      <input type="submit" class=" btn btn-primary addSearchForm"  />
    </div>
  </div>

</form>

<script type="text/javascript">
  $(document).ready(function() {

    $('.userId').select2();
  })
</script>