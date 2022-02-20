<?php 
    include('header.php'); 
    $state = 'Melaka'; //to chg
    $date = date("Y-m-d", strtotime("-1 days")); //chg to check whether csv got today data onot, if no only show ytd

    $cases_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_malaysia.csv');
    $death_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_malaysia.csv');
    $vac_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_malaysia.csv');
    $state_cases_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_state.csv');
    $state_deaths_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_state.csv');
    $state_vac_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_state.csv');

    $cases_list = explode("\n", $cases_data);
    $death_list = explode("\n", $death_data);
    $vac_list = explode("\n", $vac_data);
    $state_cases_list = explode("\n", $state_cases_data);
    $state_deaths_list = explode("\n", $state_deaths_data);
    $state_vac_list = explode("\n", $state_vac_data);
    
    //retrieve ytd active cases for overview
    if($state != ''){
        foreach($state_cases_list as $state_cases_record) {
            if (strpos($state_cases_record, $date) !== false && strpos($state_cases_record, $state)!==false){
                $index = array_search($state_cases_record, $state_cases_list);
                $cases_record_ytd = $state_cases_list[$index-16];
            }
        }
        $cases_ytd = explode(',', $cases_record_ytd)[5];
    }
    else{
        foreach($cases_list as $cases_record) {
            if (strpos($cases_record, $date) !== false){
                $index = array_search($cases_record, $cases_list);
                $cases_record_ytd = $cases_list[$index-1];
            }
        }
        $cases_ytd = explode(',', $cases_record_ytd)[4];
    }
    
    
    //make data first, rather then format and check tgt?

    //retrive today record for overview and all records along with neccessary col for graph
    $graph_array = array(); 
    $i = 0;
    $datematch = 'N';
    $vacdatematch = 'N';

    if($state != ''){
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
    }

?>
    <p><?php echo $state; ?></p>
    <div><?php echo $date; ?></div>


    <div>
        <b>Cases</b>
        <p>New cases</p>
        <p><?php echo $today_data[0]; ?></p>
        <p>Local <?php echo (int)$today_data[0] - (int)$today_data[1]; ?></p>
        <p>Imported <?php echo $today_data[1]; ?></p>
        <p>Active cases</p>
        <p><?php echo $today_data[2] . ' ' . (int)$today_data[2]-(int)$cases_ytd; ?></p>
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

    <script>
        var graph_array=<?php echo json_encode($graph_array); ?>;
    </script>
<div id="dailycases"></div>

<?php include('footer.php'); ?>
