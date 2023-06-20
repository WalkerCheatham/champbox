<!DOCTYPE html>
<html>
<head>
  <title>Movie Series Database Viewer</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    h1,
    h2 {
      text-align: center;
    }
  </style>
</head>
<body>
  <h1>Movie Series Database Viewer</h1>

  <h2>Movies Series</h2>
  <table id="movies-table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Summary</th>
        <th>Poster</th>
        <th>Background Image</th>
        <th>Type</th>
        <th>Genre</th>
        <th>PHP File Location</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="movies-body"></tbody>
  </table>

  <h2>Episodes</h2>
  <table id="episodes-table">
    <thead>
      <tr>
        <th>Series ID</th>
        <th>Season Number</th>
        <th>Episode Number</th>
        <th>Episode Name</th>
        <th>Video Source</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="episodes-body"></tbody>
  </table>

  <h2>Subtitles</h2>
  <table id="subtitles-table">
    <thead>
      <tr>
        <th>Series ID</th>
        <th>Season Number</th>
        <th>Episode Number</th>
        <th>Subtitle Source</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="subtitles-body"></tbody>
  </table>

  <script>
    // Fetch movies data from the SQLite database using PHP
    fetch('getdata.php?table=movies_series')
      .then((response) => response.json())
      .then((data) => {
        const moviesBody = document.getElementById('movies-body');
        let html = '';

        // Iterate over the data and generate table rows
        data.forEach((row) => {
          html += `<tr>
                <td>${row.title}</td>
                <td>${row.summary}</td>
                <td>${row.poster}</td>
                <td>${row.background_img}</td>
                <td>${row.type}</td>
                <td>${row.genre}</td>
                <td>${row.php_file_location}</td>
                <td><button class="delete-btn" data-id="${row.series_id}">Delete</button></td>
              </tr>`;
        });

        moviesBody.innerHTML = html;

        // Attach event listeners to delete buttons
        const deleteButtons = document.getElementsByClassName('delete-btn');
        Array.from(deleteButtons).forEach((button) => {
          button.addEventListener('click', (event) => {
            const seriesId = event.target.getAttribute('data-id');
            deleteData('movies_series', seriesId);
          });
        });
      })
      .catch((error) => {
        console.error('Error:', error);
      });

    // Fetch episodes data from the SQLite database using PHP
    fetch('getdata.php?table=episodes')
      .then((response) => response.json())
      .then((data) => {
        const episodesBody = document.getElementById('episodes-body');
        let html = '';

        // Iterate over the data and generate table rows
        data.forEach((row) => {
          html += `<tr>
                <td>${row.series_id}</td>
                <td>${row.season_number}</td>
                <td>${row.episode_number}</td>
                <td>${row.episode_name}</td>
                <td>${row.video_source}</td>
                <td><button class="delete-btn" data-id="${row.episode_id}">Delete</button></td>
              </tr>`;
        });

        episodesBody.innerHTML = html;

        // Attach event listeners to delete buttons
        const deleteButtons = document.getElementsByClassName('delete-btn');
        Array.from(deleteButtons).forEach((button) => {
          button.addEventListener('click', (event) => {
            const episodeId = event.target.getAttribute('data-id');
            deleteData('episodes', episodeId);
          });
        });
      })
      .catch((error) => {
        console.error('Error:', error);
      });

    // Fetch subtitles data from the SQLite database using PHP
    fetch('getdata.php?table=subtitles')
      .then((response) => response.json())
      .then((data) => {
        const subtitlesBody = document.getElementById('subtitles-body');
        let html = '';

        // Iterate over the data and generate table rows
        data.forEach((row) => {
          html += `<tr>
                <td>${row.series_id}</td>
                <td>${row.season_number}</td>
                <td>${row.episode_number}</td>
                <td>${row.subtitle_source}</td>
                <td><button class="delete-btn" data-id="${row.subtitle_id}">Delete</button></td>
              </tr>`;
        });

        subtitlesBody.innerHTML = html;

        // Attach event listeners to delete buttons
        const deleteButtons = document.getElementsByClassName('delete-btn');
        Array.from(deleteButtons).forEach((button) => {
          button.addEventListener('click', (event) => {
            const subtitleId = event.target.getAttribute('data-id');
            deleteData('subtitles', subtitleId);
          });
        });
      })
      .catch((error) => {
        console.error('Error:', error);
      });

    // Function to delete data from the specified table
    function deleteData(table, id) {
      if (confirm('Are you sure you want to delete this data?')) {
        fetch(`delete.php?table=${table}&id=${id}`, { method: 'POST' })
          .then((response) => response.text())
          .then((result) => {
            console.log('Data deleted:', result);
            // Reload the page to reflect the changes
            location.reload();
          })
          .catch((error) => {
            console.error('Error:', error);
          });
      }
    }
  </script>
</body>
</html>
