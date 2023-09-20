    </main>
    
  </div>
</div>

<?php 
  if (isset($_SESSION['action']) && isset($_SESSION['action_message'])) {
    unset($_SESSION['action']);
    unset($_SESSION['action_message']);
  }
?>
  <script type="text/javascript">
    let GOOGLE_TRANSLATE_API_KEY = "<?php echo $config->google_cloud_translation_api_key; ?>";
  </script>
  <script src="<?php echo $base_url; ?>/admin/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="<?php echo $base_url; ?>/admin/js/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/marked@2.1.3/marked.min.js"></script>
  <script src="<?php echo $base_url; ?>/admin/js/app.js?v=3"></script> 
  <script src="<?php echo $base_url; ?>/admin/js/jquery.dataTables.min.js"></script>
  <script src="<?php echo $base_url; ?>/admin/js/dataTables.bootstrap5.min.js"></script>
  <script src="<?php echo $base_url; ?>/admin/js/toastr.min.js"></script>
  <script src="<?php echo $base_url; ?>/js/sse.js"></script>
  <?php if(isset($use_sortable) && $use_sortable){?>
  <script src="<?php echo $base_url; ?>/admin/js/sortable.min.js"></script>
  <?php } ?>
  <?php if(isset($use_codemirror) && $use_codemirror){?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1/codemirror.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.61.1/mode/javascript/javascript.min.js"></script>
  <script type="text/javascript">
    var myCodeMirrors = [];

    document.addEventListener("DOMContentLoaded", function() {
      var formatCodeElements = document.getElementsByClassName("format-code");

      for (var i = 0; i < formatCodeElements.length; i++) {
        myCodeMirrors.push(CodeMirror.fromTextArea(formatCodeElements[i], {
          lineNumbers: true,
          mode: "javascript"
        }));
      }
    });

  </script>      
  <?php } ?>

  <script src="https://cdn.tiny.cloud/1/3i0f55gz2j6agr826szltx2eb13a81g30uexvg0920fkhga0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <?php if(isset($use_select2) && $use_select2){?>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
  <script type="text/javascript">
    $("#multiple-select-field").select2({
    theme: "bootstrap-5",
    containerCssClass: "select2--medium", 
    selectionCssClass: "select2--large",
    dropdownCssClass: "select2--medium",
    });    
  </script>
  <?php } ?>

  <style>
    .tox-notification{
      display: none !important;
    }
  </style>
  <script type="text/javascript">
  $(document).ready(function() {

  $('.data-table').DataTable({
      pageLength: 50,
      lengthMenu: [[50, 100, 150, 200, 250, 300], [50, 100, 150, 200, 250, 300]],
      columnDefs: [
          { orderable: true, targets: 3 }
      ],
      order: function() {
          var orderCol = $('[data-order-by="order-col"]');
          if (orderCol.length > 0) {
              return [[orderCol.index(), 'desc']];
          } else {
              return [[0, 'desc']];
          }
      }()
  });

  tinymce.init({
    selector: '.editor',
    relative_urls: false,
    valid_elements: "*[*]",
    plugins: 'ai tinycomments mentions anchor autolink charmap codesample code emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat mybutton | codesample | code',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
    setup: function (editor) {
      editor.ui.registry.addButton('mybutton', {
        text: 'Picture Library',
        icon: 'image',
        onAction: function (_) {
          window.KCFinder = {};
          window.KCFinder.callBack = function(url) {
            window.KCFinder = null;
            editor.insertContent('<img src="' + url + '">');
          };
          window.open('/admin/js/plugins/kcfinder/browse.php?type=images', 'kcfinder_textbox',
            'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
            'resizable=1, scrollbars=0, width=800, height=600'
          );
        }
      });
    }
  });


  $('#openKCFinder').click(function(event) {
    event.preventDefault();
    window.KCFinder = {};
    window.KCFinder.callBack = function(url) {
      window.KCFinder = null;
      tinymce.activeEditor.insertContent('<img src="' + url + '">');
    };
    window.open('/admin/js/plugins/kcfinder/browse.php?type=images', 'kcfinder_textbox',
      'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
      'resizable=1, scrollbars=0, width=800, height=600'
    );
  });

});

  </script>



  </body>
</html>