<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHAMPBOX</title>
    <link rel="icon" type="image/x-icon" href="./cboxlogo.PNG">
    <link rel="stylesheet" href="./assets/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/simple-line-icons.css">
    <link rel="stylesheet" href="./assets/style.css">
    <style>
        .genre-container {
            overflow-x: auto;
            white-space: nowrap;
            margin-bottom: 30px;
        }

        .genre-header {
            font-size: 24px;
            margin-top: 20px;
            padding-left: 7px;
            text-transform: uppercase;
        }

        .posters-row {
            display: flex;
            overflow-x: auto;
        }

        .poster {
            margin: 10px;
            text-align: center;
        }

        .poster img {
            width: 200px;
            height: auto;
            max-height: 300px;
        }

        .poster-title {
            color: #fff;
            margin-top: 5px;
        }

        .close-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
            font-size: 16px;
            visibility: hidden;
            cursor: pointer;
            z-index: 2;
        }

        .poster:hover .close-icon {
            visibility: visible;
        }

        .arrow-icon {
            position: absolute;
            top: 50%;
            font-size: 24px;
            color: #fff;
            cursor: pointer;
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .poster:hover .arrow-icon {
            opacity: 1;
        }

        .left-arrow {
            left: 10px;
        }

        .right-arrow {
            right: 10px;
        }

        /* CSS styles for the menu */
        .navigation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #205837;
            color: #fff;
            z-index: 100;
        }

        .menu-items {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            justify-content: space-between;
        }

        .menu-items li {
            margin: 0;
        }

        .menu-items li a {
            color: #fff;
            text-decoration: none;
            padding: 15px;
            display: block;
        }

        .menu-items li a:hover {
            background-color: #666;
        }

        .content {
            margin-top: 50px;
            padding: 0 15px;
        }

        .responsive-heading {
            padding: 6px;
            font-size: 24px;
            line-height: 1.2;
        }

        /* Enable touch scrolling for mobile devices */
        @media only screen and (max-width: 767px) {
            .genre-container {
                overflow-x: scroll;
                -webkit-overflow-scrolling: touch;
            }

            .posters-row {
                flex-wrap: nowrap;
            }

            .poster {
                flex: 0 0 auto;
                margin-right: 10px;
                margin-bottom: 10px;
                width: 200px;
                text-align: center;
            }
        }
        </style>
</head>

<body>
<div class="navigation">
    <ul class="menu-items">
        <li><a href="./index.php">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="./upload_form.html">Uploader</a></li>
        <li><a href="./admin.php">Admin</a></li>
    </ul>
</div>

<div id="content" class="content menu-open"></div>
<header class="masthead d-flex">
    <div class="container text-center my-auto">
        <img src="cbox.svg" />
    </div>
</header>

<h3 class="responsive-heading">Movie/TV Series Homepage</h3>

<?php
// Connect to the SQLite database
$databaseFile = __DIR__ . '/movies-series.db';
$db = new SQLite3($databaseFile);

// Query to retrieve movies and series from the database
$query = "SELECT * FROM movies_series ORDER BY genre";
$result = $db->query($query);

// Initialize a variable to store the current genre
$currentGenre = null;

// Loop through the query result
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $genre = $row['genre'];

    // Check if the genre has changed
    if ($currentGenre != $genre) {
        // Close the previous genre container if it exists
        if ($currentGenre !== null) {
            echo '</div></div>';
        }

        // Start a new genre container
        echo '<div class="genre-container">';
        echo '<h2 class="genre-header">' . $genre . '</h2>';
        echo '<div class="posters-row">';
        
        // Update the current genre
        $currentGenre = $genre;
    }

    // Display the movie poster
    echo '<div class="poster">';
    if (isset($row['php_file_location']) && isset($row['poster']) && isset($row['title']) && isset($row['summary']) && isset($row['genre'])) {
        echo '<a href="' . $row['php_file_location'] . '">';
        echo '<img src="' . $row['poster'] . '" alt="' . $row['title'] . '">';
        echo '<span class="close-icon" onclick="confirmDelete(event)">&times;</span>';
        echo '</a>';
        echo '<p class="poster-title">' . $row['title'] . '</p>';
    } else {
        echo '<p>Invalid data for this movie/series.</p>';
    }
    echo '</div>';
}

// Close the last genre container if it exists
if ($currentGenre !== null) {
    echo '</div></div>';
}

// Close the database connection
$db->close();
?>


   <script src="./assets/jquery-3.7.0.js"></script>
<script src="./assets/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Scroll horizontally through movies under each genre
        $('.arrow-icon').click(function () {
            var container = $(this).closest('.genre-container');
            var postersRow = container.find('.posters-row');
            var scrollAmount = postersRow.width() - container.width();
            var currentScrollLeft = postersRow.scrollLeft();

            if ($(this).hasClass('arrow-right')) {
                postersRow.animate({ scrollLeft: currentScrollLeft + 200 }, 400);
            } else {
                postersRow.animate({ scrollLeft: currentScrollLeft - 200 }, 400);
            }
        });

        $('.close-icon').click(confirmDelete);

        function confirmDelete(event) {
            event.preventDefault(); // Prevent the link from being followed immediately

            var deleteConfirmed = confirm("Are you sure you would like to delete this?");
            if (deleteConfirmed) {
                var poster = $(event.target).siblings('img').attr('src');
                var data = {
                    'poster': poster
                };

                // Send an AJAX request to delete the poster and remove the entry from data.csv
                $.ajax({
                    url: 'delete_movie.php',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response === 'success') {
                            // Reload the page to reflect the changes
                            location.reload();
                        } else {
                            alert('Failed to delete the movie/series.');
                        }
                    },
                    error: function () {
                        alert('An error occurred while deleting the movie/series.');
                    }
                });
            }
        }
    });
</script>

</body>

</html>
