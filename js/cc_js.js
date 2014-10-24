
jQuery(function($) {
    $("#subscribe_to_cc_list").click(function(){
        var cc_button_text=$(this).val();
        var cc_current_button=$(this);
        $(".constant-contact-error-message").html('').addClass("hide-it");
        $(".constant-contact-success-message").html('').addClass("hide-it");
   jQuery.ajax({
        type: 'POST',
        url: '/SS_ConstantContactController/subscribe',
       data: $(this).closest("form").serialize(),
        beforeSend: function() {
             $("#subscribe_to_cc_list").prop('value', 'Please wait...'); 
         
        },
        success: function(res) {              
            res=jQuery.parseJSON(res);
            if (res['action']=='C'||res['action']=='U'){
               $(".constant-contact-success-message").html(res['message']).removeClass("hide-it"); 
               cc_current_button.prop('value', 'Signed Up'); 
               cc_current_button.closest("form").find("input").prop('disabled','disabled');
            }
            else if (res['action']=='E'){
                $(".constant-contact-error-message").html(res['message']).removeClass("hide-it");
                cc_current_button.prop('value', cc_button_text); 
            } else{
                 $(".constant-contact-error-message").html('Unexpected error happened.').removeClass("hide-it");
                cc_current_button.prop('value', cc_button_text); 
            }
           
        },

        error: function() {
            // failed request; give feedback to user

        }
    });
}
);
 });