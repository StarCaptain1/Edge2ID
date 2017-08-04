<head>
  <title>Edge2ID</title>
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="wrapper">
  <h1>
    Result
  </h1>
  <form action="download.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <textarea name="csv_data">
<?php
ini_set('max_execution_time', 300);

$sourceIDs = $_POST['source_matches'];
$targetIDs = $_POST['target_matches'];

$total_rows = max(count($sourceIDs), count($targetIDs));

$name = tempnam('/tmp', 'csv');
$handle = fopen($name, 'w');

fputcsv($handle, ['SourceID', 'TargetID']);
for ($i = 0; $i < $total_rows; $i++) {
  $source_cell = isset($sourceIDs[$i]) ? $sourceIDs[$i] : 0;
  $target_cell = isset($targetIDs[$i]) ? $targetIDs[$i] : 0;
  fputcsv($handle, [$source_cell, $target_cell]);
}

fclose($handle);
readfile($name);
unlink($name);
?>
    </textarea><br/>
    <input class="submit" id="download" type="submit" name="download" value="Download CSV"/>
  </form>
</div>
</body>
