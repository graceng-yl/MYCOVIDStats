<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="style.css">
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="script.js"></script>
    </head>

    <body>
        <header>
            <nav>
                <div><a href='index.php'>COVID-19</p></div>
                <div>
                    <a href='index.php'>Overview</a>
                    <a href='cases.php'>Cases</a>
                    <a href='deaths.php'>Deaths</a>
                    <a href='vax.php'>Vaccination</a>
                    <select></select>
                </div>
            </nav>
        </header>
<?php
    $date = date("Y-m-d", strtotime("-1 days"));
    $cases_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/cases_malaysia.csv');
    $death_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/epidemic/deaths_malaysia.csv');
    $vac_data = file_get_contents('https://raw.githubusercontent.com/MoH-Malaysia/covid19-public/main/vaccination/vax_malaysia.csv');
    $cases_list = explode("\n", $cases_data);
    $death_list = explode("\n", $death_data);
    $vac_list = explode("\n", $vac_data);
    
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
?>