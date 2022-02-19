google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var test = anarray;
    var startdate = new Date('2021', '0', '1'); //to choose start and end range

    for(var record=0; record<test.length; record++){
        if(record!=0){
            for(var item=0; item<test[record].length; item++){
                if(item==0){
                    var splitted = test[record][item].split('-');
                    test[record][item] = new Date(splitted[0], parseInt(splitted[1])-1, splitted[2]);
                }
                else{
                    test[record][item] = parseInt(test[record][item]);
                }
            }
        }
    }
    console.log(startdate);
    for(var record=0; record<test.length; record++){
        if (test[record].map(Number).indexOf(+startdate) !== -1){
            var pos = test.indexOf(test[record]);
        }
    }
    var testranged = test.slice(pos-1, test.length);
    testranged[0] = test[0];
    console.log(testranged);

    var data = google.visualization.arrayToDataTable(testranged);

      var options = {
        title: 'Daily Cases',
        curveType: 'function',
        legend: { position: 'bottom' }
      };

      var chart = new google.visualization.LineChart(document.getElementById('dailycases'));

      chart.draw(data, options);
  }