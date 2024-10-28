
// JavaScript Document
(function($) {
    "use strict";
	
	//calling foundation js
	jQuery(document).foundation();

    jQuery.fn.exists = function(){ return this.length > 0; }

    $("#OpenModal").click(function(){
        $("#WcAcModalOverlay").css("display","flex");
        $("#WcAcModalBody").css("display","flex");
    });
   
   $("#WcAcCancelModal").click(function(){
        $("#WcAcModalOverlay").fadeOut();
        $("#WcAcModalBody").fadeOut();
   });

   jQuery(document).ready(function( $ ){
        $( '#add-row' ).on('click', function() {
            var row = $ ( '.repeatable-row.repeatable-row-add' ).clone(true).find("input").val("").end();
            row.removeClass ( 'repeatable-btn-hide repeatable-row-add' );
            row.insertAfter ( '#RepeatableFieldJv tbody>.repeatable-row:last' );
            return false;
        });
        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
    
    jQuery("#btnPrint").on("click", function() {
        window.print();
    });
    
    $('#abas_account_groupschecklist input, .abas_account_groups-checklist input, #abas_account_groupschecklist-pop input').click(function() {
        $('#abas_account_groupschecklist input, .abas_account_groups-checklist input, #abas_account_groupschecklist-pop input').not(this).prop('checked', false);
    });

})(jQuery); //jQuery main function ends strict Mode on
