<head>
    <title>Edge2ID</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#upload').click(function(e) {
                var postData = true;
                if (document.getElementById("csv_master").files.length == 0) {
                    $('#csv_master').parent('.form-element').css('border-color', 'red');
                    postData = false;
                } else {
                    $('#csv_master').parent('.form-element').removeAttr('style');
                }

                if (document.getElementById("csv_servant").files.length == 0) {
                    $('#csv_servant').parent('.form-element').css('border-color', 'red');
                    postData = false;
                } else {
                    $('#csv_servant').parent('.form-element').removeAttr('style');
                }

                if (postData) {
                    $('.wrapper').hide().after("<div id='loading'><img src='images/loading.gif'><br/><br/>Processing</div>");
                }
                else {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <h1>
            Edge2ID
        </h1>
        <p>Welcome to Edge2ID, by <a href="http://piercew.net">Pierce Williams</a> at Carnegie Mellon University. This is a data transformation tool for network analysts who need to convert alphanumeric strings
            in an edge list to the unique identifiers associated with those strings in a separate master list. The form
            takes two documents, a master ID library and an edge list, both as CSVs.</p>

        <p>Upload each CSV to its place below and click "submit." You will be returned a selection window populated
            with the closest matches that Edge2ID has found between the names in your edge list and the names in your
            master ID library.</p>

        <p>Select among the options provided for each entry. Once you've made your selections and submitted, you will
            be forwarded to a page displaying your new CSV for review. You can cut and paste this information from the
            display field, or you can select to download your new CSV file.</p>

        <p>For contributors to the <a href="http://www.sixdegreesoffrancisbacon.com/">Six Degrees of Francis Bacon</a> project, you can download your SDFB master ID library
            <a href="samples/SDFB_IDs.csv">here</a>. In your case, after you have submitted your CSVs, the selection page will include links to
            biographical information pages on the SDFB site next to each potential option. You can use this information
            to be sure you select the correct individual, including the correct “Jane Smith,” for example, when there
            are many possible Jane Smiths.</p>
        <form id="csv-form" action="match.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="form-element">
                <strong>ID Library CSV:</strong><br/>
                <i>
                    Formatting: Two columns with formatted headers: Name,ID (<a href="samples/CSV1.csv">Download Sample</a>)
                </i><br/><br/>
                <input type="file" id="csv_master" name="csv_master"><br/>
            </div>
            <div class="form-element">
                <strong>Edge List CSV:</strong><br/>
                <i>
                    Formatting: Two columns with formatted headers: SourceName,TargetName (<a href="samples/CSV2.csv">Download Sample</a>)
                </i><br/><br/>
                <input type="file" id="csv_servant" name="csv_servant"><br/>
            </div>
            <input class="submit" id="upload" type="submit" name="upload" value="Submit"/>
        </form>
    </div>
</body>
