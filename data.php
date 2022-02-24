<?php
    // the program only show ytd data
    $state = '';
    if(isset($_GET['state_select'])){
        $state = $_GET['state_select']; 
    }
    $date_min = "2020-01-25"; //earliest record date from KKM
    $date_tdy = date("Y-m-d", strtotime("-1 days")); //current date to show
    $date_ytd = date("Y-m-d", strtotime("-2 days")); //the day before current showing data

    //delete old files
    $csv_prev = glob("state_cases_*.csv");
    foreach($csv_prev as $csv){
        if(explode('state_cases_', $csv)[1] != $date_tdy.'.csv'){
            $date_prev = explode('state_cases_', $csv)[1];
            unlink("state_cases_".$date_prev);
            unlink("state_deaths_".$date_prev);
            unlink("state_vacs_".$date_prev);
            unlink("msia_cases_".$date_prev);
            unlink("msia_deaths_".$date_prev);
            unlink("msia_vacs_".$date_prev);
        }
    }

    //dl new file if not yet dl
    if(!file_exists("state_cases_".$date_tdy.".csv")){
        file_put_contents("state_cases_".$date_tdy.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_state.csv"));
    }if(!file_exists("state_deaths_".$date_tdy.".csv")){
        file_put_contents("state_deaths_".$date_tdy.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_state.csv"));
    }if(!file_exists("state_vacs_".$date_tdy.".csv")){
        file_put_contents("state_vacs_".$date_tdy.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_state.csv"));
    }if(!file_exists("msia_cases_".$date_tdy.".csv")){
        file_put_contents("msia_cases_".$date_tdy.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_malaysia.csv"));
    }if(!file_exists("msia_deaths_".$date_tdy.".csv")){
        file_put_contents("msia_deaths_".$date_tdy.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_malaysia.csv"));
    }if(!file_exists("msia_vacs_".$date_tdy.".csv")){
        file_put_contents("msia_vacs_".$date_tdy.".csv", file_get_contents("https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_malaysia.csv"));
    }
    
    $state_cases = array_map('str_getcsv', file("state_cases_".$date_tdy.".csv"));
    $state_deaths = array_map('str_getcsv', file("state_deaths_".$date_tdy.".csv"));
    $state_vacs = array_map('str_getcsv', file("state_vacs_".$date_tdy.".csv"));
    $msia_cases = array_map('str_getcsv', file("msia_cases_".$date_tdy.".csv"));
    $msia_deaths = array_map('str_getcsv', file("msia_deaths_".$date_tdy.".csv"));
    $msia_vacs = array_map('str_getcsv', file("msia_vacs_".$date_tdy.".csv"));

    $data_all = array(); 
    $row = 0;
    $data_tdy_states = array();

    //main page
    if($state == ''){
        //today data
        foreach ($msia_cases as $msia_case){ 
            if($row==0){ //if first row
                array_push($data_all, ['Date', 'New Cases', 'Active Cases', 'Recovered', 'Deaths', 'Vaccines Administered']);
                $row = 1; //done, set to any other value so that the data rows use elseif
            }
            else{
                //print(gettype($msia_case[0]));
                if ($msia_case[0] == $date_ytd){ //ytd data
                    $active_ytd = $msia_case[4];
                }
                foreach($msia_deaths as $msia_death) { //compare with death csv, if date different set 0 death
                    if ($msia_death[0] == $msia_case[0]){
                        $match_death = 'Y'; 
                        break;
                    }else{
                        $match_death = 'N';
                    }
                }
                foreach($msia_vacs as $msia_vac){ //compare with vac csv, if date different set 0 vac
                    if ($msia_vac[0] == $msia_case[0]){
                        $match_vac = 'Y'; 
                        break;
                    }else{
                        $match_vac = 'N';
                    }
                }
                if($match_death=='Y' && $match_vac=='Y' && $state == ''){
                    array_push($data_all, [$msia_case[0], $msia_case[1], $msia_case[4], $msia_case[3], $msia_death[1], $msia_vac[4]]);
                    if($msia_case[0] == $date_tdy){
                        $data_tdy = [$msia_case[1], $msia_case[2], $msia_case[4], $msia_case[3], $msia_death[1], $msia_vac[4]];
                        //cases_new,cases_import,cases_active,cases_recovered,deaths_new,daily
                    }
                }elseif($match_death=='Y' && $match_vac=='N' && $state == ''){
                    array_push($data_all, [$msia_case[0], $msia_case[1], $msia_case[4], $msia_case[3], $msia_death[1], 0]);
                    if($msia_case[0] == $date_tdy){
                        $data_tdy = [$msia_case[1], $msia_case[2], $msia_case[4], $msia_case[3], $msia_death[1], 0];
                    }
                }elseif($match_death=='N' && $match_vac=='Y' && $state == ''){
                    array_push($data_all, [$msia_case[0], $msia_case[1], $msia_case[4], $msia_case[3], 0, $msia_vac[4]]);
                    if($msia_case[0] == $date_tdy){
                        $data_tdy = [$msia_case[1], $msia_case[2], $msia_case[4], $msia_case[3], 0, $msia_vac[4]];
                    }
                }elseif($match_death=='N' && $match_vac=='N' && $state == ''){
                    array_push($data_all, [$msia_case[0], $msia_case[1], $msia_case[4], $msia_case[3], 0, 0]);
                    if($msia_case[0] == $date_tdy){
                        $data_tdy = [$msia_case[1], $msia_case[2], $msia_case[4], $msia_case[3], 0, 0];
                    }
                }
            }
        }

        //table data
        $i = 0;
        foreach($state_cases as $state_case){
            if($state_case[0]==$date_tdy){
                $data_tdy_states[$i] = [$state_case[1], $state_case[2], $state_case[3], $state_case[5], $state_case[4]];
                $i++;
            }  
        }
        $i = 0;
        foreach($state_deaths as $state_death){
            if($state_death[0]==$date_tdy){
                array_push($data_tdy_states[$i], $state_death[2]);
                $i++;
            }
        }
        $i = 0;
        foreach($state_vacs as $state_vac){
            if($state_vac[0]==$date_tdy){
                array_push($data_tdy_states[$i], $state_vac[4]);
                $i++;
            }
        }
    }

    //state data
    else{        
        //today data
        foreach ($state_cases as $state_case){ 
            if($row==0){ //if first row
                array_push($data_all, ['Date', 'New Cases', 'Active Cases', 'Recovered', 'Deaths', 'Vaccines Administered']);
                $row = 1; //done, set to any other value so that the data rows use elseif
            }
            elseif ($state_case[1] == $state){
                if ($state_case[0] == $date_ytd && $state_case[1] == $state){ //ytd data
                    $active_ytd = $state_case[5];
                }
                //$state_case = explode(',', $state_case);
                foreach($state_deaths as $state_death) { //compare with death csv, if date different set 0 death
                    if ($state_death[0] == $state_case[0] && $state_death[1] == $state){
                        $match_death = 'Y'; 
                        break;
                    }else{
                        $match_death = 'N';
                    }
                }
                foreach($state_vacs as $state_vac){ //compare with vac csv, if date different set 0 vac
                    if ($state_vac[0] == $state_case[0] && $state_vac[1] == $state){
                        $match_vac = 'Y'; 
                        break;
                    }else{
                        $match_vac = 'N';
                    }
                }
                
                if($match_death=='Y' && $match_vac=='Y'){
                    array_push($data_all, [$state_case[0], $state_case[2], $state_case[5], $state_case[4], $state_death[2], $state_vac[5]]);
                    if($state_case[0] == $date_tdy){
                        $data_tdy = [$state_case[2], $state_case[3], $state_case[5], $state_case[4], $state_death[2], $state_vac[5]];
                        //cases_new,cases_import,cases_active,cases_recovered,deaths_new,daily
                    }
                }elseif($match_death=='Y' && $match_vac=='N'){
                    array_push($data_all, [$state_case[0], $state_case[2], $state_case[5], $state_case[4], $state_death[2], 0]);
                    if($state_case[0] == $date_tdy){
                        $data_tdy = [$state_case[2], $state_case[3], $state_case[5], $state_case[4], $state_death[2], 0];
                    }
                }elseif($match_death=='N' && $match_vac=='Y'){
                    array_push($data_all, [$state_case[0], $state_case[2], $state_case[5], $state_case[4], 0, $state_vac[5]]);
                    if($state_case[0] == $date_tdy){
                        $data_tdy = [$state_case[2], $state_case[3], $state_case[5], $state_case[4], 0, $state_vac[5]];
                    }
                }else{
                    array_push($data_all, [$state_case[0], $state_case[2], $state_case[5], $state_case[4], 0, 0]);
                    if($state_case[0] == $date_tdy){
                        $data_tdy = [$state_case[2], $state_case[3], $state_case[5], $state_case[4], 0, 0];
                    }
                }
            }
        }
    }

?>