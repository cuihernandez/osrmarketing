<!-- Recaptcha Scripts -->
<?php if(isset($use_recaptcha) && $use_recaptcha && $config->use_recaptcha): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo htmlspecialchars($config->recaptcha_public_key); ?>"></script>
    <script type="text/javascript">
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo htmlspecialchars($config->recaptcha_public_key); ?>', {action: 'submit'}).then(function(token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });        
    </script>
<?php endif; ?>

<script type="text/javascript">
    window.addEventListener('load', async () => {
        await fetchLanguageData();
        <?php if(isset($loadAI) && $loadAI): ?>
            fetchLoadData(<?php echo htmlspecialchars($AI_ID); ?>);
        <?php endif; ?>
    });
</script>


<?php
//Google Analytics 
if(isset($config->use_google_analytics) && $config->use_google_analytics):
    echo $config->google_analytics_code;
endif; ?>

<?php 
//Custom JS code
if(isset($config->use_custom_code) && $config->use_custom_code):
    echo $config->custom_code;
endif; ?>

<?php 
if(isset($config->allow_sticky_header) && $config->allow_sticky_header):?>      
<script type="text/javascript">
    //sticky header
    const header = document.querySelector('header');
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 130 && document.documentElement.scrollHeight > 1200) {
            if (!header.classList.contains('sticky-header')) {
                header.style.top = "0";
                header.classList.add('sticky-header');
            }
        } else if (window.pageYOffset < 120) {
            header.style.transition = "top 0.5s ease, padding 0.5s ease"; 
            header.style.top = "0";
            header.classList.remove('sticky-header');
        }
    });                 
</script>
<?php endif; ?>