<?php
include_once 'core/API/parser_interfaces.php';
?>

<div style="background: #efefef; border: 1px solid #676767; width: 90%; margin: 25px auto; padding: 15px; -moz-border-radius: 5px;">
    <div style="padding: 10px; width: 95%; border: 1px solid #454545;-moz-border-radius: 5px; margin: 15px auto;background: white;">
        <div style="float: left; width: 675px;">
            <h2>Validate current configuration.</h2><br>
            <?php
            $ifaces = parse_interfaces("/etc/network/interfaces");
           // echo "<pre>".print_r($ifaces, true)."</pre>";
            ?>
            <form action="#"  style="width: 128px; margin-top: 15px;">
                <input type=submit value="Accept" name="confirm" style="border: 1px solid #454545; background: #bcbcbc; width: 61px; -moz-border-radius: 5px; font-size: 13px;" />
            </form>
        </div>
        <div class="simpleInfo">
            <?php
            /*exec('cat '.$base_dir.'core/structure/confirmation/data/toConfirm', $confirmationInfo);
            foreach ($confirmationInfo as $line) {
                echo $line."<br>";
            }*/
                echo "<b>Ethernet</b><br>";
                if (!empty ($ifaces['eth0']['address'])) {
                    echo "<b>IP: </b> ".$ifaces['eth0']['address']."<br><br>";
                } else {
                    echo "<b>IP: </b> DHCP<br><br>";
                }
                echo "<b>Wifi AP</b><br>";
                echo "<b>IP: </b> ".$ifaces['ath0']['address']."<br><br>";
                if (!empty ($ifaces['ath1']))
                {
                    echo "<b>Wifi Mesh</b><br>";
                    echo "<b>IP: </b> ".$ifaces['ath1']['address']."<br><br>";
                }
            ?>
        </div>
        <div style="clear: both;"></div>
    </div>

    <?php
    if(file_exists("/etc/network/interfaces.lastValidated"))
    {
    ?>
       <div style="padding: 10px; width: 95%; border: 1px solid #454545;-moz-border-radius: 5px; margin: 15px auto;background: white;">
           <div style="float: left; width: 675px;">
                <h2>Return to the last validated configuration.</h2><br>
                <?php
                unset ($ifaces);
                $ifaces = parse_interfaces("/etc/network/interfaces.lastValidated");
               // echo "<pre>".print_r($ifaces, true)."</pre>";
                ?>
                <form action="#"  style="width: 128px; margin-top: 15px;">
                    <input type=submit value="Accept" name="last"  style="border: 1px solid #454545; background: #bcbcbc; width: 61px; -moz-border-radius: 5px; font-size: 13px;"/>
                </form>
            </div>
            <div class="simpleInfo">
                <?php
                /*unset($confirmationInfo);
                exec('cat '.$base_dir.'core/structure/confirmation/data/notConfirm', $confirmationInfo);
                foreach ($confirmationInfo as $line) {
                    echo $line."<br>";
                }*/
                echo "<b>Ethernet</b><br>";
                if (!empty ($ifaces['eth0']['address'])) {
                    echo "<b>IP: </b> ".$ifaces['eth0']['address']."<br><br>";
                } else {
                    echo "<b>IP: </b> DHCP<br><br>";
                }
                echo "<b>Wifi AP</b><br>";
                echo "<b>IP: </b> ".$ifaces['ath0']['address']."<br><br>";
                if (!empty ($ifaces['ath1']))
                {
                    echo "<b>Wifi Mesh</b><br>";
                    echo "<b>IP: </b> ".$ifaces['ath1']['address']."<br><br>";
                }
                ?>
            </div>
            <div style="clear: both;"></div>
        </div>
    <?php
    }
    ?>



    <div style="padding: 10px; width: 95%; border: 1px solid #454545;-moz-border-radius: 5px; margin: 15px auto;background: white;">
        <div style="float: left; width: 675px;">
            <h2>Back to factory presets.</h2><br>
            <?php
                unset ($ifaces);
            $ifaces = parse_interfaces("/etc/network/interfaces.default");
           // echo "<pre>".print_r($ifaces, true)."</pre>";
            ?>
            <form action="#"  style="width: 128px; margin-top: 15px;">
                <input type=submit value="Accept" name="default" style="border: 1px solid #454545; background: #bcbcbc; width: 61px; -moz-border-radius: 5px; font-size: 13px;" />
            </form>
        </div>
        <div class="simpleInfo">
            <?php
            /*unset($confirmationInfo);
            exec('cat '.$base_dir.'core/structure/confirmation/data/default', $confirmationInfo);
            foreach ($confirmationInfo as $line) {
                echo $line."<br>";
            }*/
                echo "<b>Ethernet</b><br>";
                if (!empty ($ifaces['eth0']['address'])) {
                    echo "<b>IP: </b> ".$ifaces['eth0']['address']."<br><br>";
                } else {
                    echo "<b>IP: </b> DHCP<br><br>";
                }
                echo "<b>Wifi AP</b><br>";
                echo "<b>IP: </b> ".$ifaces['ath0']['address']."<br><br>";
                if (!empty ($ifaces['ath1']))
                {
                    echo "<b>Wifi Mesh</b><br>";
                    echo "<b>IP: </b> ".$ifaces['ath1']['address']."<br><br>";
                }
            ?>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>

