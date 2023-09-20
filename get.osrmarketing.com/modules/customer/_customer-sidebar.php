        <ul class="white-card user-panel-options p-0">
          <li><a <?php if(isset($_REQUEST['url']) && $_REQUEST['url'] == "panel") echo "class='active'"; ?> href="<?php echo $base_url; ?>/panel"><i class="bi bi-files"></i> <?php echo $lang['menu_my_chats']; ?></a></li>
          <li><a <?php if(isset($_REQUEST['url']) && $_REQUEST['url'] == "my-account") echo "class='active'"; ?> href="<?php echo $base_url."/panel/my-account"; ?>"><i class="bi bi-person-circle"></i> <?php echo $lang['menu_my_account']; ?></a></li>
          <li><a <?php if(isset($_REQUEST['url']) && $_REQUEST['url'] == "my-purchases") echo "class='active'"; ?> href="<?php echo $base_url."/panel/my-purchases"; ?>"><i class="bi bi-receipt"></i> <?php echo $lang['menu_my_purchases']; ?></a></li>
          <li><a href="<?php echo $base_url."/logout"; ?>"><i class="bi bi-box-arrow-left"></i> <?php echo $lang['menu_logout']; ?></a></li>
        </ul>  