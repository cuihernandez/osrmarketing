<?php
$module_name = "sales";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-receipt fs-3'></i> Sales", $module_name,"");
$get = $customer_credits_packs->getPurchases();
require_once(__DIR__."/../../helpers/message-session.php");
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

          <div class="alert alert-light">
            <h6 class="mb-0"><i class="bi bi-info-circle-fill"></i> The administrator must manually approve payments made through bank deposits.</h6>
          </div>
          <table class="table table-striped border data-table align-middle">
            <thead>
              <tr>
                <th>Package</th>
                <th>Customer</th>
                <th>Price</th>
                <th>Credits</th>
                <th scope="col" data-order-by="order-col">Purchase date</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($get as $show) {?>
              <tr>
                <td><?php echo $show->product_name; ?></td>
                <td><?php echo truncateText($show->name,15); ?></td>
                <td><?php echo $show->price_label; ?></td>
                <td><?php echo number_format($show->credit_amount, 0, ',', '.'); ?></td>
                <td><?php echo $show->purchase_date; ?></td>
                <td><?php if($show->payment_method == "stripe") echo "<i class='bi bi-stripe fs-5'></i> Stripe"; else echo "<i class='bi bi-bank fs-5'></i> Bank deposit"; ?></td>
                <td>
                  <span class="badge_id_<?php echo $show->id_order; ?> badge rounded-pill <?php if($show->status === 'succeeded') {echo 'bg-success';} else if($show->status === 'processing') {echo 'bg-primary';} else {echo 'bg-danger';}?>"><?php echo $show->status; ?></span>
                </td>
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


<?php
require_once("../../inc/footer.php");
?>