<?php

$conn = new mysqli('localhost', 'root', '', 'learning_php');
if (isset($_POST['countryName'])){
    if ($_POST['action']=='insert') {
        $countryName = $conn->real_escape_string($_POST['countryName']);
        $shortDesc = $conn->real_escape_string($_POST['shortDesc']);
        $longDesc = $conn->real_escape_string($_POST['longDesc']);

        $sql = "SELECT * FROM `country` WHERE country_name='$countryName'";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            exit('Record with this country name is already exists');
        } else {
            $sql1 = "INSERT INTO `country`(`country_name`, `short_desc`, `long_desc`) VALUES ('$countryName', '$shortDesc', '$longDesc')";
            if ($result1 = $conn->query($sql1)) {
                exit("Your record is inserted");
            }
        }
    }
}


if (isset($_POST['getdata'])) {
    $start = $conn->real_escape_string($_POST['start']);
    $limit = $conn->real_escape_string($_POST['limit']);
    $sql2 = "SELECT id, country_name FROM `country` LIMIT $start, $limit";
    $result2 = $conn->query($sql2);

    if ($result2->num_rows > 0) {
        $response = "";
        while ($row = $result2->fetch_array()) {
            $response .= '
            <tr>
            <td>' . $row['id'] . '</td>
            <td id="' . $row['id'] . '">' . $row['country_name'] . '</td>
            <td>
            <input type="button" onclick="viewCountry(' . $row['id'] . ')" value="View" class="btn btn-primary">&nbsp;
            <input type="button" onclick="editCountry(' . $row['id'] . ')" value="Edit" class="btn btn-warning">&nbsp;
            <input type="button" onclick="deleteCountry(' . $row['id'] . ')" value="Delete" class="btn btn-danger">
</td>
</tr>
          ';
        }
        exit($response);
    } else {
        exit('reachedMax');
    }
}


if (isset($_POST['countryid'])) {
    $cid = $_POST['countryid'];

    $sql3 = "SELECT * FROM `country` WHERE id='$cid'";
    $result3 = $conn->query($sql3);
    if ($result3->num_rows > 0) {
        $row = $result3->fetch_assoc();
        $jsonarray = array(
            'countryName' => $row['country_name'],
            'shortDesc'   => $row['short_desc'],
            'longDesc'    => $row['long_desc'],
            'countryId'   => $row['id']

        );
    }
    exit(json_encode($jsonarray));
}

if (isset($_POST['countryName'])) {
    if ($_POST['action'] == 'update') {
        $countryName = $conn->real_escape_string($_POST['countryName']);
        $shortDesc = $conn->real_escape_string($_POST['shortDesc']);
        $longDesc = $conn->real_escape_string($_POST['longDesc']);
        $cid = $conn->real_escape_string($_POST['countryId']);

        $sql4="UPDATE `country` SET `country_name`='$countryName',`short_desc`='$shortDesc',`long_desc`='$longDesc' WHERE `id`='$cid'";

        $result=$conn->query($sql4);
        if ($result){
            exit("Your record is updated");
        }
    }
}

if (isset($_POST['deleteid'])) {
    $cid = $_POST['deleteid'];

    $sql3 = "DELETE FROM `country` WHERE id='$cid'";
    $result3 = $conn->query($sql3);
    if ($result3) {
        exit('record  has been deleted');
    }
    ;
}


