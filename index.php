<?php 
    include('header.php'); 
    // the program only show ytd data
    $state = ''; //to chg
    $date = date("Y-m-d", strtotime("-1 days")); 
    $ytddate = date("Y-m-d", strtotime("-2 days")); 

    if(file_exists("state_cases_".$ytddate.".csv")){
        unlink("state_cases_".$ytddate.".csv");
        unlink("state_deaths_".$ytddate.".csv");
        unlink("state_vac_".$ytddate.".csv");
        unlink("msia_cases_".$ytddate.".csv");
        unlink("msia_deaths_".$ytddate.".csv");
        unlink("msia_vac_".$ytddate.".csv");
    }

    if(!file_exists("state_cases_".$date.".csv")){
        file_put_contents("state_cases_".$date.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_state.csv"));
    }if(!file_exists("state_deaths_".$date.".csv")){
        file_put_contents("state_deaths_".$date.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_state.csv"));
    }if(!file_exists("state_vac_".$date.".csv")){
        file_put_contents("state_vac_".$date.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_state.csv"));
    }if(!file_exists("msia_cases_".$date.".csv")){
        file_put_contents("msia_cases_".$date.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_malaysia.csv"));
    }if(!file_exists("msia_deaths_".$date.".csv")){
        file_put_contents("msia_deaths_".$date.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_malaysia.csv"));
    }if(!file_exists("msia_vac_".$date.".csv")){
        file_put_contents("msia_vac_".$date.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_malaysia.csv"));
    }
    
    $state_cases = array_map('str_getcsv', file("state_cases_".$date.".csv"));
    $state_deaths = array_map('str_getcsv', file("state_deaths_".$date.".csv"));
    $state_vac = array_map('str_getcsv', file("state_vac_".$date.".csv"));
    $msia_cases = array_map('str_getcsv', file("msia_cases_".$date.".csv"));
    $msia_deaths = array_map('str_getcsv', file("msia_deaths_".$date.".csv"));
    $msia_vac = array_map('str_getcsv', file("msia_vac_".$date.".csv"));

    $graph_array = array(); 
    $rowno = 0;
    // $datematch = 'N';
    // $vacdatematch = 'N';
    $today_state_data = array();

    //main page
    if($state == ''){
        //today data
        foreach ($msia_cases as $case_record){ 
            if($rowno==0){ //if first row
                array_push($graph_array, ['Date', 'New Cases', 'Active Cases', 'Recovered', 'Deaths', 'Vaccines Administered']);
                $rowno = 1; //done, set to any other value so that the data rows use elseif
            }
            else{
                if ($case_record[0] == $ytddate){ //ytd data
                    $cases_ytd = $case_record[4];
                }
                foreach($msia_deaths as $death_record) { //compare with death csv, if date different set 0 death
                    if ($death_record[0] == $case_record[0]){
                        $datematch = 'Y'; 
                        break;
                    }else{
                        $datematch = 'N';
                    }
                }
                foreach($msia_vac as $vac_record){ //compare with vac csv, if date different set 0 vac
                    if ($vac_record[0] == $case_record[0]){
                        $vacdatematch = 'Y'; 
                        break;
                    }else{
                        $vacdatematch = 'N';
                    }
                }
                if($datematch=='Y' && $vacdatematch=='Y' && $state == ''){
                    array_push($graph_array, [$case_record[0], $case_record[1], $case_record[4], $case_record[3], $death_record[1], $vac_record[4]]);
                    if($case_record[0] == $date){
                        $today_data = [$case_record[1], $case_record[2], $case_record[4], $case_record[3], $death_record[1], $vac_record[4]];
                        //cases_new,cases_import,cases_active,cases_recovered,deaths_new,daily
                    }
                }elseif($datematch=='Y' && $vacdatematch=='N' && $state == ''){
                    array_push($graph_array, [$case_record[0], $case_record[1], $case_record[4], $case_record[3], $death_record[1], 0]);
                    if($case_record[0] == $date){
                        $today_data = [$case_record[1], $case_record[2], $case_record[4], $case_record[3], $death_record[1], 0];
                    }
                }elseif($datematch=='N' && $vacdatematch=='Y' && $state == ''){
                    array_push($graph_array, [$case_record[0], $case_record[1], $case_record[4], $case_record[3], 0, $vac_record[4]]);
                    if($case_record[0] == $date){
                        $today_data = [$case_record[1], $case_record[2], $case_record[4], $case_record[3], 0, $vac_record[4]];
                    }
                }elseif($datematch=='N' && $vacdatematch=='Y' && $state == ''){
                    array_push($graph_array, [$case_record[0], $case_record[1], $case_record[4], $case_record[3], 0, 0]);
                    if($case_record[0] == $date){
                        $today_data = [$case_record[1], $case_record[2], $case_record[4], $case_record[3], 0, 0];
                    }
                }
            }
        }

        //table data
        $i = 0;
        foreach($state_cases as $state_cases_record1){
            if($state_cases_record1[0]==$date){
                $today_state_data[$i] = [$state_cases_record1[1], $state_cases_record1[2], $state_cases_record1[3], $state_cases_record1[5], $state_cases_record1[4]];
                $i++;
            }
            
        }
        $i = 0;
        foreach($state_deaths as $state_deaths_record1){
            if($state_deaths_record1[0]==$date){
                array_push($today_state_data[$i], $state_deaths_record1[2]);
                $i++;
            }
        }
        $i = 0;
        foreach($state_vac as $state_vac_record1){
            if($state_vac_record1[0]==$date){
                array_push($today_state_data[$i], $state_vac_record1[4]);
                $i++;
            }
        }
    }

    //state data
    else{
        //retrieve ytd active cases for overview
        // foreach($state_cases as $state_cases_record) {
        //     if ($state_cases_record[0] == $date && $state_cases_record[1] == $state){
        //         $index = array_search($state_cases_record, $state_cases);
        //         $cases_record_ytd = $state_cases[$index-16];
        //     }
        // }
        // $cases_ytd = $cases_record_ytd;
        
        //today data
        foreach ($state_cases as $state_case_record){ 
            if($rowno==0){ //if first row
                array_push($graph_array, ['Date', 'New Cases', 'Active Cases', 'Recovered', 'Deaths', 'Vaccines Administered']);
                $rowno = 1; //done, set to any other value so that the data rows use elseif
            }
            elseif ($state_case_record[1] == $state){
                if ($state_case_record[0] == $ytddate && $state_case_record[1] == $state){ //ytd data
                    $cases_ytd = $state_case_record[5];
                }
                //$state_case_record = explode(',', $state_case_record);
                foreach($state_deaths as $state_death_record) { //compare with death csv, if date different set 0 death
                    if ($state_death_record[0] == $state_case_record[0] && $state_death_record[1] == $state){
                        $datematch = 'Y'; 
                        break;
                    }else{
                        $datematch = 'N';
                    }
                }
                foreach($state_vac as $state_vac_record){ //compare with vac csv, if date different set 0 vac
                    if ($state_vac_record[0] == $state_case_record[0] && $state_vac_record[1] == $state){
                        $vacdatematch = 'Y'; 
                        break;
                    }else{
                        $vacdatematch = 'N';
                    }
                }
                
                if($datematch=='Y' && $vacdatematch=='Y'){
                    array_push($graph_array, [$state_case_record[0], $state_case_record[2], $state_case_record[5], $state_case_record[4], $state_death_record[2], $state_vac_record[5]]);
                    if($state_case_record[0] == $date){
                        $today_data = [$state_case_record[2], $state_case_record[3], $state_case_record[5], $state_case_record[4], $state_death_record[2], $state_vac_record[5]];
                        //cases_new,cases_import,cases_active,cases_recovered,deaths_new,daily
                    }
                }elseif($datematch=='Y' && $vacdatematch=='N'){
                    array_push($graph_array, [$state_case_record[0], $state_case_record[2], $state_case_record[5], $state_case_record[4], $state_death_record[2], 0]);
                    if($state_case_record[0] == $date){
                        $today_data = [$state_case_record[2], $state_case_record[3], $state_case_record[5], $state_case_record[4], $state_death_record[2], 0];
                    }
                }elseif($datematch=='N' && $vacdatematch=='Y'){
                    array_push($graph_array, [$state_case_record[0], $state_case_record[2], $state_case_record[5], $state_case_record[4], 0, $state_vac_record[5]]);
                    if($state_case_record[0] == $date){
                        $today_data = [$state_case_record[2], $state_case_record[3], $state_case_record[5], $state_case_record[4], 0, $state_vac_record[5]];
                    }
                }else{
                    array_push($graph_array, [$state_case_record[0], $state_case_record[2], $state_case_record[5], $state_case_record[4], 0, 0]);
                    if($state_case_record[0] == $date){
                        $today_data = [$state_case_record[2], $state_case_record[3], $state_case_record[5], $state_case_record[4], 0, 0];
                    }
                }
            }
        }
    }
    

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
            <tr>
                <td>Sum</td>
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
    <div id="dailycases"></div>

<?php
    include('footer.php'); 
?>
