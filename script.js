jQuery(document).ready(function() {
	document.getElementById('statedropdown').addEventListener("change", function(){
        //console.log('hello');
        document.getElementById('statedropdown').submit();
    });

    var state = document.getElementById('state').title;
    jQuery.each(jQuery('#statedropdownselect option'), function(i, val){
        if(val.value == state){
            jQuery(val).attr('selected','selected');
        }
    });

    //restrict date range
    jQuery('#startdate').attr('min', mindate);
    jQuery('#enddate').attr('min', mindate);
    jQuery('#startdate').attr('max', date);
    jQuery('#enddate').attr('max', date);
    document.getElementById('startdate').addEventListener("change", function(){
        var start = document.getElementById('startdate').value; 
        jQuery('#enddate').attr('min', start);
    });
    document.getElementById('enddate').addEventListener("change", function(){
        var end = document.getElementById('enddate').value; 
        jQuery('#startdate').attr('max', end);
    });

    //to choose start and end range
    document.getElementById('filter').addEventListener("click", function(){
        if(document.getElementById('startdate').value=='' || document.getElementById('enddate').value==''){
            jQuery('#filtermessage').show().delay(2000).fadeOut(); //if no set either one
        }else{
            startdate = document.getElementById('startdate').value; 
            enddate = document.getElementById('enddate').value;
            var splitted_startdate = startdate.split('-');
            var splitted_enddate = enddate.split('-');
            startdate = new Date(splitted_startdate[0], parseInt(splitted_startdate[1])-1, splitted_startdate[2]);
            enddate = new Date(splitted_enddate[0], parseInt(splitted_enddate[1])-1, splitted_enddate[2]);
            //console.log(new Date(splitted_enddate[0], parseInt(splitted_enddate[1])-1, splitted_enddate[2]));
            //drawChart(startdate, enddate);
            drawChart();
        }
    });
});


var d = new Date();
var startdate = new Date(d.setDate(d.getDate() - 31));
//console.log(startdate);
startdate = startdate.setHours(0,0,0,0);
//console.log(startdate);
var d = new Date();
var enddate = new Date(d.setDate(d.getDate() - 1));
enddate = enddate.setHours(0,0,0,0);
    
google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);


function addrows(start, end, data){
    var lengthfromend = (data.getNumberOfRows()+1)-(end+1);
    //console.log(lengthfromend);
    data.removeRows(0, start-1); //remove 0 to start-1
    data.removeRows(end+1-start, lengthfromend); //remove 0+end-start to total-end
    return data;
}

function drawChart() {
    var cases = JSON.parse(JSON.stringify(graph_array)); // deep copy
    //console.log(graph_array === cases);
    //console.log(cases);
    
    
    for(var record=0; record<cases.length; record++){
        if(record!=0){
            for(var item=0; item<cases[record].length; item++){
                if(item==0){
                    //console.log(cases[record][item]);
                    var splitted = cases[record][item].split('-');
                    cases[record][item] = new Date(splitted[0], parseInt(splitted[1])-1, splitted[2]);
                }
                else{
                    cases[record][item] = parseInt(cases[record][item]);
                }
            }
        }
    }
    var startpos = 0;
    var endpos = 0;
    for(var record=0; record<cases.length; record++){
        //console.log(startdate); //console.log(cases[record]);
        if (cases[record].map(Number).indexOf(+startdate) !== -1){
            //console.log(cases[record]);
            startpos = cases.indexOf(cases[record]);
            //console.log(startpos);
        }
        if (cases[record].map(Number).indexOf(+enddate) !== -1){
            //console.log(cases[record]);
            endpos = cases.indexOf(cases[record]);
            //console.log(endpos);
        }
    }
    // var casesranged = cases.slice(startpos, endpos);
    // casesranged[0] = cases[0];

    var data = google.visualization.arrayToDataTable(cases);
    //console.log(startpos, endpos, data);
    data = addrows(startpos, endpos, data);
    
    var options = {
        title: '',
        legend: { position: 'right' },
        focusTarget: 'category',
        //height: jQuery(window).height()*1.2,
        //width: jQuery(window).width()*0.98,
        vAxis: { title: 'Number', viewWindow: { min:0 } },
        hAxis: { title: 'Date' },
        backgroundColor: 'white'
    };
    var chart = new google.visualization.LineChart(document.getElementById('trendgraph'));
    chart.draw(data, options);

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
