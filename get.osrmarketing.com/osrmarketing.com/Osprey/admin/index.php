<?php 
require_once("inc/restrict.php");
require_once("inc/includes.php");
require_once("inc/header.php");
$countPrompts = $analytics->getByTable("prompts")->rowCount();
$countCustomers = $analytics->getByTable("customers")->rowCount();
$countSales = $analytics->getByTable("customer_credits_packs")->rowCount();
$countCategories = $analytics->getByTable("categories")->rowCount();
$mostChatPrompts = $analytics->getMostChatPrompts(8);
$get = $credits_packs->getList()->FetchAll();
?>

<div class="row mt-3">

	<div class="col-12"><h5>Resume</h5></div>

	<div class="col-md-6 col-lg-3 mb-3">
	    <div class="card">
	        <div class="card-body">
	            <div class="float-end">
	                <i class="bi bi-cpu fs-3"></i>
	            </div>
	            <h5 class="text-muted fw-normal mt-0" title="Number of Prompts">Prompts</h5>
	            <h3 class="mt-3 mb-3"><?php echo $countPrompts; ?></h3>
	            <a href="<?php echo $base_url."/admin/prompts/add"; ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add new</a>
	        </div>
	    </div>
	</div>

	<div class="col-md-6 col-lg-3 mb-3">
	    <div class="card">
	        <div class="card-body">
	            <div class="float-end">
	                <i class="bi bi-people fs-3"></i>
	            </div>
	            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customers</h5>
	            <h3 class="mt-3 mb-3"><?php echo $countCustomers; ?></h3>
	            <a href="<?php echo $base_url."/admin/customers"; ?>" class="btn btn-primary"><i class="bi bi-person-lines-fill"></i> View list</a>
	        </div>
	    </div>
	</div>

	<div class="col-md-6 col-lg-3 mb-3">
	    <div class="card">
	        <div class="card-body">
	            <div class="float-end">
	                <i class="bi bi-receipt fs-3"></i>
	            </div>
	            <h5 class="text-muted fw-normal mt-0" title="Number of Sales">Sales</h5>
	            <h3 class="mt-3 mb-3"><h3 class="mt-3 mb-3"><?php echo $countSales; ?></h3></h3>
	            <a href="<?php echo $base_url."/admin/sales"; ?>" class="btn btn-primary"><i class="bi bi-search"></i> View Sales</a>
	        </div>
	    </div>
	</div>

	<div class="col-md-6 col-lg-3 mb-3">
	    <div class="card">
	        <div class="card-body">
	            <div class="float-end">
	                <i class="bi bi-receipt fs-3"></i>
	            </div>
	            <h5 class="text-muted fw-normal mt-0" title="Number of Categories">Categories</h5>
	            <h3 class="mt-3 mb-3"><h3 class="mt-3 mb-3"><?php echo $countCategories; ?></h3></h3>
	            <a href="<?php echo $base_url."/admin/categories"; ?>" class="btn btn-primary"><i class="bi bi-search"></i> View Categories</a>
	        </div>
	    </div>
	</div>

	<div class="col-12 d-flex align-items-center mb-3">
		<h5>Most popular prompts</h5> <a href="<?php echo $base_url."/admin/analytics?filterType=prompts"; ?>" class="badge bg-primary text-decoration-none ms-2"><i class="bi bi-search"></i> View All</a>
	</div>

	<?php foreach ($mostChatPrompts as $showMostChatPrompts) {?>
	<div class="col-md-6 col-lg-3 mb-3">
		<div class="card resume-card mb-3">
		  <div class="row g-0">
		    <div class="col-lg-4 col-md-5">
		      <div class="wrapper-prompt-img-chats">
		      	<img src="<?php echo $base_url; ?>/public_uploads/<?php echo $showMostChatPrompts->image; ?>" onerror="this.src='<?php echo $base_url; ?>/admin/img/placeholder.jpg'">
		      </div>
		    </div>
		    <div class="col-lg-8 col-md-7">
		      <div class="card-body">
		      	<span class="badge bg-success"><?php echo $showMostChatPrompts->total_conversations; ?> conversations</span></h3>
		        <h6 class="card-title"><?php echo $showMostChatPrompts->name; ?></h6>
		        <p class="card-text card-expert"><?php echo $showMostChatPrompts->expert; ?></p>
		        <td><a href="<?php echo $base_url."/chat/".$showMostChatPrompts->slug; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i> Chat</a></td>
		      </div>
		    </div>
		  </div>
		</div>	
	</div>
	<?php } ?>

	<div class="col-12 col mt-3">
	<?php 
		if(!$config->openai_api_key){
			echo "<div class='alert alert-danger'><i class='bi bi-exclamation-octagon fs-4'></i> Attention, you need to configure your openAI KEY api. Go to settings menu</div>";
		}
	?>
	</div>

</div>

<?php
require_once("inc/footer.php");
?>