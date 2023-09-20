<?php 
  $mostChatPrompts = $analytics->getMostChatPrompts($limit, $startDate, $endDate);
?>
  <div class="table-responsive">
    <table class="table table-striped border data-table align-middle">
      <thead>
        <tr>
          <th scope="col">Image</th>
          <th scope="col">Name</th>
          <th scope="col">Slug</th>
          <th scope="col">Expert</th>
          <th scope="col">Chat Customers</th>
          <th scope="col">Total Conversations</th>
          <th scope="col">Last Conversation Date</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        if(!$mostChatPrompts){
          echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon fs-4"></i>No results found</div>';
          exit();
        }
        foreach ($mostChatPrompts as $showMostChatPrompts) {?>
          <tr>
            <td><div class="wrapper-form-img"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $showMostChatPrompts->image; ?>" onerror="this.src='<?php echo $base_url; ?>/admin/img/placeholder.jpg'"></div></td>
            <td><?php echo $showMostChatPrompts->name; ?></td>
            <td><?php echo $showMostChatPrompts->slug; ?></td>
            <td><?php echo $showMostChatPrompts->expert; ?></td>
            <td><?php echo $showMostChatPrompts->unique_customer_count; ?></td>
            <td><?php echo $showMostChatPrompts->total_conversations; ?></td>
            <td><?php echo $showMostChatPrompts->last_conversation_date; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>