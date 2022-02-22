<?php 
    include('header.php'); 
    $state = ''; //to chg
    $date = date("Y-m-d", strtotime("-1 days")); //chg to check whether csv got today data onot, if no only show yth

    $graph_array = array(); 
    $i = 0;
    $datematch = 'N';
    $vacdatematch = 'N';

    //retrieve yth active cases for overview
    if($state != ''){
        $state_cases_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_state.csv');
        $state_deaths_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_state.csv');
        $state_vac_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_state.csv');
        
        $state_cases_list = explode("\n", $state_cases_data);
        $state_deaths_list = explode("\n", $state_deaths_data);
        $state_vac_list = explode("\n", $state_vac_data);


        foreach($state_cases_list as $state_cases_record) {
            if (strpos($state_cases_record, $date) !== false && strpos($state_cases_record, $state)!==false){
                $index = array_search($state_cases_record, $state_cases_list);
                $cases_record_yth = $state_cases_list[$index-16];
            }
        }
        $cases_yth = explode(',', $cases_record_yth)[5];

        foreach ($state_cases_list as $state_case_record){ 
            if($i==0){ //if first row
                array_push($graph_array, ['Date', 'New Cases', 'Active Cases', 'Recovered', 'Deaths', 'Vaccines Administered']);
                $i = 1; //done, set to any other value so that the data rows use elseif
            }
            elseif ($state_case_record != '' && strpos($state_case_record, $state)!==false){
                $state_case_temp = explode(',', $state_case_record);
                foreach($state_deaths_list as $state_death_record) { //compare with death csv, if date different set 0 death
                    if (strpos($state_death_record, $state_case_temp[0]) !== false && strpos($state_death_record, $state)!==false){
                        $datematch = 'Y'; 
                        $state_death_temp = explode(',', $state_death_record);
                        break;
                    }else{
                        $state_datematch = 'N';
                    }
                }
                foreach($state_vac_list as $state_vac_record){ //compare with vac csv, if date different set 0 vac
                    if (strpos($state_vac_record, $state_case_temp[0]) !== false && strpos($state_vac_record, $state)!==false){
                        $vacdatematch = 'Y'; 
                        $state_vac_temp = explode(',', $state_vac_record);
                        break;
                    }else{
                        $vacdatematch = 'N';
                    }
                }
                if($datematch=='Y' && $vacdatematch=='Y'){
                    array_push($graph_array, [$state_case_temp[0], $state_case_temp[2], $state_case_temp[5], $state_case_temp[4], $state_death_temp[2], $state_vac_temp[5]]);
                    if($state_case_temp[0] == $date){
                        $today_data = [$state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], $state_death_temp[2], $state_vac_temp[5]];
                        //cases_new,cases_import,cases_active,cases_recovered,deaths_new,daily
                    }
                }elseif($datematch=='Y' && $vacdatematch=='N'){
                    array_push($graph_array, [$state_case_temp[0], $state_case_temp[2], $state_case_temp[5], $state_case_temp[4], $state_death_temp[2], 0]);
                    if($state_case_temp[0] == $date){
                        $today_data = [$state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], $state_death_temp[2], 0];
                    }
                }elseif($datematch=='N' && $vacdatematch=='Y'){
                    array_push($graph_array, [$state_case_temp[0], $state_case_temp[2], $state_case_temp[5], $state_case_temp[4], 0, $state_vac_temp[5]]);
                    if($state_case_temp[0] == $date){
                        $today_data = [$state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], 0, $state_vac_temp[5]];
                    }
                }else{
                    array_push($graph_array, [$state_case_temp[0], $state_case_temp[2], $state_case_temp[5], $state_case_temp[4], 0, 0]);
                    if($state_case_temp[0] == $date){
                        $today_data = [$state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], 0, 0];
                    }
                }
            }
        }
    }


    else{
        // if(!isset($_COOKIE['statecases'])){
        //     setcookie("statecases", file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_state.csv'), strtotime("tomorrow"));
        // }if(!isset($_COOKIE['statedeaths'])){
        //     setcookie("statedeaths", file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_state.csv'), strtotime("tomorrow"));
        // }if(!isset($_COOKIE['statevac'])){
        //     setcookie("statevac", file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_state.csv'), strtotime("tomorrow"));
            
        // }

        #print_r($state_cases);
        // $cases_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_malaysia.csv');
        // $death_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_malaysia.csv');
        // $vac_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_malaysia.csv');
        // $cases_list = explode("\n", $cases_data);
        // $death_list = explode("\n", $death_data);
        // $vac_list = explode("\n", $vac_data);
        // $state_cases_list = explode("\n", $_COOKIE['statecases']);
        //     $state_deaths_list = explode("\n", $_COOKIE['statedeaths']);
        //     $state_vac_list = explode("\n", $_COOKIE['statevac']);
        //get all states today data

        $today_state_data = array();
        foreach($cases_list as $cases_record) {
            if (strpos($cases_record, $date) !== false){
                $index = array_search($cases_record, $cases_list);
                $cases_record_yth = $cases_list[$index-1];
            }
        }
        $cases_yth = explode(',', $cases_record_yth)[4];

        foreach ($cases_list as $case_record){ 
            if($i==0){ //if first row
                array_push($graph_array, ['Date', 'New Cases', 'Active Cases', 'Recovered', 'Deaths', 'Vaccines Administered']);
                $i = 1; //done, set to any other value so that the data rows use elseif
            }
            elseif ($case_record != ''){
                $case_temp = explode(',', $case_record);
                foreach($death_list as $death_record) { //compare with death csv, if date different set 0 death
                    if (strpos($death_record, $case_temp[0]) !== false){
                        $datematch = 'Y'; 
                        $death_temp = explode(',', $death_record);
                        break;
                    }else{
                        $datematch = 'N';
                    }
                }
                foreach($vac_list as $vac_record){ //compare with vac csv, if date different set 0 vac
                    if (strpos($vac_record, $case_temp[0]) !== false){
                        $vacdatematch = 'Y'; 
                        $vac_temp = explode(',', $vac_record);
                        break;
                    }else{
                        $vacdatematch = 'N';
                    }
                }
                if($datematch=='Y' && $vacdatematch=='Y'){
                    array_push($graph_array, [$case_temp[0], $case_temp[1], $case_temp[4], $case_temp[3], $death_temp[1], $vac_temp[4]]);
                    if($case_temp[0] == $date){
                        $today_data = [$case_temp[1], $case_temp[2], $case_temp[4], $case_temp[3], $death_temp[1], $vac_temp[4]];
                        //cases_new,cases_import,cases_active,cases_recovered,deaths_new,daily
                    }
                }elseif($datematch=='Y' && $vacdatematch=='N'){
                    array_push($graph_array, [$case_temp[0], $case_temp[1], $case_temp[4], $case_temp[3], $death_temp[1], 0]);
                    if($case_temp[0] == $date){
                        $today_data = [$case_temp[1], $case_temp[2], $case_temp[4], $case_temp[3], $death_temp[1], 0];
                    }
                }elseif($datematch=='N' && $vacdatematch=='Y'){
                    array_push($graph_array, [$case_temp[0], $case_temp[1], $case_temp[4], $case_temp[3], 0, $vac_temp[4]]);
                    if($case_temp[0] == $date){
                        $today_data = [$case_temp[1], $case_temp[2], $case_temp[4], $case_temp[3], 0, $vac_temp[4]];
                    }
                }else{
                    array_push($graph_array, [$case_temp[0], $case_temp[1], $case_temp[4], $case_temp[3], 0, 0]);
                    if($case_temp[0] == $date){
                        $today_data = [$case_temp[1], $case_temp[2], $case_temp[4], $case_temp[3], 0, 0];
                    }
                }
            }
        }


        foreach ($state_cases_list as $state_case_record){ 
            // if($i==0){ //if first row
            //     array_push($graph_array, ['Date', 'New Cases', 'Active Cases', 'Recovered', 'Deaths', 'Vaccines Administered']);
            //     $i = 1; //done, set to any other value so that the data rows use elseif
            // }
            // elseif ($state_case_record != '' && strpos($state_case_record, $state)!==false){
                $state_case_temp = explode(',', $state_case_record);
                if(isset($state_case_temp[1])){
                    $curr_state = $state_case_temp[1];
                }
                foreach($state_deaths_list as $state_death_record) { //compare with death csv, if date different set 0 death
                    if (strpos($state_death_record, $state_case_temp[0]) !== false && strpos($state_death_record, $curr_state)!==false){
                        $datematch = 'Y'; 
                        $state_death_temp = explode(',', $state_death_record);
                        break;
                    }else{
                        $state_datematch = 'N';
                    }
                }
                foreach($state_vac_list as $state_vac_record){ //compare with vac csv, if date different set 0 vac
                    if (strpos($state_vac_record, $state_case_temp[0]) !== false && strpos($state_vac_record, $curr_state)!==false){
                        $vacdatematch = 'Y'; 
                        $state_vac_temp = explode(',', $state_vac_record);
                        break;
                    }else{
                        $vacdatematch = 'N';
                    }
                }
                if($datematch=='Y' && $vacdatematch=='Y' && $state_case_temp[0] == $date){
                    array_push($today_state_data, [$state_case_temp[1], $state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], $state_death_temp[2], $state_vac_temp[5]]);
                    //state,cases_new,cases_import,cases_active,cases_recovered,deaths_new,daily
                }elseif($datematch=='Y' && $vacdatematch=='N' && $state_case_temp[0] == $date){
                    array_push($today_state_data, [$state_case_temp[1], $state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], $state_death_temp[2], 0]);
                }elseif($datematch=='N' && $vacdatematch=='Y' && $state_case_temp[0] == $date){
                    array_push($today_state_data, [$state_case_temp[1], $state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], 0, $state_vac_temp[5]]);
                }elseif($datematch=='N' && $vacdatematch=='N' && $state_case_temp[0] == $date){
                    array_push($today_state_data, [$state_case_temp[1], $state_case_temp[2], $state_case_temp[3], $state_case_temp[5], $state_case_temp[4], 0, 0]);
                }
           // }
        }
    }
    
    
    //make data first, rather then format and check tgt?
    // use date as array key to avoid nested loop (loading due to get_file_content in state page)

    //retrive today record for overview and all records along with neccessary col for graph

?>
    <script>var graph_array=<?php echo json_encode($graph_array); ?>;</script>
    <p><?php echo $state; ?></p>
    <div><?php echo $date; ?></div>


    <div>
        <b>Cases</b>
        <p>New cases</p>
        <p><?php echo $today_data[0]; ?></p>
        <p>Local <?php echo (int)$today_data[0] - (int)$today_data[1]; ?></p>
        <p>Imported <?php echo $today_data[1]; ?></p>
        <p>Active cases</p>
        <p><?php echo $today_data[2] . ' ' . (int)$today_data[2]-(int)$cases_yth; ?></p>
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
        <table>
            <tr>
                <th>State</th><th>New Cases</th><th>Local Cases</th><th>Import Cases</th><th>Active Cases</th><th>Recovered Cases</th><th>Deaths</th><th>Vaccination</th>
            </tr>
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
?>
        </table>
<?php
    }
?>
    <div id="dailycases"></div>

<?php
    include('footer.php'); 
?>
