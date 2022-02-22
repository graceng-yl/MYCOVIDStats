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

    google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);

    //to choose start and end range
    document.getElementById('filter').addEventListener("click", function(){
        startdate = document.getElementById('startdate').value; 
        enddate = document.getElementById('enddate').value;
        var splitted_startdate = startdate.split('-');
        var splitted_enddate = enddate.split('-');
        startdate = new Date(splitted_startdate[0], parseInt(splitted_startdate[1])-1, splitted_startdate[2]);
        enddate = new Date(splitted_enddate[0], parseInt(splitted_enddate[1])-1, splitted_enddate[2]);
        //console.log(new Date(splitted_enddate[0], parseInt(splitted_enddate[1])-1, splitted_enddate[2]));
        //drawChart(startdate, enddate);
        drawChart();
    });
});




google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);


function addrows(start, end, data){
    //console.log(start-1);
    console.log(start, end, data);
    var length = data.getNumberOfRows()-end;
    data.removeRows(0, start-1);
    data.removeRows(end, length);
    //data = data.removeRows(end+1, length-end+1);

    // for (var i = start-1; i == 0; i--) {
    //   data = data.removeRow(i);
    // }
    // for (var i = end; i < data.getNumberOfRows(); i++){
    //   console.log('hi');
    //   data = data.removeRow(i);
    // }
    //console.log(data.getNumberOfRows());
    return data;
}

function drawChart() {
    console.log(graph_array);
    var cases = Array.from(graph_array); //
    var d = new Date();
    var startdate = new Date(d.setDate(d.getDate() - 31));
    //console.log(startdate);
    startdate = startdate.setHours(0,0,0,0);
    //console.log(startdate);
    var d = new Date();
    var enddate = new Date(d.setDate(d.getDate() - 1));
    enddate = enddate.setHours(0,0,0,0);
    
    for(var record=0; record<cases.length; record++){
        if(record!=0){
            for(var item=0; item<cases[record].length; item++){
                if(item==0){
                    console.log(cases[record][item]);
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
        }
        if (cases[record].map(Number).indexOf(+enddate) !== -1){
            //console.log(cases[record]);
            endpos = cases.indexOf(cases[record]);
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
