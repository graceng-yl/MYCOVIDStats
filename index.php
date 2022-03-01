

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
                    echo 'MY COVID Stats';
                } else{ 
                    echo 'MY <b>'.$state.'</b> COVID Stats'; 
                }
?>
            </p>
            <h1 class="centered_divs" id="date_div">
                <?php echo date("d F Y", strtotime("-1 days")); ?>
            </h1>
            <p class="centered_divs" id="refresh_div">
                Last refreshed: <?php echo date("d F Y, g:i a"); ?> (UTC+8)
            </p>
        </div>

        <div class="container top_section_bottom">
            <div class="row">
                <div class="col-sm data_card" id="data_card_cases">
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
                        <h3 class="data_card_cases_active">Active cases</h3>
                        <p><span class="number_counter"><?php echo $data_tdy[2]; ?></span>
                        <span id="active_diff">
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
        <p id="update_div">*Data updated daily by the next day</p>
    </section>

<?php 
    if($state==''){
?>
        <section class="container middle_section">
            <div class="middle_section_top">
                <h1>Latest Stats By States</h1>
                <p>Summary of latest data by states</p>
            </div>
            <div class="middle_section_bottom">
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
                            <td><a href="<?php 
                            if (strpos($_SERVER['REQUEST_URI'], '?state_select=') == '')
                                echo $_SERVER['REQUEST_URI'].'?state_select='.str_replace(" ","+",$data_tdy_state[0]);
                            else 
                                echo $_SERVER['REQUEST_URI'].str_replace(" ","+",$data_tdy_state[0]); ?>">
                                <?php echo $data_tdy_state[0]; ?>
                                </a></td>
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
            </div>
        </section>
<?php
    }
?>  

    <section class="container bottom_section">
        <div class="bottom_section_top">
            <h1>Stats By Date</h1>
            <p>Data for <?php if ($state=='') echo 'Malaysia'; else echo $state; ?> over time</p>
        </div>
        <div class="bottom_section_bottom">
            <p>Select date range to view: </p>
            <form id="filter_form" onsubmit="return false;">
                <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id='start_date' class="input_date" placeholder="Start date">
                <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id='end_date' class="input_date" placeholder="End date">
                <input type="submit" value="Filter" id='submit_filter' class="input_submit">
            </form>
            <span id='filter_message'>Please select a valid range!</span>

            <p id="trend_graph_info">Show / hide data by clicking on the legends.</p>
            <div id="trend_graph"></div>
        </div>
    </section>
    

<?php
    include('pages/footer.php'); 
?>
