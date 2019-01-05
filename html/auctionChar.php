<?php require_once 'engine/init.php';
include 'layout/overall/header.php'; ?>
<?php
    $cache = new Cache('engine/cache/auction_system');
    if ($cache->hasExpired()) {
        $auctions = mysql_select_multi('SELECT `auction_system`.`player`, `auction_system`.`id`, `auction_system`.`item_name`, `auction_system`.`item_id`, `auction_system`.`count`, `auction_system`.`cost`, `auction_system`.`date`, `players`.`name` FROM `auction_system`, `players` WHERE `players`.`id` = `auction_system`.`player` ORDER BY `auction_system`.`id` DESC');
        $cache->setContent($auctions);
        $cache->save();
    } else {
        $auctions = $cache->load();
    }
   $players = 0;
   
    if (isset($_POST)) {
        if (isset($_POST['player']) && ! empty($_POST['player'])) {
            $name = getValue($_POST['player']);
            $player_id = user_character_exist($name);
            $auctions = array_filter($auctions, function($auction) use($player_id) {
                return $player_id == $auction['player'];
            });
        }
        if (isset($_POST['item']) && ! empty($_POST['item'])) {
            $item = getValue($_POST['item']);
            $auctions = array_filter($auctions, function($auction) use($item) {
                return strtolower($item) == strtolower($auction['item_name']);
            });
        }
    }  
?>
<script type="text/javascript">
function show_hide(flip)
{
   var tmp = document.getElementById(flip);
   if(tmp)
       tmp.style.display = tmp.style.display == 'none' ? '' : 'none';
}
</script>
<a onclick="show_hide('commands'); return false;" style="cursor: pointer;">Click here to show the Instructions</a><br/>
<div id="commands" style="display: none;">
   <table border="0" cellspacing="1" cellpadding="4" width="100%">
       <tr bgcolor="#505050">
           <td class="white"><b>Instructions<b></td>
       </tr>
       <td><center><h2>Commands</h2>
           <b>!market add,ItemName,ItemPrice,ItemCount</b><br>
           <small>Example: <b>!market add,carlin sword,500,1</small><br>
           <small><font color="red">DO NOT USE SPACE BETWEEN COMMAS !!!</font></b></small><br><br>
           <b>!market buy, AuctionID</b><br><small>Example: <b>!market buy, 1943</b></small><br><br>
           <b>!market remove, AuctionID</b><br><small>Example: <b>!market remove, 1943</b></small><br><br>
           <b>!market withdraw</b><br><small>Use this command to get money for sold items.</small><br><br>
           <small><font color="red"><b>Requirements:<br>Your character must be level 8 or higher to make an offer and must have a vocation.</b></font></small>
       </center></td>
   </table>
</div><p></p>
        <?php
        if(empty($auctions) || $auctions === false) {
        ?>
   <table border="0" cellspacing="1" cellpadding="4" width="100%">
       <tr bgcolor="#505050">
           <td class="white">
               <b>Auctions</b>
           </td>
       </tr>
       <tr bgcolor="#D4C0A1">
           <td><center>Currently there is no item in Market.</center></td>
       </tr>
   </table>
    <br>
    <?php
        } else {
    ?>
   
   <form action="" method="POST" align="right">
        <input type="text" name="player" placeholder="Type the seller name...">
        <input type="text" name="item" placeholder="Type the item name...">
        <input type="submit" value="Search">
    </form>
   
   <table border="0" cellspacing="1" cellpadding="4" width="100%">
   <tr bgcolor="#505050">
       <td class="white"><b><center>Offer ID</center></b></td>
       <td class="white"><b><center>Item Image</center></b></td>
       <td class="white"><b><center>Item Name</center></b></td>
       <td class="white"><b><center>Seller</center></b></td>
       <td class="white"><b><center>Item Count</center></b></td>
       <td class="white"><b><center>Item Cost</center></b></td>
       <td class="white"><b><center>Buy</center></b></td>
   </tr>
   <?php
   foreach($auctions as $auction) {
   $players++;
   if(is_int($players / 2)) {
       $bgcolor = '#F1E0C6';
   } else {
       $bgcolor = '#D4C0A1';
       $cost = round($auction['cost'] / 1000, 2);
   }  
   ?>
    <tr bgcolor=<?php echo $bgcolor ?>>
       <td class="white"><center><?php echo $auction['id'] ?></center></td>
       <td class="white"><center><?php echo '<img src="http://'. $config['shop']['imageServer'] .''.$auction['item_id'].'.gif"/>' ?></center></td>
       <td class="white"><center><?php echo $auction['item_name'] ?></center></td>
       <td class="white"><center><a href="characterprofile.php?name=<?php echo urlencode($auction['name']) ?>"><?php echo $auction['name'] ?></a></center></td>
       <td class="white"><center><?php echo $auction['count'] ?></center></td>
       <td class="white"><center><?php echo $cost ?>k<br><small><?php echo $auction['cost'] ?>gp</small></center></td>
       <td class="white"><center>!market buy, <?php echo $auction['id'] ?></center></td>
       </tr> <?php } } ?>
   </table>
<?php include 'layout/overall/footer.php'; ?>