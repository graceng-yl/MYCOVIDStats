<?php 
    include('header.php'); 

    $date = date("Y-m-d", strtotime("-1 days"));
    echo '<p>'.$date.'</p>'; #chg to check whether csv got today data onot, if no only show ytd

    $cases_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_malaysia.csv');
    $death_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_malaysia.csv');
    $vac_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_malaysia.csv');
    $cases_list = explode("\n", $cases_data);
    $death_list = explode("\n", $death_data);
    $vac_list = explode("\n", $vac_data);
    
    //retrieve today record for overview, can be optimized
    foreach($cases_list as $cases_record) {
        if (strpos($cases_record, $date) !== false){
            $cases_record_today = $cases_record; 
            $index = array_search($cases_record, $cases_list);
            $cases_record_ytd = $cases_list[$index-1];
        }
    }
    $cases_today = explode(',',$cases_record_today);
    $cases_ytd = explode(',', $cases_record_ytd);

    foreach($death_list as $death_record) {
        if (strpos($death_record, $date) !== false){
            $death_record_today = $death_record; 
        }
    }
    $death_today = explode(',',$death_record_today);

    foreach($vac_list as $vac_record) {
        if (strpos($vac_record, $date) !== false){
            $vac_record_today = $vac_record; 
        }
    }
    $vac_today = explode(',',$vac_record_today);


    //retrive all records along with neccessary col for graph
    $graph_array = array(); 
    $i = 0;
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
            }elseif($datematch=='Y' && $vacdatematch=='N'){
                array_push($graph_array, [$case_temp[0], $case_temp[1], $case_temp[4], $case_temp[3], $death_temp[1], 0]);
            }elseif($datematch=='N' && $vacdatematch=='Y'){
                array_push($graph_array, [$case_temp[0], $case_temp[1], $case_temp[4], $case_temp[3], 0, $vac_temp[4]]);
            }else{
                array_push($graph_array, [$case_temp[0], $case_temp[1], $case_temp[4], $case_temp[3], 0, 0]);
            }
        }
    }
    //print_r($cases_array);
?>

    <div>
        <b>Cases</b>
        <p>New cases</p>
        <p><?php echo $cases_today[1]; ?></p>
        <p>Local <?php echo (int)$cases_today[1] - (int)$cases_today[2]; ?></p>
        <p>Imported <?php echo $cases_today[2]; ?></p>
        <p>Active cases</p>
        <p><?php echo $cases_today[4] . ' ' . (int)$cases_today[4]-(int)$cases_ytd[4]; ?></p>
    </div>
    <div>
        <b>Recovered</b>
        <p><?php echo $cases_today[3]; ?></p>
    </div>

    <div>
        <b>Deaths</b>
        <p><?php echo $death_today[1]; ?></p>
    </div>

    <div>
        <b>Vaccinations</b>
        <p><?php echo $vac_today[4]; ?></p>
    </div>

    <script>
        var graph_array=<?php echo json_encode($graph_array); ?>;
    </script>
<div id="dailycases"></div>

<?php include('footer.php'); ?>
