//default trand graph date range
var d = new Date();
var start_date = new Date(d.setDate(d.getDate() - 31));
start_date = start_date.setHours(0,0,0,0);
var d = new Date();
var end_date = new Date(d.setDate(d.getDate() - 1));
end_date = end_date.setHours(0,0,0,0);


google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);


function addrows(start_date_pos, end_date_pos, data){
    var length_from_end = (data.getNumberOfRows()+1)-(end_date_pos+1);
    data.removeRows(0, start_date_pos-1); //remove 0 to start_date_pos-1
    data.removeRows(end_date_pos+1-start_date_pos, length_from_end); //remove 0+end_date_pos-start_date_pos to total-end_date_pos
    return data;
}


function drawChart() {
    var predata = JSON.parse(JSON.stringify(data_all)); // deep copy
    for(var record=0; record<predata.length; record++){
        if(record!=0){
            for(var item=0; item<predata[record].length; item++){
                if(item==0){
                    var data_date_split = predata[record][item].split('-');
                    predata[record][item] = new Date(data_date_split[0], parseInt(data_date_split[1])-1, data_date_split[2]);
                }
                else{
                    predata[record][item] = parseInt(predata[record][item]);
                }
            }
        }
    }
    //find start and end position in data_all
    var start_date_pos = 0;
    var end_date_pos = 0;
    for(var record=0; record<predata.length; record++){
        if (predata[record].map(Number).indexOf(+start_date) !== -1){
            start_date_pos = predata.indexOf(predata[record]);
        }
        if (predata[record].map(Number).indexOf(+end_date) !== -1){
            end_date_pos = predata.indexOf(predata[record]);
        }
    }

    var data = google.visualization.arrayToDataTable(predata);
    //filter the data range
    data = addrows(start_date_pos, end_date_pos, data);
    
    var options = {
        title: '',
        legend: { position: 'right' },
        focusTarget: 'category',
        height: jQuery(window).height()*0.9,
        width: jQuery(window).width()*0.9,
        chartArea: {'width': '65%', 'height': '80%'},
        hAxis:{gridlines: {color: 'transparent', minSpacing: 20}},
        vAxis:{gridlines: {color: 'transparent', minSpacing: 50}},
        // vAxis: { title: 'Number', viewWindow: { min:0 } },
        // hAxis: { title: 'Date' },
        backgroundColor: 'white'
    };
    var chart = new google.visualization.LineChart(document.getElementById('trend_graph'));
    chart.draw(data, options);

    //click to legend to hide or show 
    var columns = [];
    var series = {};
    for (var i = 0; i < data.getNumberOfColumns(); i++) {
        columns.push(i);
        if (i > 0) {
            series[i - 1] = {};
        }
    }
    google.visualization.events.addListener(chart, 'select', function () {
        var sel = chart.getSelection();
        // if selection length is 0, we deselected an element
        if (sel.length > 0) {
          // if row is undefined, we clicked on the legend
            if (sel[0].row === null) {
                var col = sel[0].column;
                if (columns[col] == col) {
                // hide the data series
                    columns[col] = {
                        label: data.getColumnLabel(col),
                        type: data.getColumnType(col),
                        calc: function () {
                        return null;
                    },
                };
                // grey out the legend entry
                series[col - 1].color = '#CCCCCC';
                } else {
                    // show the data series
                    columns[col] = col;
                    series[col - 1].color = null;
                }
                var view = new google.visualization.DataView(data);
                view.setColumns(columns);
                chart.draw(view, options);
            }
        }
    });
}


jQuery(document).ready(function() {

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
        jQuery('body').css('background-image','url(content/'+state.replace(' ','_')+'.png)');
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
            drawChart();
        }
    });

    //datatable for states table
    jQuery('#states_table').DataTable({
        "columnDefs": [{ "orderSequence": [ "desc","asc" ], "targets": [ 1,2,3,4,5,6,7 ] }], //order by desc first
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

    //jQuery('#state_form_dropdown').selectmenu();
});