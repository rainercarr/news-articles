<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Create Article</title>
  </head>
  <body class="bg-light">
    <nav class="navbar navbar-dark navbar-expand-sm bg-info"> <!-- bg-light -->
        <h2>News Articles</h2>
        <ul class="navbar-nav">
            <li class="nav-item"><a href="./view-articles.php" class="nav-link">View Articles</a></li>
            <li class="nav-item"><a href="./index.html" class="nav-link">Create Article</a></li>
        </ul>

    </nav>
    <?php

    //Bind multiple parameters in a prepared query safe from SQL injection
    function prepared_query($mysqli, $sql, $params, $types = "")
    {
        $types = $types ?: str_repeat("s", count($params));
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt;
    }


    $host="your MySQL host name";
    $port=3306;
    $socket="";
    $user="your MySQL username";
    $password="your MySQL password";
    $dbname="your MySQL database name";

    $con = new \mysqli($host, $user, $password, $dbname);
    
    if ($con->connect_error) {
        die ('Could not connect to the database server' . $con->connect_error);
    }

    //delete this article, if desired
    $del = NULL;
    
    try {
        $del = (int)$_REQUEST['del'];
        if ($del != 0) {
            $sql_del = "DELETE FROM article WHERE aid = ?";
            $stmt_del = $con->prepare($sql_del);
            $stmt_del->bind_param('s', $del);
            $stmt_del->execute();
        }
    } catch (Exception $e) {
        echo "$e";
    }

    //get existing articles to print
    $sql = "SELECT aid, atitle, adate, abody FROM article";

    $stmt = $con->prepare($sql);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all();

    //iterate over data and print accordingly
    for ($i = 0; $i < count($data); $i++) {
        $aid = $data[$i][0];
        $title = $data[$i][1];
        $date = $data[$i][2];
        $body = $data[$i][3];
        echo "
            <div class='container border m-5 p-5 shadow'>
                <span class='row'>
                    <h2>$title</h2>
                    <form action='view-articles.php'>
                        <input type='hidden' name='del' value=$aid/>
                        <input type='submit' class='btn btn-secondary ml-4' value='Remove'/>
                    </form>
                </span>
                <h5>$date</h5>
                <p>$body</p>
            </div>
        ";
    }
    ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>