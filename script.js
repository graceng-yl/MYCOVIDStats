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

});


google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var cases = graph_array;
    var startdate = new Date('2020', '0', '1'); //to choose start and end range

    for(var record=0; record<cases.length; record++){
        if(record!=0){
            for(var item=0; item<cases[record].length; item++){
                if(item==0){
                    var splitted = cases[record][item].split('-');
                    cases[record][item] = new Date(splitted[0], parseInt(splitted[1])-1, splitted[2]);
                }
                else{
                    cases[record][item] = parseInt(cases[record][item]);
                }
            }
        }
    }
    for(var record=0; record<cases.length; record++){
        if (cases[record].map(Number).indexOf(+startdate) !== -1){
            var pos = cases.indexOf(cases[record]);
        }
    }
    var casesranged = cases.slice(pos-1, cases.length);
    casesranged[0] = cases[0];

    var data = google.visualization.arrayToDataTable(casesranged);
    var options = {
        title: 'Daily Cases',
        legend: { position: 'right' },
        focusTarget: 'category',
        height: jQuery(window).height()*1.2,
        width: jQuery(window).width()*0.98,
        vAxis: { viewWindow: { min:0 } },
        backgroundColor: 'white'
    };
    var chart = new google.visualization.LineChart(document.getElementById('dailycases'));
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
