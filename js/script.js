function drawChart(){
    chart = new Chart(document.getElementById('trend_graph').getContext('2d'), {
        type: 'line', data: {
            labels: labels.slice(start_date_pos, end_date_pos+1),
            datasets: [{
                label: 'New Cases',
                data: data[0].slice(start_date_pos, end_date_pos+1),
                borderColor: '#f1ca3a',
                pointRadius: 0,
            },{
                label: 'Active Cases',
                data: data[1].slice(start_date_pos, end_date_pos+1),
                borderColor: '#e7711b',
                pointRadius: 0,
            },{
                label: 'Recovered',
                data: data[2].slice(start_date_pos, end_date_pos+1),
                borderColor: '#6f9654',
                pointRadius: 0,
            },{
                label: 'Deaths',
                data: data[3].slice(start_date_pos, end_date_pos+1),
                borderColor: '#e2431e',
                pointRadius: 0,
            },{
                label: 'Vaccinations',
                data: data[4].slice(start_date_pos, end_date_pos+1),
                borderColor: '#1c91c0',
                pointRadius: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        tooltipFormat: 'd LLL y',
                    },
                    ticks: {
                        color: '#ffffff',
                        font:{
                            family: "'Helvetica', 'Arial', sans-serif",
                            size: 16
                        },
                    }
                },
                y: {
                    ticks: {
                        color: '#ffffff',
                        font:{
                            family: "'Helvetica', 'Arial', sans-serif",
                            size: 16
                        },
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff',
                        usePointStyle: true,
                        font:{
                            family: "'Helvetica', 'Arial', sans-serif",
                            size: 16
                        },
                    },
                }
            }  
        }
    });
}

function addrows(start_date, end_date){
    for(var record=0; record<labels.length; record++){
        if (+labels[record] === +start_date){
            start_date_pos = record;
        }
        if (+labels[record] === +end_date){
            end_date_pos = record;
        }
    }
}


var chart;
var start_date = '';
var end_date = '';
var start_date_pos = 0;
var end_date_pos = 0;
var labels = [];
var data = [[],[],[],[],[]];
var predata = '';


jQuery(window).on('load', function(){
    jQuery('.preloader').fadeOut('slow');
    jQuery('body').attr('id','');
});


jQuery(document).ready(function() {
    jQuery('#filter_message').hide();

    //reload page when state dropdown is chosen
	document.getElementById('state_form').addEventListener("change", function(){
        document.getElementById('state_form').submit();
    });

    var state = document.getElementById('state_div').title;
    //var state = jQuery('.ui-selectmenu-text').text()

    //if state is selected, keep it selected in dropdown
    jQuery.each(jQuery('#state_form_dropdown option'), function(i, val){
        if(val.value == state){
            jQuery(val).attr('selected','selected');
        }
    });

    //change background map according to selected state
    if(state==""){
        jQuery('body').css('background-image','url(content/Malaysia.png)');
    }
    else{
        jQuery('body').css('background-image','url(content/'+state.replaceAll(' ','_')+'.png)');
    }

    //restrict input date range in trend graph
    jQuery('#start_date').attr('min', date_min);
    jQuery('#end_date').attr('min', date_min);
    jQuery('#start_date').attr('max', date_tdy);
    jQuery('#end_date').attr('max', date_tdy);
    document.getElementById('start_date').addEventListener("change", function(){
        var start_lim = document.getElementById('start_date').value; 
        jQuery('#end_date').attr('min', start_lim);
    });
    document.getElementById('end_date').addEventListener("change", function(){
        var end_lim = document.getElementById('end_date').value; 
        jQuery('#start_date').attr('max', end_lim);
    });

    //to update when trend graph filter date button is clicked
    document.getElementById('submit_filter').addEventListener("click", function(){
        if(document.getElementById('start_date').value=='' || document.getElementById('end_date').value==''){
            jQuery('#filter_message').show().delay(2000).fadeOut(); //if either one input not set
        }else{
            start_date = document.getElementById('start_date').value; 
            end_date = document.getElementById('end_date').value;
            var start_date_split = start_date.split('-');
            var end_date_split = end_date.split('-');
            start_date = new Date(start_date_split[0], parseInt(start_date_split[1])-1, start_date_split[2]);
            end_date = new Date(end_date_split[0], parseInt(end_date_split[1])-1, end_date_split[2]);
            //drawChart();
            addrows(start_date, end_date);
            chart.destroy();
            drawChart();
        }
    });

    //datatable for states table
    jQuery('#states_table').DataTable({
        "columnDefs": [
            { 
                "orderSequence": [ "desc","asc" ], //order by desc first
                "targets": [ 1,2,3,4,5,6,7 ]
            },
        ], 
        "order": [[ 1, "desc" ]], //default order new cases by desc
        "paging": false,
        "searching": false,
        "info": false
        //no paging, searching, and info
    });

    jQuery('.number_counter').each(function () {
        jQuery(this).prop('Counter',0).animate({
            Counter: jQuery(this).text()
        }, {
            duration: 2000,
            easing: 'swing',
            step: function (now) {
                jQuery(this).text(Math.ceil(now));
            }
        });
    });

    predata = JSON.parse(JSON.stringify(data_all)); // deep copy
    for(var record=0; record<predata.length; record++){
        if(record!=0){
            for(var item=0; item<predata[record].length; item++){
                if(item==0){
                    var data_date_split = predata[record][item].split('-');
                    predata[record][item] = new Date(data_date_split[0], parseInt(data_date_split[1])-1, data_date_split[2]);
                    labels.push(predata[record][item]);
                }
                else{
                    predata[record][item] = parseInt(predata[record][item]);
                    data[item-1].push(predata[record][item]);
                }
            }
        }
    }
    
    var d = new Date();
    start_date = new Date(d.setDate(d.getDate() - 31));
    start_date = start_date.setHours(0,0,0,0);
    var d = new Date();
    end_date = new Date(d.setDate(d.getDate() - 1));
    end_date = end_date.setHours(0,0,0,0);

    addrows(start_date, end_date);
    drawChart();
    
});
