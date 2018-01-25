<?php

include('connection.php');
$con = getdb();

if (isset($_POST["Import"])) {
    echo $filename = $_FILES["file"]["tmp_name"];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sql = "INSERT into registre (id,nom,dataHora,terminal) values ('" . $getData[1] . "','" . $getData[2] . "','" . $getData[3] . "','" . $getData[7] . "')";
            $result = mysqli_query($con, $sql);
            // var_dump(mysqli_error_list($con));
            // exit(); 
            if (!isset($result)) {
                echo "<script type=\"text/javascript\">
							alert(\"Invalid File:Please Upload CSV File.\");
							window.location = \"index.php\"
						  </script>";
            } else {
                echo "<script type=\"text/javascript\">
						alert(\"CSV File has been successfully Imported.\");
						window.location = \"index.php\"
					</script>";
            }
        }
        fclose($file);
    }
}
if (isset($_POST["Export"])) { //In case of Export Data from here(Future Applications)

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $output = fopen("php://output", "w");
    fputcsv($output, array('ID', 'Nom', 'Data Hora', 'Terminal'));
    $query = "SELECT * from registre ORDER BY id DESC";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
}

function get_all_records() {
    $con = getdb();

    $Sql = "SELECT * FROM registre";
    $result = mysqli_query($con, $Sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='table-responsive'><table id='registre' class='table table-striped table-bordered'>
     <thead>
     <tr>
                        <th>ID</th>
			<th>Nom</th>
			<th>Data Hora</th>
			<th>Terminal</th>
                        </tr></thead><tbody>";
        while ($row = mysqli_fetch_assoc($result)) {

            echo "<tr><td>" . $row['id'] . "</td>
                   <td>" . $row['nom'] . "</td>
                   <td>" . $row['dataHora'] . "</td>
                   <td>" . $row['terminal'] . "</td></tr>";
        }
        //  echo "<tr> <td><a href='' class='btn btn-danger' id='status_btn' data-loading-text='Changing Status..'>Export</a></td></tr>";
        echo "</tbody></table></div>";
    } else {
        echo "you have no recent pending orders";
    }
}
?>