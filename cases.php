<?php 
include('header.php'); 
$cases_array = array(); 
foreach ($cases_list as $row){
    if ($row != ''){
        $temp = explode(',', $row);
        array_push($cases_array, [$temp[0], $temp[1]]);
    }
    
}
#print_r($cases_array);
?>
<script>var anarray=<?php echo json_encode($cases_array); ?>;</script>
<div id="dailycases"></div>

<?php include('footer.php'); ?>