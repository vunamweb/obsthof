<!-- Modal edit area -->
<div class="modal fade" id="edit_area" tabindex="-1" aria-labelledby="morpheusModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
		  <div class="container-fluid">
			  <div class="row">
			  	<div class="offset-1 col-10">
				  <img src="<?php echo $dir; ?>morpheus/images/Logo-Morpheus.svg" alt="CMS Morpheus" width="100">	  
				</div>
			  	<div class="col-1 text-right">
        		  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				  </div>
			  </div>
		  </div>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>
<!-- END MODAL -->

<input type="hidden" id="url" value="<?php echo $morpheus["url"] ?>"/>

<div id="morpheus_edit_bar">
	<a class="close_me" href="<?php echo $dir; ?>morpheus/content.php?edit=<?php echo $cid; ?>&db=content"><i class="fa fa-chevron-left"></i>
	<a class="close_me" href="?edit_close=1"><i class="fa fa-coffee"></i>
</div>
