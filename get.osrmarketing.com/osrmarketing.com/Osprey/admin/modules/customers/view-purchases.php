<?php
$module_name = "customers";
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "view-purchases") {
	$getCustomer = $customers->getCustomer(addslashes($_REQUEST['id']));
}
if($getCustomer->id){
  $getPurchases = $customer_credits_packs->getByIdCustomer($getCustomer->id);
  $getPurchasesCount = $getPurchases->rowCount();
}
?>


      <!-- Modal -->
      <div class="modal fade" id="modalPaymentCustomer" tabindex="-1" aria-labelledby="modalPaymentCustomerLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalPaymentCustomerLabel">Purchase details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="purchase-details">
              <div class="spinner-border" role="status"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalConfirmPayment" tabindex="-1" aria-labelledby="modalConfirmPaymentLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalConfirmPaymentLabel">Are you certain that you wish to approve this payment?</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              By approving this payment, the number of credits for the purchase will be credited to the customer's account. Can you confirm this?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
              <button data-order="#" id="aprovePaymentConfirmButton" class="btn btn-success">Yes</button>
            </div>
          </div>
        </div>
      </div>            


      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">User purchase history: <?php echo $getCustomer->name; ?></h1>
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


      <?php if($getPurchasesCount > 0){?>
      <div class="mt-3">
          <table class="table table-striped border data-table align-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Credits</th>
                <th>Date</th>
                <th>Status</th>
                <th>Payment Method</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($getPurchases as $show) {
                $getCreditPack = $credits_packs->get($show->id_credit_pack);
              ?>
              <tr>
                <td class="fw-bold"><?php echo $getCreditPack->name; ?></td>
                <td><?php echo $show->price_label; ?></td>
                <td><?php echo $show->credit_amount; ?></td>
                <td><?php echo $show->purchase_date; ?></td>
                <td>
                  <span class="badge_id_<?php echo $show->id_order; ?> badge rounded-pill <?php if($show->status === 'succeeded') {echo 'bg-success';} else if($show->status === 'processing') {echo 'bg-primary';} else {echo 'bg-danger';}?>"><?php echo $show->status; ?></span>
                </td>
                <td><?php if($show->payment_method == "stripe") echo "<i class='bi bi-stripe fs-5'></i> Stripe"; else echo "<i class='bi bi-bank fs-5'></i> Bank deposit"; ?></td>
                <td class="text-end">
                  <?php if($show->payment_method == "bank_deposit" && $show->status != "succeeded"){?>
                  <button type="button" class="btn btn_<?php echo $show->id_order; ?> btn-primary btn-sm" data-bs-toggle="modal" onclick="prepareOrder('<?php echo $show->id_order; ?>')" data-bs-target="#modalConfirmPayment"  data-id="<?php echo $show->id; ?>"><i class="bi bi-check-lg fs-6"></i></button>
                  <?php } ?>
                  <button data-bs-toggle="modal" data-bs-target="#modalPaymentCustomer" onclick="checkOrderDetails('<?php echo $show->id_order; ?>')" class="btn btn-sm btn-success"><i class="bi bi-plus-circle fs-6"></i></button>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
      </div>
    	<?php }else { ?>
    	<div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> No purchases found for this user.</div>
    	<?php } ?>
		

<?php
require_once("../../inc/footer.php");
?>