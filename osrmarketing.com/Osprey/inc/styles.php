<?php if($config->use_custom_color_theme){?>
<style type="text/css">
  header {
    background: linear-gradient(180deg, <?php echo $theme_skin['header_background_color1']; ?> 0%, <?php echo $theme_skin['header_background_color2']; ?> 100%);
    border-bottom: 1px solid <?php echo $theme_skin['header_border_bottom']; ?>;
  }
  #hero .btn{
    background: linear-gradient(180deg, <?php echo $theme_skin['hero_button_background_color1']; ?> 0%, <?php echo $theme_skin['hero_button_background_color2']; ?> 100%);
    color: <?php echo $theme_skin['hero_button_text_color']; ?>;
  }      
  #hero .btn:hover {
    box-shadow: 0px 1px 19px <?php echo $theme_skin['hero_button_background_color_hover']; ?>;
  }
  #hero h1, #hero p {
    color: <?php echo $theme_skin['hero_home_text_color']; ?>;
  }
  footer {
    background: linear-gradient(180deg, <?php echo $theme_skin['footer_background_color1']; ?> 0%, <?php echo $theme_skin['footer_background_color2']; ?> 100%);
  }   
  footer ul li, footer ul li a, footer p {
    color: <?php echo $theme_skin['footer_text_color']; ?>;
  }   
  footer ul li a:hover {
    color: <?php echo $theme_skin['footer_text_color_hover']; ?>;
  }
  .btn-sign-up {
    background: <?php echo $theme_skin['btn_sign_up_background_color']; ?>;
    color: <?php echo $theme_skin['btn_sign_up_text_color']; ?>;
    border: 1px solid <?php echo $theme_skin['btn_sign_up_border_color']; ?>;
  }      
  .btn-sign-up:hover, .btn-sign-up:active, .btn-sign-up:focus {
    background: <?php echo $theme_skin['btn_sign_up_background_color_hover']; ?> !important;
    color: <?php echo $theme_skin['btn_sign_up_text_color_hover']; ?> !important;
    border: 1px solid  <?php echo $theme_skin['btn_sign_up_border_color_hover']; ?> !important;
  }
  .btn-sign-in {
    background: <?php echo $theme_skin['btn_sign_in_background_color']; ?>;
    color: <?php echo $theme_skin['btn_sign_in_text_color']; ?>;
    border: 1px solid <?php echo $theme_skin['btn_sign_in_border_color']; ?>;
  }      
  .btn-sign-in:hover, .btn-sign-in:active, .btn-sign-in:focus {
    background: <?php echo $theme_skin['btn_sign_in_background_color_hover']; ?> !important;
    color: <?php echo $theme_skin['btn_sign_in_text_color_hover']; ?> !important;
    border: 1px solid  <?php echo $theme_skin['btn_sign_in_border_color_hover']; ?> !important;
  }      
  .primary-menu li a {
    color: <?php echo $theme_skin['header_menu_links_color']; ?> !important;
  }
  .primary-menu li a:hover {
    color: <?php echo $theme_skin['header_menu_links_color_hover']; ?> !important;
  }
  .primary-menu li a.nav-link-effect::before {
    background-color: <?php echo $theme_skin['header_menu_links_color_effect_hover']; ?>;
  }
  .navbar-expand-lg .navbar-nav .dropdown-menu a {
    color: <?php echo $theme_skin['header_menu_links_dropdown_color']; ?>;
  }
  #inner-page {
    background: <?php echo $theme_skin['header_inner_page_background_color']; ?>;
  }
  #inner-page h3, #inner-page h1 {
    color: <?php echo $theme_skin['header_inner_page_text_color']; ?>;
  }
  .offcanvas-custom {
    background: <?php echo $theme_skin['mobile_background_color']; ?>;
  }
  header .btn-close span, header .bi-list {
    color: <?php echo $theme_skin['mobile_btn_close_color']; ?>;
  }
  .btn-primary {
    background: <?php echo $theme_skin['btn_primary_background_color']; ?>;
    color: <?php echo $theme_skin['btn_primary_text_color']; ?>;
  }      
  .btn-primary:hover, .btn-primary:active, .btn-primary:focus {
    background: <?php echo $theme_skin['btn_primary_background_color_hover']; ?> !important;
    color: <?php echo $theme_skin['btn_primary_text_color_hover']; ?> !important;
  }  

</style>  
<?php } ?>

<style type="text/css">
<?php 
  echo $config->custom_code_css;
?>
</style>

<?php if($dir == "rtl") { ?>
  <style type="text/css">
    .offcanvas-custom.show {
      visibility: visible;
    }
    @media (min-width: 992px){
      .offcanvas-custom {
        position: inherit;
        display: block;
        bottom: inherit;
        left: inherit;
        visibility: visible;
        z-index: inherit;
        max-width: inherit;
        transform: inherit;     
        width: auto;   
        background: transparent;
        border: 0;
      }
    }        
  </style>
<?php } ?>