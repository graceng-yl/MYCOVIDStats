<!DOCTYPE html>
<html>
    <head>
        <title>
            MY COVID Stats
<?php
            if(isset($_GET['state_select']) && $_GET['state_select'] != ''){
                echo ' - '.$_GET['state_select'];
            }
?>
        </title>
        <meta name="description" content="View the latest Malaysia COVID-19 statistics by states">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="content/icon.png">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="js/script.js"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body id="stop-scrolling">
        <div class="preloader">
            Just taking a few seconds for statistics update :D
        </div>
        <header>
            <nav class="navbar navbar-expand-lg sticky-top">
                <ul class="nav container">
                    <li class="nav-item" id="nav_left">
                        <a class="navbar-brand" href='index.php'>MY COVID Stats</a>
                    </li>
                    <li class="nav-item" id="nav_right">
                        <span>State: </span>
                        <form method="GET" id='state_form'>
                            <select name="state_select" id='state_form_dropdown'>
                                <option value=''>---</option>
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Pulau Pinang">Pulau Pinang</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                                <option value="W.P. Kuala Lumpur">W.P. Kuala Lumpur</option>
                                <option value="W.P. Labuan">W.P. Labuan</option>
                                <option value="W.P. Putrajaya">W.P. Putrajaya</option>
                            </select>
                        </form>
                    </li>
                </ul>
            </nav>
        </header>
