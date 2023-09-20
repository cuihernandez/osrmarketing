				<div class="d-grid gap-2 justify-content-center d-flex">
				  <button class="btn btn-success fw-bold mb-2 submit-button" type="submit"><i class="bi bi-check-circle"></i> Save</button>
				  <?php if(@$_REQUEST['action'] == "edit"){?>
				  <button class="btn btn-success fw-bold mb-2 submit-button-ajax" type="submit"><i class="bi bi-arrow-clockwise"></i> Save and Stay</button>
					<?php } ?>
				</div>

	       <input type="hidden" name="id" value="<?php echo @$edit ? $get->id : ''; ?>">
	       <input type="hidden" name="action" value="<?php echo @$edit ? 'edit' : 'add'; ?>">
	      </form>
      </div>

 
			<div id="formErrorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
			  <div class="d-flex">
			    <div class="toast-body">
			      <i class="bi bi-exclamation-octagon"></i>Attention: Please check all mandatory fields.
			    </div>
			    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			  </div>
			</div>