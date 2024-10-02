<?php

// Fonction pour filtrer les paramètres de requête
function filterRequest($request) {
    // Vérifie si la clé existe dans le tableau $_POST
    
    if (isset($_POST[$request])) {
        // Applique strip_tags et htmlspecialchars pour filtrer la demande
        return isset($_REQUEST[$request]) ? htmlspecialchars(trim($_REQUEST[$request])) : null;
    } else {
        // Retourne une valeur par défaut ou une chaîne vide si la clé n'existe pas
        return '';
    }
}

function getAllData($table, $where = null, $values = null){
    global $con; // Assuming $con is your PDO connection object
    $data = array();
    $sql = "SELECT * FROM $table";
    $params = array();

    if ($where !== null) {
        $sql .= " WHERE $where";
        $params = $values;
    }

    $stmt = $con->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $count = $stmt->rowCount();

    $response = array();
    if($count > 0){
        $response["status"] = "success";
        $response["data"] = $data;
    } else {
        $response["status"] = "failure";
    }

    echo json_encode($response);
    return $count;
}


// Assuming $con is your PDO connection object

function getUserGatewayData($user_id, $con) {
    // SQL query to fetch data
    $sql = "SELECT g.module_gtw_id, g.place, g.nb_henhouse, gd.total_weight, g.gwt_id, g.date
            FROM gateway g
            INNER JOIN gateway_data gd ON g.module_gtw_id = gd.module_gtw_id
            WHERE g.user_id = :user_id";

    // Prepare the statement
    $stmt = $con->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Check if there are any results
    if ($stmt->rowCount() > 0) {
        // Fetch data and return as an associative array
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        // If no results found
        echo json_encode(array("status" => "fail"));
    }
}

// Example usage assuming $con is your PDO connection object
// $user_id = 'your_user_id_here';
// getUserGatewayData($user_id, $con);


function ModuleCardData($user_id, $place, $con) {
    
    // $sql = "SELECT md.type, md.weight, md.date, m.module_gtw_id, m.module_status
    //         FROM module_Data md
    //         INNER JOIN module m ON md.module_id = m.module_id
    //         INNER JOIN gateway g ON m.module_gtw_id = g.module_gtw_id
    //         WHERE m.user_id = :user_id AND g.place = :place";

    // $sql = "SELECT md.type, md.weight, md.date,m.henhouse_id, m.module_gtw_id, m.module_status,
    //         md.health, md.humidity, md.temperature, md.pressure, md.stability,
    //         gd.ext_humidity, gd.ext_temperature, gd.ext_pressure, gd.total_weight
    //         FROM module_Data md
    //         INNER JOIN module m ON md.module_id = m.module_id
    //         INNER JOIN gateway g ON m.module_gtw_id = g.module_gtw_id
    //         INNER JOIN gateway_data gd ON g.module_gtw_id = gd.module_gtw_id
    //         WHERE m.user_id = :user_id AND g.place = :place";

    $sql = "SELECT md.type, md.weight, md.date, m.henhouse_id, m.module_gtw_id, m.module_status,
           md.health, md.humidity, md.temperature, md.pressure, md.stability,
           gd.ext_humidity, gd.ext_temperature, gd.ext_pressure, gd.total_weight
    FROM module_Data md
    INNER JOIN module m ON md.module_id = m.module_id
    INNER JOIN gateway g ON m.module_gtw_id = g.module_gtw_id
    INNER JOIN gateway_data gd ON g.module_gtw_id = gd.module_gtw_id
    INNER JOIN (
        SELECT module_id, MAX(date) AS max_date
        FROM module_Data
        GROUP BY module_id
    ) md_max ON md.module_id = md_max.module_id AND md.date = md_max.max_date
    WHERE m.user_id = :user_id AND g.place = :place
";

    // Prepare statement
    $stmt = $con->prepare($sql);

    // if ($stmt === false) {
    //     die('MySQL prepare error: ' . $conn->error);
    // }

    // Bind parameters
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindParam(':place', $place, PDO::PARAM_STR);


    // Execute statement
    $stmt->execute();

    // Check if there are any results
    if ($stmt->rowCount() > 0) {
        ///////////////////////////////////
        $sql1 = "SELECT SUM(md.weight) AS total_weight
                 FROM module_Data md
                 INNER JOIN module m ON md.module_id = m.module_id
                 INNER JOIN gateway g ON m.module_gtw_id = g.module_gtw_id
                 WHERE m.user_id = :user_id AND g.place = :place";
    
        // Prepare statement
        $stmt1 = $con->prepare($sql1);
    
        // Bind parameters
        $stmt1->bindParam(':user_id', $user_id, PDO::PARAM_STR);
        $stmt1->bindParam(':place', $place, PDO::PARAM_STR);
    
        // Execute statement
        $stmt1->execute();
        $data1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    
        ///////////////////////////////////
        
        $total_weight = $data1['total_weight'];
    
        // Prepare the UPDATE statement
        $updateQuery = "UPDATE gateway_data gd
                    INNER JOIN gateway g ON gd.module_gtw_id = g.module_gtw_id
                    SET gd.total_weight = :total_weight
                    WHERE g.place = :place";
        $updateStmt = $con->prepare($updateQuery);
    
        // Bind parameters
        $updateStmt->bindParam(':total_weight', $total_weight, PDO::PARAM_STR);
        $updateStmt->bindParam(':place', $place, PDO::PARAM_STR);
    
        // Execute statement
        $updateStmt->execute();
        //$updateStmt->execute(array($newpass, $username));

        ///////////////////
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        // If no results found
        echo json_encode(array("status" => "fail"));
    }
}

?>
