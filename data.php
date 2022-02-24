<?php
    // the program only show ytd data

use function PHPSTORM_META\type;

    $state = '';
    if(isset($_GET['selectedstate'])){
        $state = $_GET['selectedstate']; 
    }
    $date = date("Y-m-d", strtotime("-1 days")); 
    $ytddate = date("Y-m-d", strtotime("-2 days")); 

    //delete old files
    $prevcsv = glob("state_cases_*.csv");
    foreach($prevcsv as $csv){
        if(explode('state_cases_', $csv)[1] != $date.'.csv'){
            $prevdate = explode('state_cases_', $csv)[1];
            unlink("state_cases_".$prevdate);
            unlink("state_deaths_".$prevdate);
            unlink("state_vac_".$prevdate);
            unlink("msia_cases_".$prevdate);
            unlink("msia_deaths_".$prevdate);
            unlink("msia_vac_".$prevdate);
        }
    }

    //dl new file if not yet dl
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
                //print(gettype($case_record[0]));
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
                }elseif($datematch=='N' && $vacdatematch=='N' && $state == ''){
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