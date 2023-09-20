<?php
$module_name = "analytics";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-bar-chart fs-3'></i> Analytics", $module_name,"");
$get = $customer_credits_packs->getPurchases();
require_once(__DIR__."/../../helpers/message-session.php");

?>

      
        <form action="/admin/analytics" method="get" novalidate>
          
          <div class="row">
              <div class="col-md-3">
                <div class="form-floating mb-3">
                  <select name="filterType" class="form-control" id="floatingFilterType" required>
                    <?php
                    $options = [
                      "Select" => "",
                      "Prompt chats" => "prompts",
                      "Registered customers" => "customers",
                      "Sales" => "sales"
                    ];

                    $selectedPenalty = isset($_GET['filterType']) ? $_GET['filterType'] : 'select';

                    foreach ($options as $label => $value) {
                      $selected = $value == $selectedPenalty ? 'selected' : '';
                      echo "<option value='$value' $selected>$label</option>";
                    }
                    ?>
                  </select>                 
                  <label for="floatingFilterType">Select filter type</label>
                </div>
              </div>

              <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <select name="limit" class="form-control" id="floatingLimit" required>
                          <?php
                          $limits = [
                              "All" => "all",
                              "5" => "5",
                              "10" => "10",
                              "15" => "15",
                              "50" => "50",
                              "100" => "100"
                          ];

                          $selectedLimit = isset($_GET['limit']) ? $_GET['limit'] : '';

                          foreach ($limits as $label => $value) {
                              $selected = $value == $selectedLimit ? 'selected' : '';
                              echo "<option value='$value' $selected>$label</option>";
                          }
                          ?>
                      </select>                 
                      <label for="floatingLimit">Select limit</label>
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <input type="date" class="form-control" id="startDate" name="startDate" 
                      value="<?php echo isset($_GET['startDate']) ? htmlspecialchars($_GET['startDate'], ENT_QUOTES, 'UTF-8') : date('Y-m-01'); ?>">
                      <label for="startDate">Start Date</label>
                  </div>
              </div>

              <div class="col-md-3">
                  <div class="form-floating mb-3">
                      <input type="date" class="form-control" id="endDate" name="endDate" 
                      value="<?php echo isset($_GET['endDate']) ? htmlspecialchars($_GET['endDate'], ENT_QUOTES, 'UTF-8') : date('Y-m-d'); ?>">
                      <label for="endDate">End Date</label>
                  </div>
              </div>


          </div>           

          <div class="d-grid">
            <button class="btn btn-success text-uppercase fw-bold mb-2 submit-button" type="submit">Filter</button>
          </div>

        </form>


<?php
$startDate = isset($_GET['startDate']) ? $_GET['startDate'] : null;
$endDate = isset($_GET['endDate']) ? $_GET['endDate'] . ' 23:59:59' : null;
$limit = isset($_GET['limit']) ? $_GET['limit'] : null;

//Filter prmopts
if (isset($_GET['filterType']) && $_GET['filterType'] == "prompts") {
  require_once("analytics-prompts.php");
}

//Filter customers
if (isset($_GET['filterType']) && $_GET['filterType'] == "customers") {
  require_once("analytics-customers.php");
}

//Filter sales
if (isset($_GET['filterType']) && $_GET['filterType'] == "sales") {
  require_once("analytics-sales.php");
}


?>



<?php
require_once("../../inc/footer.php");
?>