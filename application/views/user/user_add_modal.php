<div class="main_notify"></div>
<form action="" method="POST" id="candidate_add_form">
   <input type="hidden" name="id" value="<?php echo  isset($directory_data['id']) ? $directory_data['id'] : ''; ?>">
   <div class="row">
      <div class="col-md-6">

         <div class="form-group">
            <label for="exampleInputEmail1">UserId</label>

            <select class="form-control" name="userId">
               <option value="">--Select Any One-- </option>
               <?php foreach ($userId as $key => $value) { ?>
                  <option value="<?php echo $value ?>" <?php if ($value == $directory_data['userId']) {
                                                   echo 'selected';
                                                } ?>><?php echo $value ?> </option>
               <?php } ?>
            </select>
         </div>

      </div>
      <div class="col-md-6">
         <div class="form-group">
            <label for="exampleInputEmail1">Title</label>
            <input type="text" class="form-control" id="title" aria-describedby="emailHelp" name="title" value="<?php echo  isset($directory_data['title']) ? $directory_data['title'] : ''; ?>" placeholder="Enter Middle name">
         </div>

      </div>
   </div>
   <div class="row">
      <div class="col-md-12">

         <div class="form-group">
            <label for="exampleInputEmail1">Body</label>
            <textarea class="form-control" name="body"> <?php echo  isset($directory_data['body']) ? $directory_data['body'] : ''; ?> </textarea>
         </div>
      </div>

   </div>

   <div class="row">
      <div class="col-md-4"> </div>
      <div class="col-md-4">
         <input type="submit" class="btn btn-primary"></input>
      </div>
   </div>
   </div>
</form>