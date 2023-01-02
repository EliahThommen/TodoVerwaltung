<!-- Header (navbar) -->

<header class="header">
    <nav class="navbar navbar-default">
        <div class="container-fluid">

            <a class="navbar-brand" href="index.php">
                <img src="images/wgbs.png" alt="logo" class="navbar-brand-logo">
            </a>

            <div class="logout">
                <a class="btn btn-warning navbar-btn navbar-right" href="includes/logout.inc.php">Logout</a>
                <p class="navbar-text navbar-right">Eingelogt als <?php echo $_SESSION['username'] ?> </p>
            </div>
            <?php
            if ($_SESSION['user_ID'] == 1) {
                echo "  <a href='create_category.php' class='btn btn-default navbar-btn navbar-right signup-btn'>Neue Kategorie erstellen</a>";
                echo "  <a href='signup.php' class='btn btn-info navbar-btn navbar-right signup-btn'>Neuer Benutzer hinzufügen</a>";
            } else if (basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']) === 'index.php') {
                $user_ID = $_SESSION['user_ID'];
                echo "  <a href='create_todo.php?user_ID=$user_ID' class='btn btn-info navbar-btn navbar-right create-todo-btn'>Neues ToDo hinzufügen</a>";
                echo "  <a href='archiv.php' class='btn btn-default navbar-btn navbar-right archiv'>Archiv</a>";
                echo "  <form class='navbar-form navbar-left'  role='search'>";
                echo "      <div class='form-group'>";
                echo "          <input type='text' class='form-control' name='search_field' placeholder='Suche nach Namen...'>";
                echo "      </div>";
                echo "      <button type='submit' action='index.php' method='get' class='btn btn-default'>Submit</button>";
                echo "  </form>";
            } ?>
        </div>
    </nav>
</header>