

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
    <div class="today_div">
        <div class="container">
            <p class="center_div" id="state_div" title='<?php echo $state; ?>'>
<?php 
                if ($state==''){
                    echo 'MY Covid Stats';
                } else{ 
                    echo 'MY <b>'.$state.'</b> Covid Stats'; 
                }
?>
            </p>
            <h1 class="center_div" id="date_div">
                <?php echo date("d F Y", strtotime("-1 days")); ?>
            </h1>
            <p class="center_div" id="refresh_div">
                Last updated: <?php echo date("d F Y, g:i a"); ?> (UTC+8)
            </p>
        </div>

        <div class="container today_data">
            <div class="row">
                <div class="col-sm today_data_card" id="today_data_card_cases">
                    <div>
                        <h2>Cases</h2>
                        <h3>New cases</h3>
                        <p><?php echo $data_tdy[0]; ?></p>
                        <div>
                            <div class="today_data_card_cases_sub">
                                <h4>(Local)</h4>
                                <p><?php echo (int)$data_tdy[0] - (int)$data_tdy[1]; ?></p>
                            </div>
                            <div class="today_data_card_cases_sub">
                                <h4 class="today_data_card_cases_sub">(Imported)</h4>
                                <p><?php echo $data_tdy[1]; ?></p>
                            </div>
                        </div>
                        <h3 class="today_data_card_cases_active">Active cases</h3>
                        <p><span><?php echo $data_tdy[2]; ?></span>
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
                </div>

                <div class="col-sm">
                    <div class="today_data_card" id="today_data_card_recovs">
                        <h2>Recovered</h2>
                        <p><?php echo $data_tdy[3]; ?></p>
                    </div>

                    <div class="today_data_card" id="today_data_card_deaths">
                        <h2>Deaths</h2>
                        <p><?php echo $data_tdy[4]; ?></p>
                    </div>

                    <div class="today_data_card" id="today_data_card_vacs">
                        <h2>Vaccinations</h2>
                        <p><?php echo $data_tdy[5]; ?></p>
                    </div>
                </div>
            </div>
        </div>

                        </div>

<?php 
    if($state==''){
?>
        <hr>
        <section class="container">
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
        </section>
<?php
    }
?>  
    <hr>
    <section class="container">
        <form onsubmit="return false;">
            <input type="date" id='start_date'>
            <input type="date" id='end_date'>
            <input type="submit" value="Filter" id='submit_filter'>
        </form>
        <span id='filter_message'>Please select a valid range</span>
        
    </section>
    <div id="trend_graph"></div>

<?php
    include('pages/footer.php'); 
?>
