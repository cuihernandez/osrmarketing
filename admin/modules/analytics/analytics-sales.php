<?php 
  $getSales = $analytics->getSales($limit, $startDate, $endDate);
?>

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

  <div class="table-responsive">
    <table class="table table-striped border data-table align-middle">
      <thead>
        <tr>
          <th scope="col">Purchase data</th>
          <th scope="col">Package name</th>
          <th scope="col">Customer</th>
          <th scope="col">Price</th>
          <th scope="col">Credits</th>
          <th scope="col">Payment Method</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if(!$getSales){
          echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon fs-4"></i>No results found</div>';
          exit();
        }
        foreach ($getSales as $showSales) {?>
          <tr>
            <td><?php echo $showSales->purchase_date; ?></td>
            <td><?php echo $showSales->pack_name; ?></td>
            <td><?php echo $showSales->name; ?></td>
            <td><?php echo $showSales->price_label; ?></td>
            <td><?php echo $showSales->credit_amount; ?></td>
            <td><?php if($showSales->payment_method == "stripe") echo "<i class='bi bi-stripe fs-5'></i> Stripe"; else echo "<i class='bi bi-bank fs-5'></i> Bank deposit"; ?></td>
            <td>
              <span class="badge_id_<?php echo $showSales->id_order; ?> badge rounded-pill <?php if($showSales->status === 'succeeded') {echo 'bg-success';} else if($showSales->status === 'processing') {echo 'bg-primary';} else {echo 'bg-danger';}?>"><?php echo $showSales->status; ?></span>
            </td>       
            <td class="text-end"><button data-bs-toggle="modal" data-bs-target="#modalPaymentCustomer" onclick="checkOrderDetails('<?php echo $showSales->id_order; ?>')" class="btn btn-sm btn-success"><i class="bi bi-plus-circle fs-6"></i></button></td>     
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>