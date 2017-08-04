<?php
header('Content-Type: application/csv');
header('Content-Disposition:attachment;filename=EdgeID.csv');

echo $_POST['csv_data'];
