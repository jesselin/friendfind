<?php
  
	// session_start();
  include_once("connection.php");
  include_once("process.php");

  if(!isset($_SESSION['logged_in']))
  {
    header("Location: index.php");
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" content="">
    <title>OOP Advanced - Friend Finder</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link href="css/style.css" type="text/css" rel="stylesheet">  
    <script type="text/javascript">
      $(document).ready(function(){


      });  //on document ready, do these jquery things
    </script>
  </head>
  <body>
    <div id="topdiv">
      <div class="wrapper">
      	<div id="logo">
        	<h1>Friend Finder</h1>
        </div>
        <div id="welcome_logout">
        	<h6>Welcome <?php echo $_SESSION['user']['first_name'] . " " . $_SESSION['user']['last_name'] . " - " . $_SESSION['user']['email'] ?> !</h6>
        	<div class="control-group">
            <!-- Button -->
            <div class="controls">
              <form method="POST" action="process.php">
                <input type="submit" value="Logout" class="btn btn-alert">
                <input type="hidden" name="action" value="logout" />
              </form>
            </div> 
          </div>
        </div> <!-- end welcome and logout -->
        <div class="clear"></div>
      </div> <!-- end top wrapper -->
    </div> <!-- end top div -->
    <div class="wrapper">
      <div class="col-6">
      <h2>List of Friends</h2>
        <table class="table">
        <tr>
          <th>Name</th>
          <th>Email</th>
        </tr>
        <?php

          $friendslist = new Process();
          $friends = $friendslist->friendCheckList();
          // echo "<pre>";
          // var_dump($friends);
          // echo "</pre>";


          foreach($friends as $friend)
          {
        ?>
            <tr>
              <td><?= $friend['first_name'] ?> <?= $friend['last_name'] ?></td>
              <td><?= $friend['email'] ?></td>
            </tr>
        <?php
          } 
        ?>  
        </table>
      </div>
      <div class="col-offset-6"></div>
      <div class="col-12">
        <h2>List of Users who subscribed to Friend Finder</h2>
        <table class="table">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Action</th>
        </tr>
        <?php

          $get_users = new Process();
          $users = $get_users->listFriends();
          // echo "<pre>";
          // var_dump($users);
          // echo "</pre>";

          $friend_list = new Process();
          // $wtf = $friend_list->friendCheck($user['id']);
          // echo "<pre>";
          // var_dump($wtf);
          // echo "</pre>";

          foreach($users as $user)
          {
        ?>
            <tr>
              <td><?= $user['first_name'] ?> <?= $user['last_name'] ?></td>
              <td><?= $user['email'] ?></td>
        <?php

              if($friend_list->friendCheck($user['id']))
              {
        ?>     
                <td>Friend :)</td>
        <?php
              }
              else
              {
        ?>    
                <td>
                  <form method="POST" action="process.php">
                    <input type="submit" value="Add Friend" class="btn-mini btn-info">
                    <input type="hidden" name="action" value="add_friend" />
                    <input type="hidden" name="id" value=<?= $_SESSION['user']['id'] ?> />
                    <input type="hidden" name="friend_id" value=<?= $user['id'] ?> />
                  </form>
                </td>
        <?php }
        ?>
            </tr>
    <?php }
    ?>      
        </table>
      </div>
    </div>
  </body>
</html>