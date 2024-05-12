<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/src/userload.php';
$thisUser = false;
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
    .container{
        width: 600px;
        padding:10px;
    }
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
  max-height: 300px;
  overflow-y: scroll;
}
.ssmv{
    box-sizing: border-box;
}
.card{
    border-style: solid;
    padding:5px;
    margin-bottom: 5px;
    border-width: 1px;
    border-radius: 3px;
}
.dellt{
    border:none;
    background: transparent;
    color: red;
    cursor:pointer;
    font-size: 14px;
}
</style>
<?php 
if($thisUser):
$inff_desc = $jp->get_row("info", ["userId" => $current_user['userId'], "type" => "desc"], "reverse");
$final_desc = "";
if(count($inff_desc) > 0){
    $final_desc = $inff_desc[0]['value'];
}
$inff_links = $jp->get_row("info", ["userId" => $current_user['userId'], "type" => "link"]);
$rbys = $jp->get_row("services", ["by" => $current_user['userId']], "reverse");
?>
<section style="position: absolute;top:50px;left:0px;right:0px;bottom:0px;padding:10px;display:flex;justify-content: center;">
    <div class='container'>
        <label style='font-size: 33px;'><?php echo $current_user['displayname']; ?></label><br>
        <label style='font-size: 12px;'>@<?php echo $current_user['username']; ?></label>
        <label style='display:block;margin: 10px 0px;'><b>Description: </b></label>
        <form method="post">
        <textarea id='userdesc' name='userdesc' placeholder='Your Description' onfocus="disz()" required><?php echo $final_desc; ?></textarea>
        <button class='button remew' id='descsave' name='descr' style='display:none;'>Save</button>
        </form>
        <br>
        <label style='display:block;margin: 10px 0px;'><b>Links: </b></label>
        <?php 
        if(count($inff_links) < 1){
            echo "<center>No Links Yet.</center>";
        }
        foreach($inff_links as $link){
            echo "<form method='post'><a href='{$link['value']}'>{$link['value']}</a><button name='erase' class='dellt' value='{$link['id']}'>&nbsp;<i class='uil uil-trash-alt'></i>&nbsp;Delete</button></form><br>";
        }
        ?>
        <form method='post' style="display: flex;align-items: center;">
            <input class='input-bx' style='width:89%; padding:5px;' placeholder="Enter Link" name='url' required>
            <button name='slink' class='button remew' style='padding:5px;width:9%;display: inline-block;height: 30px;'>Add</button>
        </form>
        <br>
        <label style='display:block;margin: 10px 0px;'><b>Links: </b></label>
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'serprov')">Provided</button>
            <button class="tablinks" onclick="openTab(event, 'serreq')">My Requests</button>
            <button class="tablinks" onclick="openTab(event, 'rqst')">Requestsed</button>
            <button class="tablinks" onclick="openTab(event, 'ratings')">Ratings</button>
            <button class="tablinks" onclick="openTab(event, 'renew')">Request New</button>

          </div>
          <div id="serprov" class="tabcontent">
            <?php 
        $ratings = $jp->get_row("ratings", ["for" => $current_user['userId']]);
        foreach($ratings as $rating){
            echo "
            <div class='card'>
                <label style='font-size: 20px;'><a href='/view/{$rating['serviceId']}'>{$rating['title']}</a></label><br>
                <label><b>Stars: </b>{$rating['rating']}</label><br>
                <label><b>Message: </b>{$rating['reason']}</label><br>
                <label><a href='/user/{$rating['by']}'>@{$rating['by']}</a></label><br>
            </div>
            ";
        }  
        ?>
          </div>
          
          <div id="serreq" class="tabcontent">
            <?php 
            $bids = $jp->get_row('bids', ["by"=> $current_user['userId']]);
            foreach ($bids as $bval) {
                $acced = $jp->get_row('accepted', ['accid' => $bval['id']]);
                if(count($acced) > 0){
                    $service = $jp->get_row('services', ['id' => $acced[0]['service']]);
                    foreach($service as $semc){
                        if(count($jp->get_row('ratings', ['serviceId'=>$semc['id']])) < 1){
                            $user = $jp->get_row('users', ['userId' => $semc['by']])[0];
                            echo "
            <div class='card'>
                <label style='font-size: 20px;'><a href='/view/{$semc['id']}'>{$semc['title']}</a></label><br>
                <label>{$semc['desc']}</label><br>
                <label><a href='/user/{$user['username']}'>@{$user['username']}</a></label><br>
            </div>
            ";
                        }
                    }
                }
            }    
            ?>
          </div>

          <div id="rqst" class="tabcontent">
            <?php 
            if(count($rbys) < 1){
                echo "<center>You have not requested any service!</center>";
            }else{
                foreach($rbys as $row):?>
                    <div class='card'>
                        <label class='acclink'><a href='/view/<?php echo $row['id'];?>'><b><?php echo $row['title']; ?></b></a></label><br>
                        <label><?php echo $row['desc']; ?></label><br>
                        <?php 
                        $itags = $jp->get_row("tags", ["id" => $row['id']]);
                        $mstr = "";
                        foreach($itags as $tgg){
                            if($mstr == ""){
                                $mstr = $tgg['tag'];
                            }else{
                                $mstr .= ", ".$tgg['tag'];
                            }
                        }
                        ?>

                        <label style='color:grey;font-size:12px;'><?php echo $mstr;?></label>
                    </div>
                <?php
                endforeach;
            }
            ?>
          </div>
          
          <div id="ratings" class="tabcontent">
            <?php 
        $ratemv = 0;
        $rcount = 0;
        foreach($ratings as $rating){
            $ratemv += $rating['rating'];
            $rcount += 1;
        }    
        if($rcount != 0){ 
        $ratingF = $ratemv / $rcount;
        }else{
            $ratingF = 0;
        }
        ?>
        <div style='display: flex;'>
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
        <?php 
        if($ratingF != 0){
            $amb = abs($ratingF);
            echo "<script>document.getElementById('star{$amb}').checked=true;</script>";
        }
        ?>
            <label><b>Stars: </b><?php echo $ratingF; ?></label>
          </div>
          <div id="renew" class="tabcontent">
            <form method="post">
                <input type='text' name='title' placeholder='Service Title' class='input-bx ssmv' required>
                <textarea name='descser' placeholder='Service Title' class='input-bx ssmv' required></textarea>
                <input type='text' name='tags' placeholder='Tags to appear under (Seperate by comma)' class='input-bx ssmv' required>
                <button name='pstrq' class='button remew'>Submit</button>
            </form>
          </div>
    </div>
</section>
<?php 
else:
$inff_desc = $jp->get_row("info", ["userId" => $otheruser['userId'], "type" => "desc"], "reverse");
$final_desc = "";
if(count($inff_desc) > 0){
    $final_desc = $inff_desc[0]['value'];
}
$inff_links = $jp->get_row("info", ["userId" => $otheruser['userId'], "type" => "link"]);

?>
<section style="position: absolute;top:50px;left:0px;right:0px;bottom:0px;padding:10px;display:flex;justify-content: center;">
    <div class='container'>
        <label style='font-size: 33px;'><?php echo $otheruser['displayname']; ?></label><br>
        <label style='font-size: 12px;'>@<?php echo $otheruser['username']; ?></label>
        <label style='display:block;margin: 10px 0px;'><b>Description: </b></label>
        <div id='userdesc' name='userdesc' placeholder='Your Description' style='text-align: justify;'><?php echo $final_desc; ?></div>
        <br>
        <label style='display:block;margin: 10px 0px;'><b>Links: </b></label>
        <?php 
        if(count($inff_links) < 1){
            echo "<center>No Links Yet.</center>";
        }
        foreach($inff_links as $link){
            echo "<a href='{$link['value']}'>{$link['value']}</a><br>";
        }
        ?>
    <br>

    <label style='display:block;margin: 10px 0px;'><b>Services: </b></label>
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'serprov')">Provided</button>
        <button class="tablinks" onclick="openTab(event, 'ratings')">Ratings</button>

      </div>
      <div id="serprov" class="tabcontent">
        <?php 
        $ratings = $jp->get_row("ratings", ["for" => $otheruser['userId']]);
        foreach($ratings as $rating){
            echo "
            <div class='card'>
                <label style='font-size: 20px;'><a href='/view/{$rating['serviceId']}'>{$rating['title']}</a></label><br>
                <label><b>Stars: </b>{$rating['rating']}</label><br>
                <label><b>Message: </b>{$rating['reason']}</label><br>
                <label><a href='/view/{$rating['by']}'>@{$rating['by']}</a></label><br>
            </div>
            ";
        }   
        ?>
      </div>


      <div id="ratings" class="tabcontent">
        <?php 
        $ratemv = 0;
        $rcount = 0;
        foreach($ratings as $rating){
            $ratemv += $rating['rating'];
            $rcount += 1;
        }   
        if($rcount != 0){ 
        $ratingF = $ratemv / $rcount;
        }else{
            $ratingF = 0;
        }
        ?>
        <div style='display: flex;'>
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
            <?php 
            if($ratingF != 0){
                $amb = abs($ratingF);
                echo "<script>document.getElementById('star{$amb}').checked=true;</script>";
            }
            ?>
            <label><b>Stars: </b><?php echo $ratingF; ?></label>
      </div>
</div>
</section>
<?php 
endif;
?>
<?php 
if(isset($_POST['descr'])){
    $val = htmlentities($_POST['userdesc']);
    $userinf = [
        "userId" => $current_user['userId'],
        "type" => "desc",
        "value"=> $val,
        "id" => Basic::random()
    ];
    $jp->add_row("info", $userinf);
    echo "<script>window.location.assign(window.location.href)</script>";

}

if(isset($_POST['slink'])){
    $val = $_POST['url'];
    $userinf = [
        "userId" => $current_user['userId'],
        "type" => "link",
        "value"=> $val,
        "id" => Basic::random()
    ];
    $jp->add_row("info", $userinf);
    echo "<script>window.location.assign(window.location.href)</script>";
}

if(isset($_POST['erase'])){
    $idtl = $_POST['erase'];
    $jp->remove_row("info", ["userId"=>$current_user['userId'], "id"=>$idtl]);
    echo "<script>window.location.assign(window.location.href)</script>";

}

if(isset($_POST['pstrq'])){
    $servicetitle = $_POST['title'];
    $descr = $_POST['descser'];
    $tags = explode(",", $_POST['tags']);
    $idmq = Basic::random(special: false, length: 12);
    $now = DateTime::createFromFormat('U.u', microtime(true));
    $dte = $now->format("d-m-Y");
    $services = [
        "title" => $servicetitle,
        "desc" => $descr,
        "type" => "request",
        "id"=>$idmq,
        "by" => $current_user['userId'],
        "date" => $dte
    ];
    $jp->add_row("services", $services);
    foreach($tags as $tag){
        $tagt = [
            "id" => $idmq,
            "tag" => $tag
        ];
        $jp->add_row("tags", $tagt);
    }
    echo "<script>window.location.assign(window.location.href)</script>";

}
?>
<script>
    function disz() {
        document.getElementById('descsave').style.display = "block";
    }
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