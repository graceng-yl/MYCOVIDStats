<?php 
    include('header.php'); 
    include('data.php');
?>
    <script>
        var graph_array=<?php echo json_encode($graph_array); ?>;
        var date=<?php echo json_encode($date); ?>;
        var mindate=<?php echo json_encode($mindate); ?>;
        //console.log(graph_array[1][0]);
    </script>
    <p id='state' title='<?php echo $state; ?>'><?php echo $state; ?></p>
    <div><?php echo $date; ?></div>

    <div>
        <b>Cases</b>
        <p>New cases</p>
        <p><?php echo $today_data[0]; ?></p>
        <p>Local <?php echo (int)$today_data[0] - (int)$today_data[1]; ?></p>
        <p>Imported <?php echo $today_data[1]; ?></p>
        <p>Active cases</p>
        <p><?php echo $today_data[2]; ?></p>
        <span><?php if((int)$today_data[2]-(int)$cases_ytd < 0){
                    echo '-';
                }elseif((int)$today_data[2]-(int)$cases_ytd > 0){
                    echo '+';
                } echo (int)$today_data[2]-(int)$cases_ytd; ?></span>
    </div>
    <div>
        <b>Recovered</b>
        <p><?php echo $today_data[3]; ?></p>
    </div>

    <div>
        <b>Deaths</b>
        <p><?php echo $today_data[4]; ?></p>
    </div>

    <div>
        <b>Vaccinations</b>
        <p><?php echo $today_data[5]; ?></p>
    </div>

<?php 
    if($state==''){
?>
        <table id='staterecords'>
            <thead>
                <tr>
                    <th>State</th><th>New Cases</th><th>Local Cases</th><th>Import Cases</th><th>Active Cases</th><th>Recovered Cases</th><th>Deaths</th><th>Vaccination</th>
                </tr>
            </thead>
            <tbody>
<?php
        foreach($today_state_data as $state_record){
?>
            <tr>
                <td><?php echo $state_record[0]; ?></td>
                <td><?php echo $state_record[1]; ?></td>
                <td><?php echo (int)$state_record[1]-(int)$state_record[2]; ?></td>
                <td><?php echo $state_record[2]; ?></td>
                <td><?php echo $state_record[3]; ?></td>
                <td><?php echo $state_record[4]; ?></td>
                <td><?php echo $state_record[5]; ?></td>
                <td><?php echo $state_record[6]; ?></td>
            </tr>
<?php
        }
?>          </tbody>
            <tr class='sumrecord'>
                <td></td>
                <td><?php echo $today_data[0]; ?></td>
                <td><?php echo (int)$today_data[0]-(int)$today_data[1]; ?></td>
                <td><?php echo $today_data[1]; ?></td>
                <td><?php echo $today_data[2]; ?></td>
                <td><?php echo $today_data[3]; ?></td>
                <td><?php echo $today_data[4]; ?></td>
                <td><?php echo $today_data[5]; ?></td>
            </tr>
        </table>
<?php
    }
?>  
    <form id=dateselector onsubmit="return false;">
        <input type="date" id='startdate'>
        <input type="date" id='enddate'>
        <input type="submit" value="Filter" id='filter'>
        
    </form>
    <span id='filtermessage'>Please select a valid range</span>
    <div id="trendgraph"></div>

<?php
    include('footer.php'); 
?>
