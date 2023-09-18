$(document).ready(function () {
    // jQuery(document).on("change", "#product_bd_list", function (e) {
    //     e.preventDefault();

    //     var products = $(this).val();
       

    //     switch (products) {
    //         case "3":
    //             //POSTPAID
    //             console.log("postpaid selected");
    //             //Fade In
    //             $("#postpaidReportType").show("fadein");
    //             //Fade out
    //             $("#groupz").hide("fadeout");
    //             $("#campaignname_row").hide("fadeout");
    //             $("#date_choice").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $('#date_filter').hide('fadeOut');
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").hide("fadeout");

    //             // $('#product-bd-list').prop('selectedIndex',0);
    //             $('#select_report_type').prop('selectedIndex',0);
    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);



    //             //execute code block 1
    //             break;
    //         case "4":
    //             //BROADBAND
    //             //execute code block 2
    //             console.log("broadband selected");
    //             $("#postpaidReportType").fadeIn();
    //             //Fade out
    //             $("#groupz").hide("fadeout");
    //             $("#campaignname_row").hide("fadeout");
    //             $("#date_choice").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $('#date_filter').hide('fadeOut');
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").hide("fadeout");

    //             // $('#product-bd-list').prop('selectedIndex',0);
    //             $('#select_report_type').prop('selectedIndex',0);
    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;

    //         case "6":
    //             //POSTPAID-LUKEWARM
    //             //execute code block 2
    //             console.log("LUKEWARM selected");
    //             $("#postpaidReportType").fadeIn();
    //             //Fade out
    //             $("#groupz").hide("fadeout");
    //             $("#campaignname_row").hide("fadeout");
    //             $("#date_choice").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $('#date_filter').hide('fadeOut');
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").hide("fadeout");

    //             // $('#product-bd-list').prop('selectedIndex',0);
    //             $('#select_report_type').prop('selectedIndex',0);
    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;

    //         default:
    //             $("#postpaidReportType").hide("fadeout");
    //             //Fade out
    //             $("#groupz").hide("fadeout");
    //             $("#campaignname_row").hide("fadeout");
    //             $("#date_choice").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $('#date_filter').hide('fadeOut');
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").hide("fadeout");

    //             // $('#product-bd-list').prop('selectedIndex',0);
    //             $('#select_report_type').prop('selectedIndex',0);
    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);

    //         // code to be executed if n is different from case 1 and 2
    //     }
    // });

    // jQuery(document).on("change", "#select_report_type", function (e) {
    //     e.preventDefault();
      
    //     var postpaidReportType = $(this).val();
    //     console.log(postpaidReportType);

    //     switch (postpaidReportType) {
    //         case "CampaignExtract":
    //             //POSTPAID
    //             console.log("CampaignExtract");
    //             //Fade In
    //             $("#groupz").show("fadein");
    //             $("#type_row").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").show("fadein");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#day_filter").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);

    //             //execute code block 1
    //             break;
    //         case "CampaignReupload":
    //             //BROADBAND
    //             //execute code block 2
    //             console.log("CampaignReupload");
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").show("fadein");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;

    //         case "CampaignCallNoAnswerA":
    //             //POSTPAID-LUKEWARM
    //             //execute code block 2
    //             console.log("CampaignCallNoAnswerA");
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").show("fadein");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;

    //         case "CampaignResultAllDispo":
    //             //POSTPAID
    //             console.log("CampaignResultAllDispo");
    //             //Fade In
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").show("fadein");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);

    //             //execute code block 1
    //             break;
    //         case "CampaignResultAllTransDispoZ":
    //             //BROADBAND
    //             //execute code block 2
    //             console.log("CampaignResultAllTransDispoZ");
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").show("fadein");
    //             $("#type_row").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;

    //         case "CampaignResultAllDispoC":
    //             //POSTPAID-LUKEWARM
    //             //execute code block 2
    //             console.log("CampaignResultAllDispoC");
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").hide("fadeout");
    //             $("#day_filter").show("fadein");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#date_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;
    //         case "CampaignResultAllDispoY":
    //             //POSTPAID
    //             console.log("CampaignResultAllDispoY");
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#week_filter").show("fadein");
    //             $("#date_choice").hide("fadeout");
    //             $("#day_filter").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#date_filter").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);

    //             //execute code block 1
    //             break;
    //         case "CampaignResultAllDispoB":
    //             //BROADBAND
    //             //execute code block 2
    //             console.log("CampaignResultAllDispoB");
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").hide("fadeout");
    //             $("#day_filter").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#date_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#month_filter").show("fadein");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;

    //         case "CampaignAnsweredWithData":
    //             //POSTPAID-LUKEWARM
    //             //execute code block 2
    //             console.log("CampaignAnsweredWithData");
    //             $("#groupz").show("fadein");
    //             $("#campaignname_row").show("fadein");
    //             $("#date_choice").show("fadein");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $("#day_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#submit_row").show("fadein");

    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);
    //             break;

    //         default:
    //             console.log("defaulted");

             

    //             $("#groupz").hide("fadeout");
    //             $("#campaignname_row").hide("fadeout");
    //             $("#date_choice").hide("fadeout");
    //             $("#campaignagent_row").hide("fadeout");
    //             $("#type_row").hide("fadeout");
    //             $('#date_filter').hide('fadeOut');
    //             $("#day_filter").hide("fadeout");
    //             $("#week_filter").hide("fadeout");
    //             $("#month_filter").hide("fadeout");
    //             $("#submit_row").hide("fadeout");

    //             $('#select_report_type').prop('selectedIndex',0);
    //             $('#filtertype').prop('selectedIndex',0);
    //             $('#groupzz').prop('selectedIndex',0);
    //             $('#campaignname').prop('selectedIndex',0);
    //             $('#select_date_type').prop('selectedIndex',0);
    //             $('#agentname').prop('selectedIndex',0);
    //             $('#verifiername').prop('selectedIndex',0);

    //         // code to be executed if n is different from case 1 and 2
    //     }
    // });
    jQuery(document).on('change', '#select_date_type', function(e){

    e.preventDefault();
    var datetype = $('#select_date_type').val();
    $('#start_date').val('');
    $('#end_date').val('');
    if(datetype == 'date_range')
        {
            console.log("koki date range");
            $('#date_filter').show('fadeIn');
            $('#submit_row').hide('fadeOut');
        }else{
            console.log("koki all time");
            $('#date_filter').hide('fadeOut');
            $('#submit_row').show('fadeIn');
        }
    });

    jQuery(document).on('change', '#start_date', function(e){
        var hideSubmit = true;
        var toDate = new Date($('#start_date').val());
        var fromDate = new Date($('#end_date').val());
        if($('#start_date').val() == ""){
            // console.log("1 start date")
            hideSubmit = false;
        }
        if( $('#end_date').val() == ""){
            // console.log("1 start date")
            hideSubmit = false;
        }
        if(toDate > fromDate){
            console.log("1 end date is greater than start date")
            hideSubmit = false;
        }
        // console.log($('#start_date').val());
        // console.log($('#end_date').val());
        if(hideSubmit){
            $('#start_date').val('');
            $('#end_date').val('');
            $('#submit_row').show('fadeIn');
        }else{
            $('#submit_row').hide('fadeOut');
        }
    });
    jQuery(document).on('change', '#end_date', function(e){
        var hideSubmit = true;
        var toDate = new Date($('#start_date').val());
        var fromDate = new Date($('#end_date').val());
        if($('#start_date').val() == ""){
            // console.log("2 start date")
            hideSubmit = false;
        }
        if( $('#end_date').val() == ""){
            // console.log("2 end date")
            hideSubmit = false;
        }
        if(toDate > fromDate){
            // console.log("2 end date is greater than start date")
            hideSubmit = false;
        }
        // console.log($('#start_date').val());
        // console.log($('#end_date').val());
        if(hideSubmit){
            $('#submit_row').show('fadeIn');
        }else{
            $('#start_date').val() == "";
            $('#end_date').val() == "";
            $('#submit_row').hide('fadeOut');  
        }  
    });
     
});
