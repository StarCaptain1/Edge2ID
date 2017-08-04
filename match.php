<?php
ini_set("auto_detect_line_endings", "1");
ini_set('max_execution_time', 300);

define('MAX_LEV_VALUE', 2);
?>
<head>
    <title>Edge2ID</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#match').click(function(e) {
                if ($('.match-item:not(:has(:radio:checked))').length) {
                    alert("Please select a match for all items.");
                    e.preventDefault();
                    return false;
                } else {
                    $('.wrapper').hide().after("<div id='loading'><img src='images/loading.gif'><br/><br/>Processing</div>");
                }
            });
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <p>The form below will not submit until selections have been made in all fields. If none of the suggested
            options matches your individual, select “N/A.” Your new CSV will be populated in the instances of “N/A” with
            the name string from your original list.</p>
        <form action="result.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
      <?php
      if (isset($_POST["upload"])) {
        if ($_FILES["csv_master"]['error'] == 0 && $_FILES["csv_servant"]['error'] == 0) {
          $master_array = $sources = $targets = $source_matches = $target_matches = [];

          // Parse the master csv.
          $csv_master = fopen($_FILES['csv_master']['tmp_name'], "r");
          // Skip the header.
          fgetcsv($csv_master, 1000, ",");
          while (($row = fgetcsv($csv_master, 1000, ",")) !== FALSE) {
            if ($row[0] && $row[1]) {
              $master_array[$row[1]] = $row[0];
            }
          }

          // Parse the servant csv.
          $csv_servant = fopen($_FILES['csv_servant']['tmp_name'], "r");
          // Skip the header.
          fgetcsv($csv_servant, 1000, ",");
          while (($row = fgetcsv($csv_servant, 1000, ",")) !== FALSE) {
            $sources[] = $row[0];
            $targets[] = $row[1];
          }

          // Find matches.
          foreach ($sources as $source_index => $source_name) {
            foreach ($master_array as $master_id => $original_master_name) {
              // Sanitize each name.
              $source_name = str_replace(' ', '', strtolower($source_name));
              $master_name = str_replace(' ', '', strtolower($original_master_name));

              // Compare the names.
              $lev_value = levenshtein($source_name, $master_name);

              if ($lev_value <= MAX_LEV_VALUE) {
                $source_matches[$source_index][$master_id] = $original_master_name;
              }
            }

            asort($source_matches[$source_index]);
          }

          foreach ($targets as $target_index => $target_name) {
            foreach ($master_array as $master_id => $original_master_name) {
              // Sanitize each name.
              $target_name = str_replace(' ', '', strtolower($target_name));
              $master_name = str_replace(' ', '', strtolower($original_master_name));

              // Compare the names.
              $lev_value = levenshtein($target_name, $master_name);

              if ($lev_value <= MAX_LEV_VALUE) {
                $target_matches[$target_index][$master_id] = $original_master_name;
              }
            }

            asort($target_matches[$target_index]);
          }

          // Display matches to user.
          foreach (['source', 'target'] as $column) {
            foreach (${$column . '_matches'} as $index => $matches) { ?>
                <div class="match-item">
                    <strong>Name:</strong> <?php print ${$column . 's'}[$index]; ?><br/><br/>
                    <strong>Possible Matches:</strong><br/>
                  <?php if (!empty($matches)) {
                        foreach ($matches as $master_id => $master_name) { ?>
                    <input type="radio"
                           name="<?php print $column . "_matches[" . $index . "]"; ?>"
                           id="<?php print $column . "_matches[" . $index . "][" . $master_id . "]"; ?>"
                           value="<?php print $master_id; ?>">
                    <label for="<?php print $column . "_matches[" . $index . "][" . $master_id . "]"; ?>">
                        <?php
                        $link = 'http://sixdegreesoffrancisbacon.com/people/' . $master_id;
                        print $master_array[$master_id] . ' (' . $master_id . ')' . ' <a href="' . $link . '" target="_new">' . $link . '</a>'; ?>
                    </label>
                    <br/>
                  <?php }} ?>
                    <input type="radio"
                           name="<?php print $column . "_matches[" . $index . "]"; ?>"
                           id="<?php print $column . "_matches[" . $index . "][0]"; ?>"
                           value="<?php print ${$column . 's'}[$index]; ?>">
                    <label for="<?php print $column . "_matches[" . $index . "][0]"; ?>">
                        N/A
                    </label>
                    <br/>
                </div>
              <?php
            }
          }
        }
        else {
            echo print_r($_FILES);
        }
      }
      ?>
            <input id="match" class="submit" type="submit" name="submit" value="Submit Matches"/>
        </form>
    </div>
</body>
