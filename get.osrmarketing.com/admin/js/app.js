function loadPreviewImage(event, previewId) {
  var imageInput = event.target;
  var previewImage = document.getElementById(previewId);

  if (imageInput.files && imageInput.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      previewImage.src = e.target.result;
      previewImage.style.display = "block";
    }
    reader.readAsDataURL(imageInput.files[0]);
  } else {
    previewImage.src = "";
    previewImage.style.display = "none";
  }
}

  //show tool tips
  document.addEventListener('DOMContentLoaded', function () {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
      var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
      })
  })  

  // Confirm delete
  document.addEventListener('DOMContentLoaded', function () {
    var deleteModalElement = document.getElementById('deleteModal');
    // Verifica se o elemento 'deleteModal' existe
    if (deleteModalElement) {
      var deleteModal = new bootstrap.Modal(deleteModalElement);
      var deleteConfirmButton = document.getElementById('deleteConfirmButton');
      var deleteButtons = document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#deleteModal"]');

      deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
          var id = event.currentTarget.getAttribute('data-id');
          var mod = event.currentTarget.getAttribute('data-module');
          deleteConfirmButton.href = '/admin/'+mod+'/delete/' + id;
          deleteModal.show();
        });
      });
    }
  });

  function updateSwitchValue(checkboxId, hiddenInputId) {
      const checkbox = document.getElementById(checkboxId);
      const hiddenInput = document.getElementById(hiddenInputId);
      hiddenInput.value = checkbox.checked ? 1 : 0;
  }


  // Validate Google Voice fields
  document.addEventListener('DOMContentLoaded', function () {
    var useGoogleVoice = document.querySelector('input[name="use_google_voice"]');
    var googleVoice = document.querySelector('input[name="google_voice"]');
    var googleVoiceLangCode = document.querySelector('input[name="google_voice_lang_code"]');

    if (useGoogleVoice) {
      //validateGoogleVoiceFields();
      //useGoogleVoice.addEventListener('change', validateGoogleVoiceFields);
    }

    function validateGoogleVoiceFields() {
      if (useGoogleVoice.checked) { 
        googleVoice.required = true;
        googleVoiceLangCode.required = true;
      } else {
        googleVoice.required = false;
        googleVoiceLangCode.required = false;
      }
    }
  });

  document.addEventListener('DOMContentLoaded', function() {
    var formElement = document.querySelector('form');

    if (formElement) {
      formElement.addEventListener('submit', function (event) {
        scrollToFirstInvalidField();
        if (!event.target.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
          showToast('formErrorToast');
        }
        event.target.classList.add('was-validated');
      });
    }
  });

  function showToast(toastId) {
    var toastElement = document.getElementById(toastId);
    if (toastElement) {
      var toast = new bootstrap.Toast(toastElement);
      toast.show();
    }
  }

  // Validate Mic Speak Lang fields
  document.addEventListener('DOMContentLoaded', function () {
    var displayMic = document.querySelector('#floatingDisplayMicrophone');
    var micSpeakLang = document.querySelector('input[name="mic_speak_lang"]');

    if (displayMic) {
      validateMicSpeakLangFields();
      displayMic.addEventListener('change', validateMicSpeakLangFields);
    }

    function validateMicSpeakLangFields() {
      if (displayMic.checked) {
        micSpeakLang.required = true;
      } else {
        micSpeakLang.required = false;
      }
    }
  });


$(document).ready(function () {
    const submitButton = $('.submit-button');
    const btnSaveAbsolute = $('.btn-save-absolute');

    function isSubmitVisible() {

        if (submitButton.length === 0) {
            return false;
        }
          
        const rect = submitButton[0].getBoundingClientRect();
        const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
        return rect.bottom <= windowHeight;
    }

    // Função para exibir ou ocultar o botão .btn-save-absolute
    function toggleBtnSaveAbsolute() {
        if (isSubmitVisible()) {
            btnSaveAbsolute.fadeOut();
        } else {
            btnSaveAbsolute.fadeIn();
        }
    }

    // Verifique a visibilidade ao carregar a página
    toggleBtnSaveAbsolute();

    // Verifique a visibilidade quando o usuário rolar a página
    $(window).on('scroll', function () {
        toggleBtnSaveAbsolute();
    });
});

$(".btn-save-absolute").on("click", function() {
  if (!$(this).hasClass("submit-button-ajax")) {
    $(".submit-button").click();
  }
});
// Função para rolar até o primeiro campo inválido
function scrollToFirstInvalidField() {
    const firstInvalidField = $('.form-control.is-invalid, .was-validated .form-control:invalid').first();
    if (firstInvalidField.length > 0) {
        $('html, body').animate({
            scrollTop: firstInvalidField.offset().top - 100 // Ajuste o valor "100" para adicionar uma margem superior, se necessário
        }, 500); // Ajuste a duração da animação, se necessário
        firstInvalidField.focus();
    }
}


function submitFormTestSMTP() {
  // Obter os elementos do formulário
  const subjectElement = document.getElementById("subject");
  const emailElement = document.getElementById("email");
  const recipientNameElement = document.getElementById("recipient_name");
  const contentElement = document.getElementById("content");

  // Validação dos campos
  let isValid = true;

  if (!subjectElement.value.trim()) {
    subjectElement.classList.add("is-invalid");
    isValid = false;
  } else {
    subjectElement.classList.remove("is-invalid");
  }

  if (!emailElement.value.trim()) {
    emailElement.classList.add("is-invalid");
    isValid = false;
  } else {
    emailElement.classList.remove("is-invalid");
  }

  if (!recipientNameElement.value.trim()) {
    recipientNameElement.classList.add("is-invalid");
    isValid = false;
  } else {
    recipientNameElement.classList.remove("is-invalid");
  }

  if (!validateEmail(emailElement.value)) {
    emailElement.classList.add("is-invalid");
    isValid = false;
  } else {
    emailElement.classList.remove("is-invalid");
  }

  if (!contentElement.value.trim()) {
    contentElement.classList.add("is-invalid");
    isValid = false;
  } else {
    contentElement.classList.remove("is-invalid");
  }

  if (!isValid) {
    scrollToFirstInvalidField();
    showToast('formErrorToast');
    return;
  }

  // Preparar os dados do formulário
  const formData = new FormData();
  formData.append("subject", subjectElement.value);
  formData.append("email", emailElement.value);
  formData.append("content", contentElement.value);
  formData.append("recipient_name", recipientNameElement.value);

  $("#smtp_test_return").html("Wait...");
  $("#btn-test-smtp-email").attr("disabled",true)
  // Enviar os dados via POST para o arquivo PHP
  fetch("../admin/modules/settings/test-smtp.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(result => {
      // Tratar a resposta do servidor
      console.log(result);
      $("#smtp_test_return").show();
      $("#smtp_test_return").html(result);
      $("#btn-test-smtp-email").attr("disabled",false)
    })
    .catch(error => {
      console.error("Error:", error);
      $("#btn-test-smtp-email").attr("disabled",false)
    });
}

  function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  }



document.addEventListener('DOMContentLoaded', function() {
    var tableBody = document.getElementById('sortableTableBody');
    var headerElement = document.querySelector('header');

    function onSwitchClick(event) {
        var switchInput = event.target;
        var id = switchInput.id.split('-')[1];
        var status = switchInput.checked ? 1 : 0;

        var formData = new FormData();
        formData.append('id', id);
        formData.append('status', status);
        formData.append('action', 'update_status');
        formData.append('myModule', tableBody.getAttribute('data-module'));

        $.ajax({
            url: '../admin/modules/update/action.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var obj = JSON.parse(response);
                toastr.info(obj.message);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    }

    if (typeof Sortable !== 'undefined' && tableBody) {
        var sortable = Sortable.create(tableBody, {
            animation: 0,
            handle: '.handle',
            scroll: window,
            scrollSensitivity: 50,
            scrollSpeed: 20,
            onStart: function(evt) {
                headerElement.classList.add('no-pointer-events');
            },
            onEnd: function(evt) {
                headerElement.classList.remove('no-pointer-events');
                var oldIndex = evt.oldIndex;
                var newIndex = evt.newIndex;
                var rows = sortable.el.children;
                var formData = new FormData();
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    formData.append('id[]', row.getAttribute('data-id'));
                    formData.append('item_order[]', i);
                }

                formData.append('myModule', tableBody.getAttribute('data-module'));
                formData.append('action', 'update_order');

                $.ajax({
                    url: '../admin/modules/update/action.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var obj = JSON.parse(response);
                        toastr.info(obj.message);
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            },
        });
    }

    var switches = document.querySelectorAll('#sortableTableBody .form-check-input');
    switches.forEach(function(switchInput) {
        switchInput.addEventListener('click', onSwitchClick);
    });
});


function getMessagesJson(thread){
    fetch('../../modules/customers/messages-json.php?thread='+thread)
    .then(response => response.json())
    .then(data => {
        let chatHtml = '';
        data.forEach(value => {
            chatHtml += `<div class="conversation-thread ${value.thread_class}">`;
            if (value.thread_class == 'thread-ai') {
                chatHtml += `<div class="user-image">
                    <img onerror="this.src='/img/no-image.svg'" src="${value.image}" alt="${value.name}" title="${value.name}">
                </div>`;
            }

            chatHtml += `<div class="message-container">
                <div class="message-info">
                    <div class="user-name">
                        <h5>${value.name}</h5>
                    </div>
                    <div class="message-text">
                        <div class="chat-response">${value.message}`;
            if (Array.isArray(value.imageContent)) {                        
              if (value.imageContent) {
                  value.imageContent.map(imageUrl => {
                      chatHtml += `<img src="${imageUrl}" alt="Image content" />`;
                  });
              }
            }
            chatHtml += `</div></div>`;

            chatHtml += `<div class="date-chat">
                        <img src="/img/icon-clock.svg"> ${value.time}
                    </div>
                </div>
            </div>
        </div>`;
        });
        document.getElementById('overflow-chat').innerHTML = chatHtml;
        setTimeout(function() {
          var overflowChat = document.getElementById("overflow-chat");
          overflowChat.scrollTop = overflowChat.scrollHeight;        
        }, 200);        
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}   

window.onload = function() {
    var nameInput = document.getElementById('floatingInputName');
    var slugInput = document.getElementById('floatingInputSlug');
    
    if (!nameInput || !slugInput) {
        return;
    }

    nameInput.oninput = function() {
        slugInput.value = slugify(this.value);
    }

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')           // Substituir espaços por -
            .replace(/[^\w\-]+/g, '')       // Remover todos os caracteres não palavras
            .replace(/\-\-+/g, '-')         // Substituir múltiplos - por um único -
            .replace(/^-+/, '')             // Trim - do início do texto
            .replace(/-+$/, '');            // Trim - do final do texto
    }
}


function checkOrderDetails(id_order) {
    $("#purchase-details").html(`<div class="spinner-border" role="status"></div>`);
    // URL to which you want to send the request
    var url = '/admin/modules/sales/sales-details.php';

    // Data to be sent
    var data = {id_order: id_order};

    // Request configuration
    var fetchOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    };

    // Makes the request and handles the response
    fetch(url, fetchOptions)
    .then(response => response.json())
    .then(data => {
        let badgeClass = '';
        if(data.status === 'succeeded') badgeClass = 'bg-success';
        else if(data.status === 'processing') badgeClass = 'bg-primary';
        else badgeClass = 'bg-danger';
        
        let listHtml = `<ul class="list-group">
                            <li class="list-group-item"><strong>ID:</strong> ${data.id_order}</li>
                            <li class="list-group-item"><strong>Name:</strong> ${data.customer_name}</li>
                            <li class="list-group-item"><strong>E-mail:</strong> ${data.customer_email}</li>
                            <li class="list-group-item"><strong>Package:</strong> ${data.item}</li>
                            <li class="list-group-item"><strong>Price:</strong> ${data.price_label}</li>
                            <li class="list-group-item"><strong>Credits:</strong> ${data.credits}</li>
                            <li class="list-group-item"><strong>Date:</strong> ${data.purchase_date}</li>
                            <li class="list-group-item"><strong>Payment Method:</strong> ${data.payment_method}</li>
                            <li class="list-group-item"><strong>Status:</strong> <span class="badge rounded-pill ${badgeClass}">${data.status}</span></li>
                        </ul>`;
        $("#purchase-details").html(listHtml);
    })
    .catch(error => console.error('Error:', error));
}

function prepareOrder(id_order){
  $("#aprovePaymentConfirmButton").attr("data-order",id_order);
}

$("#aprovePaymentConfirmButton").on("click", function(){
  aproveOrder($(this).attr("data-order"));
})

function aproveOrder(id_order) {
    var url = '/admin/modules/sales/action.php';
    var data = {id_order: id_order, action: "aprove_payment"};
    var fetchOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams(data)
    };

    fetch(url, fetchOptions)
    .then(response => response.json())
    .then(data => {
       if(data.status == "success"){
          $(".badge_id_"+id_order).removeClass("bg-danger");
          $(".badge_id_"+id_order).addClass("bg-success");
          $(".badge_id_"+id_order).html("succeeded");
          $(".btn_"+id_order).remove();
       }else{
          console.log("error")
       }
       $('#modalConfirmPayment').modal('hide');

    })
    .catch(error => console.error('Error:', error));
}

    document.addEventListener("DOMContentLoaded", function() {
    const passwordFields = document.querySelectorAll("[data-toggle-password]");

    passwordFields.forEach(field => {
      const togglePasswordIcon = field.querySelector(".toggle-password");

      if (togglePasswordIcon) {
        togglePasswordIcon.addEventListener("click", function() {
          const passwordInput = field.querySelector("input[type=password], input[type=text]");
          if (passwordInput) {
            const passwordType = passwordInput.getAttribute("type");

            if (passwordType === "password") {
              passwordInput.setAttribute("type", "text");
              togglePasswordIcon.classList.remove("bi-eye-slash");
              togglePasswordIcon.classList.add("bi-eye");
            } else {
              passwordInput.setAttribute("type", "password");
              togglePasswordIcon.classList.remove("bi-eye");
              togglePasswordIcon.classList.add("bi-eye-slash");
            }
          }
        });
      }
    });
  });

  //Password Strength
  document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('floatingPassword');
    const passwordStrengthBar = document.getElementById('password-strength-bar');
    const passwordStrengthText = document.getElementById('password-strength-text');

    if (passwordInput && passwordStrengthBar && passwordStrengthText) {
      passwordInput.addEventListener('input', function(event) {
        const password = event.target.value;
        const passwordStrength = checkPasswordStrength(password);

        passwordStrengthBar.style.width = passwordStrength.percentage + '%';
        passwordStrengthBar.classList.remove('bg-danger', 'bg-warning', 'bg-success');
        passwordStrengthBar.classList.add(passwordStrength.class);

        passwordStrengthText.textContent = passwordStrength.message;
      });
    }

    function checkPasswordStrength(password) {
      const minLength = 6;
      const strongRegex = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})');
      const mediumRegex = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{6,})');

      if (password.length < minLength) {
        return {
          percentage: 0,
          class: 'bg-danger',
          message: 'The password must have at least 6 characters.'
        };
      }

      if (strongRegex.test(password)) {
        return {
          percentage: 100,
          class: 'bg-success',
          message: 'The password entered is strong!'
        };
      }

      if (mediumRegex.test(password)) {
        return {
          percentage: 50,
          class: 'bg-warning',
          message: 'The password entered is medium.'
        };
      }

      return {
        percentage: 25,
        class: 'bg-danger',
        message: 'The password entered is weak.'
      };
    }
  });

let checkboxes = document.querySelectorAll('.check-language');
checkboxes.forEach((checkbox) => {
  checkbox.addEventListener('change', function() {
    if (this.checked) {
      checkboxes.forEach((otherCheckbox) => {
        if (otherCheckbox !== checkbox) {
          otherCheckbox.checked = false;
        }
      });
    }
  });
});

$(document).on('click', '.addDescription', function() {
    var descriptionField = $('.descriptionField:first').clone();
    descriptionField.find('input').val('');
    $('#descriptionFields').append(descriptionField);
});

$(document).on('click', '.removeDescription', function() {
    if ($('.descriptionField').length > 1) {
        $(this).closest('.descriptionField').remove();
    } else {
        alert('You cannot remove the last description field.');
    }
});  

//Google translate API
document.addEventListener("DOMContentLoaded", function() {

const languageOptions = [
    { value: 'en', text: 'English' },
    { value: 'af', text: 'Afrikaans' },
    { value: 'sq', text: 'Albanian' },
    { value: 'am', text: 'Amharic' },
    { value: 'ar', text: 'Arabic' },
    { value: 'hy', text: 'Armenian' },
    { value: 'az', text: 'Azerbaijani' },
    { value: 'eu', text: 'Basque' },
    { value: 'be', text: 'Belarusian' },
    { value: 'bn', text: 'Bengali' },
    { value: 'bs', text: 'Bosnian' },
    { value: 'bg', text: 'Bulgarian' },
    { value: 'ca', text: 'Catalan' },
    { value: 'ceb', text: 'Cebuano' },
    { value: 'ny', text: 'Chichewa' },
    { value: 'zh-CN', text: 'Chinese (Simplified)' },
    { value: 'zh-TW', text: 'Chinese (Traditional)' },
    { value: 'co', text: 'Corsican' },
    { value: 'hr', text: 'Croatian' },
    { value: 'cs', text: 'Czech' },
    { value: 'da', text: 'Danish' },
    { value: 'nl', text: 'Dutch' },
    { value: 'eo', text: 'Esperanto' },
    { value: 'et', text: 'Estonian' },
    { value: 'tl', text: 'Filipino' },
    { value: 'fi', text: 'Finnish' },
    { value: 'fr', text: 'French' },
    { value: 'fy', text: 'Frisian' },
    { value: 'gl', text: 'Galician' },
    { value: 'ka', text: 'Georgian' },
    { value: 'de', text: 'German' },
    { value: 'el', text: 'Greek' },
    { value: 'gu', text: 'Gujarati' },
    { value: 'ht', text: 'Haitian Creole' },
    { value: 'ha', text: 'Hausa' },
    { value: 'haw', text: 'Hawaiian' },
    { value: 'iw', text: 'Hebrew' },
    { value: 'hi', text: 'Hindi' },
    { value: 'hmn', text: 'Hmong' },
    { value: 'hu', text: 'Hungarian' },
    { value: 'is', text: 'Icelandic' },
    { value: 'ig', text: 'Igbo' },
    { value: 'id', text: 'Indonesian' },
    { value: 'ga', text: 'Irish' },
    { value: 'it', text: 'Italian' },
    { value: 'ja', text: 'Japanese' },
    { value: 'jw', text: 'Javanese' },
    { value: 'kn', text: 'Kannada' },
    { value: 'kk', text: 'Kazakh' },
    { value: 'km', text: 'Khmer' },
    { value: 'ko', text: 'Korean' },
    { value: 'ku', text: 'Kurdish (Kurmanji)' },
    { value: 'ky', text: 'Kyrgyz' },
    { value: 'lo', text: 'Lao' },
    { value: 'la', text: 'Latin' },
    { value: 'lv', text: 'Latvian' },
    { value: 'lt', text: 'Lithuanian' },
    { value: 'lb', text: 'Luxembourgish' },
    { value: 'mk', text: 'Macedonian' },
    { value: 'mg', text: 'Malagasy' },
    { value: 'ms', text: 'Malay' },
    { value: 'ml', text: 'Malayalam' },
    { value: 'mt', text: 'Maltese' },
    { value: 'mi', text: 'Maori' },
    { value: 'mr', text: 'Marathi' },
    { value: 'mn', text: 'Mongolian' },
    { value: 'my', text: 'Myanmar (Burmese)' },
    { value: 'ne', text: 'Nepali' },
    { value: 'no', text: 'Norwegian' },
    { value: 'or', text: 'Odia' },
    { value: 'ps', text: 'Pashto' },
    { value: 'fa', text: 'Persian' },
    { value: 'pl', text: 'Polish' },
    { value: 'pt', text: 'Portuguese' },
    { value: 'pa', text: 'Punjabi' },
    { value: 'ro', text: 'Romanian' },
    { value: 'ru', text: 'Russian' },
    { value: 'sm', text: 'Samoan' },
    { value: 'gd', text: 'Scots Gaelic' },
    { value: 'sr', text: 'Serbian' },
    { value: 'st', text: 'Sesotho' },
    { value: 'sn', text: 'Shona' },
    { value: 'sd', text: 'Sindhi' },
    { value: 'si', text: 'Sinhala' },
    { value: 'sk', text: 'Slovak' },
    { value: 'sl', text: 'Slovenian' },
    { value: 'so', text: 'Somali' },
    { value: 'es', text: 'Spanish' },
    { value: 'su', text: 'Sundanese' },
    { value: 'sw', text: 'Swahili' },
    { value: 'sv', text: 'Swedish' },
    { value: 'tg', text: 'Tajik' },
    { value: 'ta', text: 'Tamil' },
    { value: 'tt', text: 'Tatar' },
    { value: 'te', text: 'Telugu' },
    { value: 'th', text: 'Thai' },
    { value: 'tr', text: 'Turkish' },
    { value: 'tk', text: 'Turkmen' },
    { value: 'uk', text: 'Ukrainian' },
    { value: 'ur', text: 'Urdu' },
    { value: 'ug', text: 'Uyghur' },
    { value: 'uz', text: 'Uzbek' },
    { value: 'vi', text: 'Vietnamese' },
    { value: 'cy', text: 'Welsh' },
    { value: 'xh', text: 'Xhosa' },
    { value: 'yi', text: 'Yiddish' },
    { value: 'yo', text: 'Yoruba' },
    { value: 'zu', text: 'Zulu' },
  ];
  
let translateBtn = document.getElementById('translateBtn');
let initialBtnText;

if (translateBtn) {
  let sourceLangSelect = document.getElementById('sourceLangSelect');
  let targetLangSelect = document.getElementById('targetLangSelect');
  
  if (sourceLangSelect && targetLangSelect) {
    initialBtnText = translateBtn.innerHTML;

    languageOptions.forEach(language => {
      let option1 = document.createElement('option');
      option1.value = language.value;
      option1.text = language.text;
      sourceLangSelect.appendChild(option1);

      let option2 = document.createElement('option');
      option2.value = language.value;
      option2.text = language.text;
      targetLangSelect.appendChild(option2);
    });
  }
}

  function validateApiKey() {
    if (!GOOGLE_TRANSLATE_API_KEY) {
      toastr.error("Please provide a valid API key.")
      return false;
    }
    return true;
  }

async function translateFields(sourceLang, targetLang) {
  // check if both source and target languages are selected
  if (!sourceLang || !targetLang) {
    toastr.error("Please select both source and target languages.")
    return;
  }

  // check if the API key is valid
  if (!validateApiKey()) {
    return;
  }
  
  translateBtn.disabled = true;
  translateBtn.innerHTML = '<div class="spinner-border"></div>';

  let form = document.getElementById('form');
  let elements = form.elements;

  for (let element of elements) {
    if (
      element.value !== undefined &&
      element.value !== '' &&
      element.value !== 'slug' &&
      element.type !== 'number' &&
      element.type !== 'checkbox' &&
      element.type !== 'select-one' &&
      element.type !== 'select-multiple' &&
      element.type !== 'hidden'
    ) {
      let url = new URL('https://translation.googleapis.com/language/translate/v2');

      url.search = new URLSearchParams({
        key: GOOGLE_TRANSLATE_API_KEY,
        q: element.value,
        source: sourceLang,
        target: targetLang,
        format: 'text'
      })

      try {
        let response = await fetch(url, { method: 'GET' });
        if (response.ok) {
          let data = await response.json();
          let translatedText = data.data.translations[0].translatedText;

          if (element.type === 'text' || element.type === 'textarea') {
            element.value = translatedText;
            element.focus(); // Faz um "focus" no campo input
            element.style.backgroundColor = "#d3f1ff";

            setTimeout(function() {
              element.style.backgroundColor = ""; 
            }, 2000);
          } else {
            console.log('Cannot set value of element of type ' + element.type);
          }
        } else {
          throw response;
        }
      } catch (error) {
        if (error.status === 400) {
          toastr.error('API key not valid. Please pass a valid API key.');
        } else {
          console.error('Error:', error);
        }
        break;
      }
    }
  }

  translateBtn.disabled = false;
  translateBtn.innerHTML = initialBtnText;
  toastr.success("Fields successfully translated");
}


  if (translateBtn) {
    translateBtn.addEventListener('click', function() {
      let sourceLang = sourceLangSelect.options[sourceLangSelect.selectedIndex].value;
      let targetLang = targetLangSelect.options[targetLangSelect.selectedIndex].value;
      translateFields(sourceLang, targetLang);
    });
  }
});


$(".btn-show-embed-code").on("click", function(){
  var slug = $(this).attr("data-slug");
  var base_url = window.location.protocol + "//" + window.location.hostname;
  $("#modal-copy-code-body").html("")
  $("#modal-copy-code-body").html(`<pre>
&lt;script type="text/javascript"&gt;
    let chatWidget = document.createElement('div');
    chatWidget.id = 'chat-widget';
    document.currentScript.parentNode.insertBefore(chatWidget, document.currentScript);
    let iframe = document.createElement('iframe');
    iframe.src = '${base_url}/chat/${slug}?embed_chat=true';
    iframe.width = '100%';
    iframe.height = '950';
    iframe.style.border = '0';
    chatWidget.appendChild(iframe);
&lt;/script&gt;          
</pre> `)
})


  // Function to copy the content of a specified div
  function copyContent(divId) {
    // Select the div that contains the code to be copied
    var codeDiv = document.getElementById(divId);

    // Create a range object to select the content
    var range = document.createRange();
    range.selectNode(codeDiv);

    // Add the range to the selection
    var selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);

    // Copy the selected content to the clipboard
    document.execCommand("copy");

    // Clear the selection
    selection.removeAllRanges();

    // Display a message indicating that the content has been copied
    toastr.info("The content has been copied to the clipboard!");
  }

document.addEventListener('DOMContentLoaded', function() {
var btnCreatePost = document.querySelector('.btn-create-post-ai');
    if (btnCreatePost) {
    document.querySelector('.btn-create-post-ai').addEventListener('click', function(event) {
        event.preventDefault();

        let id_prompt = document.querySelector('#textPrompt').value;
        let summary = document.querySelector('#summary').value;
        let audience = document.querySelector('#audience').value;
        let keywords = document.querySelector('#keywords').value;
        let minParagraphs = document.querySelector('#minParagraphs').value;
        let maxParagraphs = document.querySelector('#maxParagraphs').value;
        let writingStyle = document.querySelector('#id_prompts_writing_default').value;
        let textTone = document.querySelector('#id_prompts_tone_default').value;
        let language = document.querySelector('#id_prompts_output_default').value;

        // Obtenha os valores dos campos
        let idPromptValue = document.querySelector('#textPrompt').value;
        let summaryValue = document.querySelector('#summary').value;
        let keywordsValue = document.querySelector('#keywords').value;

        // Validação dos campos
        let idPromptValid = idPromptValue.trim() !== '';
        let summaryValid = summaryValue.trim() !== '';
        let keywordsValid = keywordsValue.trim() !== '';

        // Atualiza as classes dos campos
        document.querySelector('#textPrompt').classList.toggle('is-invalid', !idPromptValid);
        document.querySelector('#summary').classList.toggle('is-invalid', !summaryValid);
        document.querySelector('#keywords').classList.toggle('is-invalid', !keywordsValid);

        // Verifica se todos os campos são válidos
        if (!idPromptValid || !summaryValid || !keywordsValid) {
            return;
        }

        let textarea = document.querySelector('#textarea-content');

        let postData = {
            id_prompt: id_prompt,
            summary: summary,
            audience: audience,
            keywords: keywords,
            minParagraphs: minParagraphs,
            maxParagraphs: maxParagraphs,
            writingStyle: writingStyle,
            textTone: textTone,
            language: language,
        };

        let postDataString = JSON.stringify(postData);;

        let source = new SSE("/admin/modules/posts/post-api.php", {
            headers: { "Content-Type": "application/json" },
            payload: postDataString,
            method: "POST",
        });

        source.addEventListener("message", function(event) {
            processServerResponse(event, textarea);
        });

        source.stream();

        let button = document.querySelector('.btn-create-post-ai');
        button.disabled = true;
        button.innerHTML = '<div class="spinner-border text-white"></div>';    

        function processServerResponse(event, textarea) {
          let data = event.data;

          if (data.startsWith("data: ")) {
            data = data.substring(6);
          }

          if (data === '[DONE]') {
            button.disabled = false;
            button.innerHTML = '<i class="bi bi-cpu fs-6"></i> Create text';
            var divContent = document.getElementById('modal-output-post-body').innerHTML;
            var cleanText = divContent.replace(/<\/?[^>]+(>|$)/g, '').replace(/&nbsp;/g, ' ');
            document.getElementById('modal-output-post-body').innerHTML = cleanText;
            renderMarkdown();
          }

          try {
              const jsonData = JSON.parse(data);

              if (jsonData.error && jsonData.error.message) {
                  // Exibe a mensagem de erro
                  toastr.error(jsonData.error.message);
                  button.disabled = false;
                  button.innerHTML = '<i class="bi bi-cpu fs-6"></i> Create text';
              }

              if (jsonData.choices && jsonData.choices.length > 0) {
                  const delta = jsonData.choices[0].delta;
                  if (delta && delta.content) {
                      const content = delta.content;
                      $("#modal-output-post-body").append(content)
                  }
              }
          } catch (error) {
              //console.log("An error occurred: " + error.message);
              button.disabled = false;
              button.innerHTML = '<i class="bi bi-cpu fs-6"></i> Create text';
          }
        }
    });
  }
});



function renderMarkdown() {
  const markdownText = document.getElementById('modal-output-post-body').textContent; 

  // Cria um novo renderizador com a função de cabeçalho personalizada
  const renderer = new marked.Renderer();
  renderer.heading = function (text, level) {
    return `<h${level}>${text}</h${level}>`;
  };

  const htmlText = marked(markdownText, { renderer: renderer });
  document.getElementById('modal-output-post-body').innerHTML = htmlText;
}

function copyAITextPost() {
    // Obter o elemento com o conteúdo
    var el = document.getElementById('modal-output-post-body');

    // Obter o conteúdo atual do editor TinyMCE
    var currentContent = tinymce.activeEditor.getContent();

    // Verificar se o editor está vazio ou a primeira linha contém apenas a quebra de linha
    if(currentContent.trim() === '' || currentContent.trim() === '<p><br></p>'){
        // Se estiver vazio ou apenas com quebra de linha, substitua o conteúdo pelo novo texto
        tinymce.activeEditor.setContent(el.innerHTML);
    } else {
        // Se não estiver vazio, adicione o novo texto ao final
        tinymce.activeEditor.insertContent('<br>' + el.innerHTML);
    }

    $('#modalOutputBlogText').modal('hide');
}



// Ao carregar a página
document.addEventListener('DOMContentLoaded', function() {

setTimeout(function(){
  var tabContent = document.getElementById('tab-content');
  var spinerLoadingSettings = document.getElementById('spiner-loading-settings');

  if (tabContent !== null && spinerLoadingSettings !== null) {
    tabContent.style.display = "flex";
    spinerLoadingSettings.style.display = "none";
  }
}, 100);


  // Remova a classe 'active' do primeiro botão da tab 
  // (ajuste o seletor abaixo para corresponder ao seu HTML)
  var firstTabButton = document.querySelector('.tab-container .nav-link');
  if (firstTabButton) {
    firstTabButton.classList.remove('active');
  }

  // Verifique se há um fragmento de URL
  var hash = window.location.hash;

  if (hash) {
    // Remova o símbolo '#' do hash
    var tabId = hash.slice(1);

    // Encontre o botão da tab correspondente
    var tabButton = document.getElementById(tabId);

    if (tabButton) {
      // Crie uma nova instância da Tab
      var tab = new bootstrap.Tab(tabButton);

      // Armazene o hash no campo de entrada
      var urlHashInput = document.getElementById('settings_url_hash');
      urlHashInput.value = tabId;
      
      // Mostre a tab
      tab.show();
    }
  } else {
    // Caso não haja um hash na URL, defina a primeira tab como ativa
    if (firstTabButton) {
      var firstTab = new bootstrap.Tab(firstTabButton);
      firstTab.show();
    }
  }

  // Adicione um ouvinte de evento 'shown.bs.tab' para cada link de navegação
  var navLinks = document.querySelectorAll('.nav-link');

  navLinks.forEach(function(navLink) {
    navLink.addEventListener('shown.bs.tab', function(e) {
      // Atualize a URL sem recarregar a página
      var tabId = e.target.id;
      history.replaceState(null, null, '#' + tabId);

      // Armazene o hash no campo de entrada
      var urlHashInput = document.getElementById('settings_url_hash');
      urlHashInput.value = tabId;

      for (var i = 0; i < myCodeMirrors.length; i++) {
        myCodeMirrors[i].refresh();
      }

    });
  });
});

$(".submit-button-ajax").on("click", function(e) {
  e.preventDefault();
  tinymce.triggerSave(); 

  var form = $("#form")[0];
  var formData = new FormData(form);

  formData.append("refer", "ajax");

  if (form.checkValidity()) {
    $.ajax({
      url: $(form).attr("action"),
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        console.log(response);
        var parsedResponse = JSON.parse(response);
        if (parsedResponse.status === "success") {
          toastr.success(parsedResponse.message);
        } else {
          toastr.error(parsedResponse.message);
        }
      },
      error: function(xhr, status, error) {
        toastr.error("Error updating data, check all fields.", error);
      }
    });
  } else {
    // Se o formulário for inválido, evite o envio e exiba as mensagens de erro
    e.stopPropagation();
    form.classList.add('was-validated');
    scrollToFirstInvalidField();
    showToast('formErrorToast');
  }
});


$('#modalSystemVoices').on('show.bs.modal', function (e) {
    $('#systemVoicesSpinner').show();
    $('#systemVoicesTable').hide();
    getTextToSpeechVoices();
});

window.speechSynthesis.onvoiceschanged = function() {
    //getTextToSpeechVoices();
};

function getTextToSpeechVoices() {
    var array_voices = [];
    window.speechSynthesis.getVoices().forEach(function(voice) {
        const voiceObj = {
            name: voice.name,
            lang: voice.lang
        };
        array_voices.push(voiceObj);
    });

    // Capture the current selected voice and language from the input fields
    let currentVoice = $('#floatingGoogleVoice').val();
    let currentLangCode = $('#floatingGoogleVoiceLangCode').val();

    // Limpando o conteúdo do tbody
    $('#modalSystemVoices .table tbody').empty();

    // Preenchendo a tabela com as vozes disponíveis
    array_voices.forEach(function(voice) {
        var row = $('<tr></tr>');
        row.append($('<td></td>').text(voice.name));
        row.append($('<td></td>').text(voice.lang));

        // Check if the current voice and language match the input values
        if (voice.name === currentVoice && voice.lang === currentLangCode) {
            row.addClass('highlighted-voice'); // Highlight this row
        }

        var useButton = $('<button></button>').text('Use').addClass('btn btn-primary');
        useButton.on('click', function() {
            $('#floatingGoogleVoice').val(voice.name);
            $('#floatingGoogleVoiceLangCode, #floatingMicSpeakLang').val(voice.lang);
            $('#modalSystemVoices').modal('hide');
        });
        row.append($('<td></td>').append(useButton));
        $('#modalSystemVoices .table tbody').append(row);
    });

    // Hide the spinner and show the table
    $('#systemVoicesSpinner').hide();
    $('#systemVoicesTable').removeClass('d-none').show();
}




async function getTextToSpeechGoogleVoices() {
    const voicesApiUrl = "/php/google_tts.php?action=listVoices";
    
    try {
        const response = await fetch(voicesApiUrl, { method: 'GET' });
        const data = await response.json();

        if (data.error) {
            toastr.error(data.error.message);
            console.error('Google API Error:', data);
        } else {
            // Filtrar vozes duplicadas
            const uniqueVoices = data.voices.filter((voice, index, self) =>
                index === self.findIndex((v) => v.name === voice.name)
            );

            // Ordenar as vozes em ordem alfabética pelo nome completo do idioma
            const sortedVoices = uniqueVoices.sort((a, b) => 
                getFullLanguageName(a.languageCodes[0]).localeCompare(getFullLanguageName(b.languageCodes[0]))
            );

            populateGoogleVoicesModal(sortedVoices);
        }
    } catch (error) {
        console.error("Failed to fetch Google voices:", error);
    }
}



$('#modalGoogleVoices').on('show.bs.modal', function (e) {
    $('#voicesSpinner').show();
    $('#voicesTable').hide();
    getTextToSpeechGoogleVoices();
});



function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function getFullLanguageName(langCode) {
    const parts = langCode.split('-');
    const mainLang = new Intl.DisplayNames([navigator.language], { type: 'language' }).of(parts[0]);

    if (parts.length > 1) {
        const region = new Intl.DisplayNames([navigator.language], { type: 'region' }).of(parts[1]);
        return capitalizeFirstLetter(mainLang) + ' (' + region + ')';
    } else {
        return capitalizeFirstLetter(mainLang);
    }
}

function getLanguageNameFromCode(langCode) {
    let lang = new Intl.DisplayNames([navigator.language], { type: 'language' });
    return lang.of(langCode.split('-')[0]); // Pega apenas o código do idioma (por exemplo, "en" de "en-US")
}


function populateGoogleVoicesModal(voices) {
    // Check if the DataTables instance already exists, and if so, destroy it
    if ($.fn.DataTable.isDataTable('#voicesTable')) {
        $('#voicesTable').DataTable().destroy();
    }

    // Get current values
    var currentVoiceName = $('#floatingCloudGoogleVoice').val();
    var currentLangCode = $('#floatingCloudGoogleVoiceLangCode').val();
    var currentGender = $('#floatingCloudGoogleVoiceGender').val();

    // Clear the tbody content
    $('#modalGoogleVoices .table tbody').empty();

    voices.forEach(voice => {
        var row = $('<tr></tr>');
        
        // Check if current voice data matches one in the list
        if (voice.name === currentVoiceName && voice.languageCodes[0] === currentLangCode && voice.ssmlGender === currentGender) {
            row.addClass('table-info'); // Add highlight class
        }

        // Add columns to the row
        row.append($('<td></td>').text(getFullLanguageName(voice.languageCodes[0]))); // Full Language name
        row.append($('<td></td>').text(voice.name));
        row.append($('<td></td>').text(voice.languageCodes[0]));
        row.append($('<td></td>').text(voice.ssmlGender)); // Gender data

        var useButton = $('<button></button>').text('Use').addClass('btn btn-primary');
        useButton.on('click', function() {
            $('#floatingCloudGoogleVoice').val(voice.name);
            $('#floatingCloudGoogleVoiceLangCode').val(voice.languageCodes[0]);
            $('#floatingCloudGoogleVoiceGender').val(voice.ssmlGender);
            $('#modalGoogleVoices').modal('hide');
        });
        row.append($('<td></td>').append(useButton));

        $('#modalGoogleVoices .table tbody').append(row);
    });

    // Hide the spinner and show the table
    $('#voicesSpinner').hide();
    $('#voicesTable').removeClass('d-none').show();
    $('#voicesTable').DataTable({
        pageLength: 100,
        order: []
    });   
    
}