<!DOCTYPE html>
<html>
  <head>
    <title>
      A venue reservation system
    </title>
    <link href="3-theme.css" rel="stylesheet">
    </script>
  </head>
  <body>
    <?php
    // (A) PROCESS RESERVATION
    if (isset($_POST['date'])) {
      require "2-reserve.php";
      if ($_RSV->save(
        $_POST['date'], $_POST['appt'], $_POST['name'],
        $_POST['email'], $_POST['tel'], $_POST['notes'])) {
        echo "<div class='ok'>Reservation confirmed.</div>";
      } else { echo "<div class='err'>".$_RSV->error."</div>"; }
    }
    ?>

    <!-- (B) RESERVATION FORM -->
    <h1>MAKE YOUR RESERVATION HERE</h1>

      <form id="resForm" method="post" target="_self">
        <label for="res_name">Name</label>
        <input type="text" required name="name" value="John Doe"/>
        <label for="res_email">Email</label>
        <input type="email" required name="email" value="john@doe.com"/>
        <label for="res_tel">Telephone Number</label>
        <input type="text" required name="tel" value="123456789"/>
        <label for="res_notes">Notes (if any)</label>
        <input type="text" name="notes" value="Testing"/>
        <label>Reservation Date</label>
        <input type="date" required id="res_date" name="date" value="<?=date("Y-m-d")?>">
        <label for="appt">Reservation Slot  8am until 11pm</label>
        <input type="time" id="appt" name="appt">

        <input type="submit" value="Submit"/>
      </form>


  </body>
</html>
