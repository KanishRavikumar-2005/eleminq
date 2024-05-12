<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/src/userload.php';
$thisUser = false;
$service = $jp->get_row('services', ['id'=> $idm]);
$service = $service[0];
$user = $jp->get_row('users', ["userId" => $service['by']]);
$cname = $user[0]['username'];
if($cname == $current_user['username']){
    $thisUser = true;
}else{
    $otheruser = $jp->get_row("users", ["username" => $cname]);
    $otheruser = $otheruser[0];
}
?>
<section class='nvbax'>
    <iter>
        <a href='/'><img src='/public/images/logo.png' alt='logo' style='height:30px;width:auto;'></a>
    </iter>
    <iter>
      <a href='/'>Home</a>
    </iter>
  </section>
  <style>
      
      textarea{
          background-color: transparent; 
          border:none;
          outline: none;
          resize: vertical;
          width:100%;
          resize: none;
          height: 200px;
      }
      textarea:focus{
          width: 98%;
          padding: 10px;
          margin: 5px;
          display: flex;
      align-items: center;
      border-style: solid;
      /* border-width: 1px; */
      border-radius: 2px;
      border-color: #555555;
      outline: none;
          
      }
      .tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
  }
  
  /* Style the buttons inside the tab */
  .tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
  }
  
  /* Change background color of buttons on hover */
  .tab button:hover {
    background-color: #ddd;
  }
  
  /* Create an active/current tablink class */
  .tab button.active {
    background-color: #ccc;
  }
  
  /* Style the tab content */
  .tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
  }
  .ssmv{
      box-sizing: border-box;
  }
  
  </style>
<section style="position: absolute;top:50px;left:0px;right:0px;bottom:0px;padding:10px;display:flex;justify-content: center;">
    <div class="container">
        <h2><?php echo $service['title'];?></h2>
        <label>Requested by <a href='/user/<?php echo $cname;?>'>@<?php echo $cname;?></a></label>
        <div class='descdesp'>
            <p><?php echo $service['desc'];?></p>
        </div>
        <br>
        <?php 
        if($thisUser):
        $accepted = $jp->get_row('accepted', ["service" => $service['id']]);
        $rated = $jp->get_row('ratings', ["serviceId" => $service['id']]);
        ?>
        <div class="tab">
            <?php 
            if(count($rated) < 1):    
            ?>
            <?php if(count($accepted) < 1): ?>
            <button class="tablinks" onclick="openTab(event, 'viewbids')">View Bids</button>
            <?php else: ?>
            <button class="tablinks" onclick="openTab(event, 'viewdets')">View Details</button>
            <button class="tablinks" onclick="openTab(event, 'done')">Completed</button>
            <?php endif;endif; ?>
          </div>
          <?php if(count($accepted) < 1): ?>
          <div id="viewbids" class="tabcontent">
            <?php 
                $bids = $jp->get_row('bids', ["service" => $service['id']]);
                if(count($bids) < 1){
                    echo "<center>No Bids Yet</center>";
                }
                foreach($bids as $bid){
                    echo "
                    <div class='card'>
                    <label style='font-size: 20px;'>₹{$bid['price']}</label><br>
                    <label>{$bid['message']}</label><br>
                    <label>By <a href='/user/{$bid['name']}'>@{$bid['name']}</a></label><br>
                    <form method='post'>
                    <button name='accept' class='actp' value='{$bid['id']}'><i class='uil uil-check'></i>Accept</button>    
                    </form>
                    </div>
                    ";
                }
            ?>
          </div>
          <?php else: ?>
          <div id="viewdets" class="tabcontent">
            <?php 
                $bidDet = $jp->get_row('bids', ["service" => $service['id'], "id"=>$accepted[0]['accid']])[0]; 
                
                ?>
                <h2><?php echo $bidDet['price'];?></h2>
                <label><?php echo $bidDet['message'];?></label><br>
                <label>By <a href='/user/<?php echo $bidDet['name'];?>'>@<?php echo $bidDet['name'];?></a></label><br>
                <label>Contact <a href='mailto:<?php echo $bidDet['mail'];?>'><?php echo $bidDet['mail'];?></a></label><br>
                <?php 
            ?>
          </div>
          <?php
          
          if(count($rated) < 1):
          ?>
          <div id='done' class='tabcontent'>
            <?php 
                $bidMet = $jp->get_row('bids', ["service" => $service['id'], "id"=>$accepted[0]['accid']])[0]; 
                ?>
            <label>Rate This Service:</label>
            <form method="post">
            <div style='display: flex;'>
            <div class="rating">
                <input type="radio" id="star5" name="five" value="5" />
                <label class="star" for="star5" title="Awesome" aria-hidden="true"></label>
                <input type="radio" id="star4" name="four" value="4" />
                <label class="star" for="star4" title="Great" aria-hidden="true"></label>
                <input type="radio" id="star3" name="three" value="3" />
                <label class="star" for="star3" title="Very good" aria-hidden="true"></label>
                <input type="radio" id="star2" name="two" value="2" />
                <label class="star" for="star2" title="Good" aria-hidden="true"></label>
                <input type="radio" id="star1" name="one" value="1" />
                <label class="star" for="star1" title="Bad" aria-hidden="true"></label>
              </div>
            </div>
          <textarea id="reason" name="reason" placeholder="How was the service provided?" class='input-bx ssmv'></textarea>
            <button name='ratedone' value='<?php echo $bidMet['by'];?>' class='button remew'>Submit Rating</button>
            </form>
          </div>
        <?php 
        else:
        $star = $rated[0]['rating'];
        ?>
         <div class="rating">
                <input type="radio" id="star5" name="five" value="5" disabled/>
                <label class="star" for="star5" title="Awesome" aria-hidden="true"></label>
                <input type="radio" id="star4" name="four" value="4" disabled/>
                <label class="star" for="star4" title="Great" aria-hidden="true"></label>
                <input type="radio" id="star3" name="three" value="3" disabled/>
                <label class="star" for="star3" title="Very good" aria-hidden="true"></label>
                <input type="radio" id="star2" name="two" value="2" disabled/>
                <label class="star" for="star2" title="Good" aria-hidden="true"></label>
                <input type="radio" id="star1" name="one" value="1" disabled/>
                <label class="star" for="star1" title="Bad" aria-hidden="true"></label>
              </div>
              <br>
              <p><?php
              echo $rated[0]['reason'];
              ?></p>
        <?php
        echo "<script>document.getElementById('star{$star}').checked=true;</script>";
        endif;
        ?>
        <?php
        endif;
        else:
        $accepted = $jp->get_row('accepted', ["service" => $service['id']]);
        if(count($accepted) < 1):
        ?>
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'viewbids')">View Bids</button>
            <button class="tablinks" onclick="openTab(event, 'raisebids')">Raise Bid</button>
          </div>
          <div id="viewbids" class="tabcontent">
            <?php 
                $bids = $jp->get_row('bids', ["service" => $service['id']]);
                if(count($bids) < 1){
                    echo "<center>No Bids Yet</center>";
                }
                foreach($bids as $bid){
                    echo "
                    <div class='card'>
                    <label style='font-size: 20px;'>₹{$bid['price']}</label><br>
                    </div>
                    ";
                }
            ?>
          </div>
          <div id="raisebids" class="tabcontent">
            <form method='post'>
                <input type="number" name='price' placeholder="Price [INR]" required class='input-bx ssmv'>
                <input type="email" name='mail' placeholder="Contact Email" required class='input-bx ssmv'>
                <textarea name='message' placeholder="Message for <?php echo $cname; ?>" required class='input-bx ssmv'></textarea>
                <button class='button remew' name='subid'>Raise Bid</button>
            </form>
          </div>

        <?php
        else:
        $rated = $jp->get_row('ratings', ["serviceId" => $service['id']]);
        $star = $rated[0]['rating'];

        ?>
        <div style="display: flex;">
        <div class="rating">
            <input type="radio" id="star5" name="five" value="5" disabled/>
            <label class="star" for="star5" title="Awesome" aria-hidden="true"></label>
            <input type="radio" id="star4" name="four" value="4" disabled/>
            <label class="star" for="star4" title="Great" aria-hidden="true"></label>
            <input type="radio" id="star3" name="three" value="3" disabled/>
            <label class="star" for="star3" title="Very good" aria-hidden="true"></label>
            <input type="radio" id="star2" name="two" value="2" disabled/>
            <label class="star" for="star2" title="Good" aria-hidden="true"></label>
            <input type="radio" id="star1" name="one" value="1" disabled/>
            <label class="star" for="star1" title="Bad" aria-hidden="true"></label>
          </div>
        </div>
          <br>
          <?php 
          $doer = $jp->get_row("users", ["userId" => $rated[0]['for']])[0];
          ?>
          <label><b>Service was provided by: </b><a href='/user/<?php echo $doer['username']; ?>'>@<?php echo $doer['username']; ?></a></label>
          <p><b>Requestor Reviewed on Service: </b><?php
          echo $rated[0]['reason'];
          ?></p>
    <?php
    echo "<script>document.getElementById('star{$star}').checked=true;</script>";
        endif;
        endif;
        ?>
    </div>
</section>
<?php
if(isset($_POST['subid'])){
    $price = $_POST['price'];
    $message = $_POST['message'];
    $mail = $_POST['mail'];
    $user = $current_user['userId'];
    $usern = $current_user['username'];
    $bidId = Basic::random(length: 20);
    $bidVa = [
        "price" => $price,
        "message" => $message,
        "by" => $user,
        "id" => $bidId,
        "service" => $service['id'],
        "mail" => $mail,
        "name" => $usern
    ];
    $jp->add_row('bids', $bidVa);
    echo "<script>window.location.assign(window.location.href)</script>";

}

if(isset($_POST['accept'])){
    $bida = $_POST['accept'];
    $serviceId = $service['id'];
    $arr = [
        "service" =>  $serviceId,
        "accid" => $bida
    ];
    $jp->add_row('accepted', $arr);
    echo "<script>window.location.assign(window.location.href)</script>";

}
if(isset($_POST['cancb'])){
    $bdx = $_POST['cancb'];
    $jp->remove_row("accepted", ['accid'=>$bdx]);
    echo "<script>window.location.assign(window.location.href)</script>";

}
if(isset($_POST['ratedone'])){
    $rating = 0;
    if(isset($_POST['five'])){
        $rating = 5;
    }
    if(isset($_POST['four'])){
        $rating = 4;
    }
    if(isset($_POST['three'])){
        $rating = 3;
    }
    if(isset($_POST['two'])){
        $rating = 2;
    }
    if(isset($_POST['one'])){
        $rating = 1;
    }

    $reason = $_POST['reason'];
    $arm = [
        "rating" => $rating,
        "reason" => $reason,
        "serviceId" => $service['id'],
        "for"=>$_POST['ratedone'],
        "title" => $service['title'],
        "by" => $current_user['username']
    ];
    $jp->add_row("ratings", $arm);
    echo "<script>window.location.assign(window.location.href)</script>";

}
?>
<script>
        function openTab(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>