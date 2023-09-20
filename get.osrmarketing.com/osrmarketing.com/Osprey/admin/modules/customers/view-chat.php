<?php
$module_name = "customers";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "view-chat") {
	$getCustomer = $customers->getCustomer(addslashes($_REQUEST['id']));
}
$getCustomerChat = $messages->getPromptByIdUser($getCustomer->id);
$getCustomerChatCount = $getCustomerChat->rowCount();
?>

		<div class="modal modal-xl fade" id="modalMessages" tabindex="-1" aria-labelledby="modalMessagesLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modalMessagesLabel">Conversation history</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <div class="modal-body">

				<div id="overflow-chat">
                    
         </div>
		      	
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>


      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">User messages: <?php echo $getCustomer->name; ?></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
			<a href="<?php echo $base_url; ?>/admin/customers" class="btn btn-danger btn-primary">Back</a>
        </div>
      </div>

		  <div class="row">
		    <div class="col-md-12 table-responsive">
		      <table class="table border">
		        <thead>
		          <tr>
		            <th>Name:</th>
		            <th>Email:</th>
		            <th>Registration date:</th>
		            <th>Remain credits:</th>
		            <th>Credits spent:</th>
		            <th>Status:</th>
		          </tr>
		        </thead>
		        <tbody>
		          <tr>
		            <td><?php echo $getCustomer->name; ?></td>
              	<?php if($config->demo_mode){?>	
              	<td><span class="badge bg-danger">Email will not be shown in demo mode</span></td>
              	<?php }else{ ?>   		            
		            <td><a href="mailto:<?php echo $getCustomer->email; ?>"><?php echo $getCustomer->email; ?></a></td>
		          	<?php } ?>
		            <td><?php echo $getCustomer->created_at; ?></td>
		            <td><?php echo $getCustomer->credits; ?></td>
		            <td><?php echo $getCustomer->total_credits_spend; ?></td>
		            <td>
		              <?php if ($getCustomer->status == 1) { ?>
		                <span class="badge bg-success">Active</span>
		              <?php } else { ?>
		                <span class="badge bg-warning text-dark">Inactive</span>
		              <?php } ?>
		            </td>            
		          </tr>
		        </tbody>
		      </table>
		    </div>
		  </div>

      <div class="mt-3">
      	<hr>
      	<h5>Threads and conversations</h5>

		
			<ol class="list-group">
			  <?php foreach ($getCustomerChat as $showCustomerAI) {
			    $getThreads = $messages->getThreadByIdUserAndPrompt($getCustomer->id,$showCustomerAI->id_prompt);
			    $countThreads = $getThreads->rowCount();			  	
			  ?>

				  <li class=" d-flex justify-content-between align-items-start mb-3 border-top alert alert-light">
				    <div class="ms-2 col">
				    	<div class="d-flex align-items-center pt-2 pb-2">
					    	<img src="<?php echo $base_url; ?>/public_uploads/<?php echo $showCustomerAI->image; ?>" alt="@mdo" width="32" height="32" class="rounded me-2" onerror="this.src='<?php echo $base_url; ?>/admin/img/placeholder.jpg'">
					      	<div class="fw-bold"><?php echo $showCustomerAI->name; ?></div>
					      	<span class="badge bg-primary rounded-pill ms-2"><?php echo $showCustomerAI->num_messages - $countThreads; ?> messages</span>
					      	<span class="badge bg-primary rounded-pill ms-2"><?php echo $countThreads; ?> threads</span>
				      	</div>
				      	
				      	<div class="mt-2 mb-2 col-12">

			              <ul class="list-group">
			                <?php 
			                $n = 1; 
			                foreach ($getThreads as $showThreads){
			                	$numMessages = $messages->getByThread($showThreads->id_thread)->rowCount();
			                ?>
			                <li class="list-group-item justify-content-between align-items-center pt-3 pb-3">
			                  <div class="ms-2 mb-2 col">
			                    <strong class="color-blue">Chat <?php echo $countThreads - $n + 1;?></strong><br>
			                    <div class="mt-1"><?php echo htmlspecialchars(truncateText($showThreads->last_message_content,200)); ?></div>
			                    <small><?php echo $showThreads->created_at;?></small>
			                  </div>
			                  <div>		                  	
			                  	<a data-bs-toggle="modal" data-bs-target="#modalMessages" class="btn btn-success btn-sm" href="#" onclick="getMessagesJson('<?php echo $showThreads->id_thread; ?>')"><i class="bi bi-chat-dots"></i><span data-feather="message-square"></span> View (<?php echo $numMessages-1; ?>) messages</a>
													<span class="dropdown">
													  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
													    Options
													  </button>
													  <ul class="dropdown-menu">
						                	<li><a class="dropdown-item" href="<?php echo $base_url; ?>/download-chat/<?php echo $showThreads->slug; ?>/<?php echo $showThreads->id_thread; ?>?format=txt"><i class="bi bi-filetype-txt"></i> Download (.txt)</a></li>
						                	<li><hr class="dropdown-divider"></li>
						                	<li><a class="dropdown-item" href="<?php echo $base_url; ?>/download-chat/<?php echo $showThreads->slug; ?>/<?php echo $showThreads->id_thread; ?>?format=pdf"><i class="bi bi-filetype-pdf"></i> Download (.pdf)</a></li>
						                	<li><hr class="dropdown-divider"></li>
						                	<li><a class="dropdown-item" href="<?php echo $base_url; ?>/download-chat/<?php echo $showThreads->slug; ?>/<?php echo $showThreads->id_thread; ?>?format=docx"><i class="bi bi-filetype-docx"></i> Download (.docx)</a></li>
													  </ul>
													</span>				                  	
			                  </div>
			                </li>
			                <?php $n++;} ?>
			              </ul>  				          	      		
					    </div>

				    </div>
				    
				  </li>

			  <?php } ?>
			</ol>
		
      </div>

<?php
require_once("../../inc/footer.php");
?>