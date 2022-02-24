<?php 
    include('pages/header.php'); 
    include('pages/data.php');
?>
    <script>
        //for data passing
        var data_all=<?php echo json_encode($data_all); ?>;
        var date_tdy=<?php echo json_encode($date_tdy); ?>;
        var date_min=<?php echo json_encode($date_min); ?>;
    </script>

    <p id='state_name' title='<?php echo $state; ?>'><?php echo $state; ?></p>
    <div><?php echo $date_tdy; ?></div>

    <div>
        <b>Cases</b>
        <p>New cases</p>
        <p><?php echo $data_tdy[0]; ?></p>
        <p>Local <?php echo (int)$data_tdy[0] - (int)$data_tdy[1]; ?></p>
        <p>Imported <?php echo $data_tdy[1]; ?></p>
        <p>Active cases</p>
        <p><?php echo $data_tdy[2]; ?></p>
        <span>
<?php 
            if((int)$data_tdy[2]-(int)$active_ytd < 0){
                echo '-';
            }elseif((int)$data_tdy[2]-(int)$active_ytd > 0){
                echo '+';
            } 
            echo (int)$data_tdy[2]-(int)$active_ytd; 
?>
        </span>
    </div>
    <div>
        <b>Recovered</b>
        <p><?php echo $data_tdy[3]; ?></p>
    </div>

    <div>
        <b>Deaths</b>
        <p><?php echo $data_tdy[4]; ?></p>
    </div>

    <div>
        <b>Vaccinations</b>
        <p><?php echo $data_tdy[5]; ?></p>
    </div>

<?php 
    if($state==''){
?>
        <table id='states_table'>
            <thead>
                <tr>
                    <th>State</th><th>New Cases</th><th>Local Cases</th><th>Import Cases</th><th>Active Cases</th><th>Recovered Cases</th><th>Deaths</th><th>Vaccination</th>
                </tr>
            </thead>
            <tbody>
<?php
        foreach($data_tdy_states as $data_tdy_state){
?>
            <tr>
                <td><?php echo $data_tdy_state[0]; ?></td>
                <td><?php echo $data_tdy_state[1]; ?></td>
                <td><?php echo (int)$data_tdy_state[1]-(int)$data_tdy_state[2]; ?></td>
                <td><?php echo $data_tdy_state[2]; ?></td>
                <td><?php echo $data_tdy_state[3]; ?></td>
                <td><?php echo $data_tdy_state[4]; ?></td>
                <td><?php echo $data_tdy_state[5]; ?></td>
                <td><?php echo $data_tdy_state[6]; ?></td>
            </tr>
<?php
        }
?>          </tbody>
            <tr class='states_table_last'>
                <td></td>
                <td><?php echo $data_tdy[0]; ?></td>
                <td><?php echo (int)$data_tdy[0]-(int)$data_tdy[1]; ?></td>
                <td><?php echo $data_tdy[1]; ?></td>
                <td><?php echo $data_tdy[2]; ?></td>
                <td><?php echo $data_tdy[3]; ?></td>
                <td><?php echo $data_tdy[4]; ?></td>
                <td><?php echo $data_tdy[5]; ?></td>
            </tr>
        </table>
<?php
    }
?>  
    <form onsubmit="return false;">
        <input type="date" id='start_date'>
        <input type="date" id='end_date'>
        <input type="submit" value="Filter" id='submit_filter'>
        
    </form>
    <span id='filter_message'>Please select a valid range</span>
    <div id="trend_graph"></div>

<?php
    include('pages/footer.php'); 
?>
