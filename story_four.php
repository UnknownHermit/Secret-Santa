<html>
    <head>
        <script src="includes/javascript/jQuery.js"></script>
        <style>
            ul.menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            }

            li.menuItem {
            float: left;
            }

            li.menuItem a {
            display: block;
            text-align: center;
            padding: 16px;
            text-decoration: none;
            }

        </style>
    </head>
    <body>
    <div>
        <ul class="menu">
            <li class="menuItem"><a href="/story_one.php">Story 1</a></li>
            <li class="menuItem"><a href="/story_two.php">Story 2</a></li>
            <li class="menuItem"><a href="/story_three.php">Story 3</a></li>
            <li class="menuItem"><a href="/story_four.php">Story 4</a></li>
        </ul>
    </div>
    <div>
        <h1>Secret Santa</h1>
        <h2>Story four</h2>
        Insert
        <form method="POST" action="api/santaApi.php" target="_blank">
            <label for="santaForeame">Forename</label> <input type="text" id="santaForename" name="forename"/><br/>
            <label for="santaSurname">Surname</label> <input type="text" id="santaSurname" name="surname"/><br/>
            <label for="santaEmail">Email Address</label> <input type="email" id="santaEmail" name="email"/><br/>
            <input type="hidden" name="action" value="create"/>
            <input type="submit" value="Create User"/>
        </form><br/><br/>

        Select
        <form method="POST" action="api/santaApi.php" target="_blank">
            <label for="santaID">ID</label> <input type="text" id="santaID" name="ID"/><br/>
            <input type="hidden" name="action" value="read"/>
            <input type="submit" value="Fetch User"/>
        </form><br/><br/>

        Update
        <form method="POST" action="api/santaApi.php" target="_blank">
            <label for="santaID">ID</label> <input type="text" id="santaID" name="ID"/><br/>
            <label for="santaForeame">Forename</label> <input type="text" id="santaForename" name="forename"/><br/>
            <label for="santaSurname">Surname</label> <input type="text" id="santaSurname" name="surname"/><br/>
            <label for="santaEmail">Email Address</label> <input type="email" id="santaEmail" name="email"/><br/>
            <input type="hidden" name="action" value="update"/>
            <input type="submit" value="Update User"/>
        </form><br/><br/>

        Delete
        <form method="POST" action="api/santaApi.php" target="_blank">
            <label for="santaID">ID</label> <input type="text" id="santaID" name="ID"/><br/>
            <input type="hidden" name="action" value="delete"/>
            <input type="submit" value="Delete User"/>
        </form><br/><br/>

        Get santas
        <form method="POST" action="api/santaApi.php" target="_blank">
        <input type="hidden" name="action" value="getSantas"/>
        <input type="submit" value="Get Santas"/>
        </form><br/><br/>
    </div>
    </body>
</html>