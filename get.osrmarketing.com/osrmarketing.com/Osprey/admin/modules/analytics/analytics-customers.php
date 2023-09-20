<?php 
  $getRegistrationUsers = $analytics->getRegisteredCustomers($limit, $startDate, $endDate);
?>
  <div class="table-responsive">
    <table class="table table-striped border data-table align-middle">
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Registration data</th>
          <th scope="col">Credits</th>
          <th scope="col">Threads (chats)</th>
          <th scope="col">Messages</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if(!$getRegistrationUsers){
          echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon fs-4"></i>No results found</div>';
          exit();
        }
        foreach ($getRegistrationUsers as $showRegistrationUsers) {?>
          <tr>
            <td><?php echo $showRegistrationUsers->name; ?></td>
            <?php if($config->demo_mode){?> 
            <td><span class="badge bg-danger">Email will not be shown in demo mode</span></td>
            <?php }else{ ?>
            <td><?php echo $showRegistrationUsers->email; ?></td>
            <?php } ?>
            <td><?php echo $showRegistrationUsers->created_at; ?></td>
            <td><?php echo $showRegistrationUsers->credits; ?></td>
            <td><?php echo $showRegistrationUsers->total_threads; ?></td>
            <td><?php echo $showRegistrationUsers->total_messages; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>