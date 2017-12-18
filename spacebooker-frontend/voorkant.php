<html>
 <head>
  <title>PHP Test</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

 <link rel="stylesheet" href="code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <link rel="stylesheet" href="resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="js/animatie.js"></script>
  <link rel="stylesheet" type="text/css" href="css/animatie.css">


  <script type = "text/javascript"
      src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js">
   </script>

   <script type = "text/javascript"
      src = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js">
   </script>

   <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">




  <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>


  <link rel="stylesheet" type="text/css" href="style.css">
 </head>
 <body>
  <div class="wrapper">

    <div class="box 1">

      <div class="title-box"><h4>Selecteer hier je voorkeuren</h4></div>


    </div>


    <div class="box a">
      <!-- <h4>Selecteer een datum en tijd</h4> -->
      <h3>Datum</h3>
      <input type="text" id="datepicker" placeholder="Kies een datum">
    </div>

    <div class="box b">
      <h3>Tijd</h3>
      <select>
        <option value="8u">vanaf 8:00</option>
        <option value="9u">vanaf 9:00</option>
        <option value="10u">vanaf 10:00</option>
        <option value="11u">vanaf 11:00</option>
        <option value="12u">vanaf 12:00</option>
        <option value="13u">vanaf 13:00</option>
        <option value="14u">vanaf 14:00</option>
        <option value="15u">vanaf 15:00</option>
        <option value="16u">vanaf 16:00</option>
      </select>
      <select>
        <option value="9u">tot 9:00</option>
        <option value="10u">tot 10:00</option>
        <option value="11u">tot 11:00</option>
        <option value="12u">tot 12:00</option>
        <option value="13u">tot 13:00</option>
        <option value="14u">tot 14:00</option>
        <option value="15u">tot 15:00</option>
        <option value="16u">tot 16:00</option>
        <option value="16u">tot 17:00</option>
      </select>
    </div>

    <div class="box c">
      <h3>Aantal personen</h3>
      <select>
        <option value="" disabled selected>Selecteer aantal personen</option>
        <option value="1p">1</option>
        <option value="2p">2</option>
        <option value="3p">3</option>
        <option value="4p">4</option>
        <option value="5p">5</option>
        <option value="6p">6</option>
        <option value="7p">7</option>
        <option value="8p">8</option>
        <option value="9p">9</option>
        <option value="10p">10</option>
      </select>
    </div>

    <div class="box d">
      <h4>Selecteer een ruimte</h4>

      <div class="ruimte-row"  id="Ruimtes">
        <i class="fa fa-camera-retro fa-lg"></i>



        <p>Ruitenberglaan</p>
        <p><i class="fa fa-map-marker" aria-hidden="true"></i>Lokaal</p>
        <p> Beeldscherm</p>
        <p> Beamer</p>
        <p> Capaciteit</p>
        <p  onclick="toonMeerInfo();">Meer info</a>

      </div>

      <div class="ruimte-row"  id="Ruimtes1">

      </div>

      <div class="ruimte-row"  id="Ruimtes2">

      </div>

      <div class="ruimte-row"  id="Ruimtes3">

      </div>

      <div class="ruimte-row"  id="Ruimtes4">



      </div>

    </div>

    <div class="box a1">
        <h4>Jouw info</h4>
    </div>

    <div class="box a2">
      <form action="">
        <h3>Naam</h3>
        <input type="text" name="naam" placeholder="Je naam"><br>
      </form>
    </div>
    <div class="box b2">
      <form action="">
        <h3>Studentnummer</h3>
        <input type="text" name="studentnummer" placeholder="Studentnummer"><br>
      </form>
    </div>
    <div class="box c2">
      <form action="">
        <h3>Emailadres</h3>
        <input type="text" name="emailadres" placeholder="Emailadres"><br>
      </form>
    </div>
    <div class="box e">
      <h3>Reservering</h3>
      <div class="ruimte-row"></div>
    </div>
    <div class="box f">
      <button type="button">RESERVEER</button>
    </div>
  </div>
 </body>
</html>
