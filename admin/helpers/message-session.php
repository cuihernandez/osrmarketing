      <?php
      if (isset($_SESSION['action']) && !empty($_SESSION['action'])) {
        if ($_SESSION['action'] === 'success') {
          echo '<div class="alert alert-success"><i class="bi bi-check-circle fs-4"></i> ' . $_SESSION['action_message'] . '</div>';
        } else {
          echo '<div class="alert alert-danger"><i class="bi bi-exclamation-octagon fs-4"></i> ' . $_SESSION['action_message'] . '</div>';
        }
      }
      ?>