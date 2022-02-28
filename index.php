

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

    <section class="container top_section">
        <div class="container top_section_top">
            <p class="centered_divs" id="state_div" title='<?php echo $state; ?>'>
<?php 
                if ($state==''){
                    echo 'MY Covid Stats';
                } else{ 
                    echo 'MY <b>'.$state.'</b> Covid Stats'; 
                }
?>
            </p>
            <h1 class="centered_divs" id="date_div">
                <?php echo date("d F Y", strtotime("-1 days")); ?>
            </h1>
            <p class="centered_divs" id="refresh_div">
                Last updated: <?php echo date("d F Y, g:i a"); ?> (UTC+8)
            </p>
        </div>

        <div class="container top_section_bottom">
            <div class="row">
                <div class="col-sm data_card">
                    <div class="" id="data_card_cases">
                        <h2>Cases</h2>
                        <h3>New cases</h3>
                        <p class="number_counter"><?php echo $data_tdy[0]; ?></p>
                        <div>
                            <div class="data_card_cases_sub">
                                <h4>(Local)</h4>
                                <p><?php echo (int)$data_tdy[0] - (int)$data_tdy[1]; ?></p>
                            </div>
                            <div class="data_card_cases_sub">
                                <h4>(Imported)</h4>
                                <p><?php echo $data_tdy[1]; ?></p>
                            </div>
                        </div>
                        <h3 class="data_card_cases_active">Active cases</h3>
                        <p><span class="number_counter"><?php echo $data_tdy[2]; ?></span>
                        <span>
<?php 
                            if((int)$data_tdy[2]-(int)$active_ytd > 0){
                                echo '+';
                            } 
                            echo (int)$data_tdy[2]-(int)$active_ytd; 
?>
                        </span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="row">
                        <div class="col-sm data_card" id="data_card_recovs">
                            <h2>Recovered</h2>
                            <p class="number_counter"><?php echo $data_tdy[3]; ?></p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm data_card" id="data_card_deaths">
                            <h2>Deaths</h2>
                            <p class="number_counter"><?php echo $data_tdy[4]; ?></p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm data_card" id="data_card_vacs">
                            <h2>Vaccinations</h2>
                            <p class="number_counter"><?php echo $data_tdy[5]; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php 
    if($state==''){
?>
        <section class="container middle_section">
            <table id='states_table'>
                <thead>
                    <tr>
                        <th>State</th><th>New Cases</th><th>Local Cases</th><th>Import Cases</th><th>Active Cases</th><th>Recovered Cases</th><th>Deaths</th><th>Vaccinations</th>
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
?>              </tbody>

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

    <section class="container bottom_section">
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
