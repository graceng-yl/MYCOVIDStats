<!DOCTYPE html>
<html>
    <head>
        <title>MY COVID Stats</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
        <script src="script.js"></script>

        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <nav>
                <div><a href='index.php'>MY COVID Stats</a></div>
                <div>
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
                </div>
            </nav>
        </header>
