<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/src/userload.php';
?>

<section class='nvbax'>
  <iter>
    <img src='/public/images/logo.png' alt='logo' style='height:30px;width:auto;'>
  </iter>
  <iter>
    Signed in as <a href='/user/<?php echo $current_user['username']; ?>'><?php echo $current_user['username']; ?></a>
  </iter>
</section>
<section style="position: absolute;top:50px;left:0px;right:0px;bottom:0px;display:flex;justify-content: center;">
  <div class='container'>
    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name">

    <ul id="myUL">

    <?php 
      $services = $jp->get('services');
      foreach($services as $service){
        $key = $service['id'];
        $ex = $jp->get_row('accepted', ['service'=>$key]);
        $user = $jp->get_row('users', ['userId' => $service['by']])[0];
        $keywords = $jp->get_row('tags', ["id"=> $key]);
        $mstring = "";
        foreach($keywords as $word){
          if($mstring == ""){
            $mstring = $word['tag'];
          }else{
            $mstring .= " " . $word['tag'];
          }
        }
        if(count($ex) < 1){
          echo "
          <li>
          <div class='card'>
            <label style='font-size:32px;'><a href='/view/{$service['id']}'>{$service['title']}</a></label><br>
            <label><a href='/user/{$user['username']}'>@{$user['username']}</a></label><br>
            <label style='font-size: 12px;color:gray;'><mabel>{$mstring}</mabel></label>
          </div>
          </li>
          ";
        }
      }
    ?>
  </div>
</ul>

</section>
<script>
  function myFunction() {
      var input, filter, ul, li, a, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      ul = document.getElementById("myUL");
      li = ul.getElementsByTagName("li");
      for (i = 0; i < li.length; i++) {
          a = li[i].getElementsByTagName("mabel")[0];
          txtValue = a.textContent || a.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
              li[i].style.display = "";
          } else {
              li[i].style.display = "none";
          }
      }
  }
  </script>
  