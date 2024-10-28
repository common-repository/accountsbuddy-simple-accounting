// JavaScript Document
(function($) {
    "use strict";
    
	$("form[data-async]").on("submit",function(e,target) {
        e.preventDefault();
        return false;
    });

    $("form[data-async]").on("submit", function(e,target) {

        var debit_amnt 		= 0;
        var credit_amnt 	= 0;
        var balance_amnt 	= 0;
        var error			= 0;

        $('.DrAmnt').each(function(){
            var amount = $('.DrAmnt').val();
            amount = parseInt($(this).val());
            if(!isNaN(amount)){
                debit_amnt = amount+debit_amnt;
            }
        });

        $('.CrAmnt').each(function(){
            var amount = $('.CrAmnt').val();
            amount = parseInt($(this).val());
            if(!isNaN(amount)) {
                credit_amnt = amount+credit_amnt;
            }
        });

        if(debit_amnt == 0){
            $(".jv_error_alert1").css({"display": "flex", "z-index": "1"});
            error = 1;
        }

        if(credit_amnt == 0){
            $(".jv_error_alert2").css({"display": "flex", "z-index": "2"});
            error = 1;
        }

        if((debit_amnt-credit_amnt) != 0) {
            $(".jv_error_alert3").css({"display": "flex", "z-index": "3"});
            error = 1;
        }

        $('.DrAmnt').each(function(){

            var array_index = $(this).index('.DrAmnt');

            var $credit_value 	= $(".CrAmnt").get(array_index).value;
            var $debit_value 	= $(".DrAmnt").get(array_index).value;
            
            if(isNaN($debit_value) || isNaN($credit_value)) {
                $(".jv_error_alert4").css({"display": "flex", "z-index": "4"});
                error = 1;
            }
        
            if($credit_value != "" && $debit_value != "") {
                $(".jv_error_alert5").css({"display": "flex", "z-index": "5"});     
                           
                $(".CrAmnt").eq(array_index).val("");
                $(".DrAmnt").eq(array_index).val("");

                error = 1;
            }

            if(($credit_value+$debit_value) == 0){
                $(".jv_error_alert6").css({"display": "flex", "z-index": "6"});
                error = 1;
            }

        });
        
        var $form 		 = $(this);
        var formData 	 = $form.serialize();

        var $input = $(this).find("input[name=form_type]");
        var $printClass = $(this).attr('data-print-reply');

        if( $input.val() == "add_jv_form_text" ) {
            var $perform_act = "abas_jv_form";	
        }

        if( error == 0 ){
            $.ajax({
                type: $form.attr('method'),
                data: formData + '&action='+$perform_act,
                url: ajax_obj.ajax_url,
                dataType: 'json',

                beforeSend: function () {
                    if ( $printClass != '' ) {
                        $('.form-message').html("<div class='spinner is-active'> form processing </div>");
                    } else {
                        $('.form-message').html("<div class='spinner is-active'> form processing </div>");
                    }
                },
                success: function(response) {

                    var message 		= response.message;
                    var success 		= response.success;

                    if( success == "YES" && $perform_act != "abas_jv_form" ) {
                        $form.trigger("reset");                        
                    }

                    if( $printClass != '' ){
                        $($printClass).html('<div class="callout success">'+message+'</div>');
                    } else {
                        $('.form-message').html('<div class="callout success">'+message+'</div>');
                    } 

                }        
            });
        }

    });

    $(document).ready(function(){

        function dr_cr_balance() { 

            var debit_amnt 		= 0;
            var credit_amnt 	= 0;
            var balance_amnt 	= 0;

            var amount = $('.DrAmnt').val();

            $('.DrAmnt').each(function(){                
                amount = parseInt($(this).val());    
                if(!isNaN(amount)) {
                    debit_amnt = amount+debit_amnt;
                }
            });

            var amount = $('.CrAmnt').val();

            $('.CrAmnt').each(function(){    
                amount = parseInt($(this).val());    
                if(!isNaN(amount)) {
                    credit_amnt = amount+credit_amnt;
                }
            });
            
            $('#debit_amnt').html(debit_amnt.toFixed(2));
            $('#credit_amnt').html(credit_amnt.toFixed(2));
            $('#balance_amnt').html((debit_amnt-credit_amnt).toFixed(2));

        }

        $('#RepeatableFieldJv').on('change', '.DrAmnt', function() {

            var array_index = $(this).index('.DrAmnt');

            if(isNaN($(this).val())) {
                $(this).val('');
                $(".jv_error_alert4").css({"display": "flex", "z-index": "4"});
            }
            
            var $credit_value 	= $(".CrAmnt").get(array_index).value;
            var $debit_value 	= $(".DrAmnt").get(array_index).value;
            
            if($credit_value != "" && $debit_value != "") {
                $(".jv_error_alert5").css({"display": "flex", "z-index": "5"});                  
                $(".CrAmnt").eq(array_index).val("");
                $(".DrAmnt").eq(array_index).val("");
            }

            dr_cr_balance();
            
        });

        $('#RepeatableFieldJv').on('change', '.CrAmnt', function() {

            var array_index = $(this).index('.CrAmnt');

            if(isNaN($(this).val())) {
                $(this).val('');
                $(".jv_error_alert4").css({"display": "flex", "z-index": "4"});
            }
            
            var $credit_value 	= $(".CrAmnt").get(array_index).value;
            var $debit_value 	= $(".DrAmnt").get(array_index).value;
            
            if($credit_value != "" && $debit_value != "") {
                $(".jv_error_alert5").css({"display": "flex", "z-index": "5"});                 
                $(".CrAmnt").eq(array_index).val("");
                $(".DrAmnt").eq(array_index).val("");
            }

            dr_cr_balance();

        });

    });

})(jQuery); //jQuery main function ends strict Mode on