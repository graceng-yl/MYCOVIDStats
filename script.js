google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);
// show active case
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
    //console.log(deaths);
    for(var record=0; record<cases.length; record++){
        if (cases[record].map(Number).indexOf(+startdate) !== -1){
            var pos = cases.indexOf(cases[record]);
        }
    }
    var casesranged = cases.slice(pos-1, cases.length);
    casesranged[0] = cases[0];
    console.log(casesranged);

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
  }