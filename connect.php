<?php
    
    class Database {
        private $con;
        
        public function connect()   {  
            $servername = getenv("IP");
            $username = getenv("C9_USER");
            $password = "Qwerty123";
            $database = "c9";
            $dbport = 3306;

            $this->con = mysqli_connect($servername, $username, $password, $database, $dbport) or die(mysql_error());
        }
        
        public function disconnect() {
            mysqli_close($this->con);
        }
        
        private function insertItemDetail($id, $name, $type, $level, $type2) {
            $name = mysqli_real_escape_string($this->con, $name);
            mysqli_query($this->con, "INSERT INTO item_detail (id, name, type, level, type2) VALUES($id,'$name','$type',$level,'$type2') 
            ON DUPLICATE KEY UPDATE name='$name', type='$type', level=$level, type2='$type2'");
        }
        
        public function getAllWatchList() {
            if ($result = mysqli_query($this->con, "SELECT item_watchlist.id, item_detail.name FROM item_watchlist
                LEFT JOIN item_detail ON item_watchlist.id = item_detail.id")) {
                $myArray = array();
                while($row = $result->fetch_array(MYSQL_ASSOC)) {
                    $myArray[] = $row;
                }
                echo json_encode($myArray);

                if (!mysqli_query($this->con, "SET @a:='this will not work'")) {
                    printf("Error: %s\n", mysqli_error($this->con));
                }
            }
        }
        
        public function insertWatchList($id) {
            if (!empty($id)) {
                if ($result = mysqli_query($this->con, "SELECT * FROM item_watchlist")) {
                    $rowcount = mysqli_num_rows($result);
                    if (intval($rowcount) <= 100) {
                        mysqli_query($this->con, "INSERT INTO item_watchlist (id) VALUES($id) ON DUPLICATE KEY UPDATE id=$id");
                        echo $id;
                    } else {
                        echo "n";
                    }
                    mysqli_free_result($result);
                }
            } else {
                echo "ID is required";
            }
        }
        
        public function deleteWatchList($id) {
            if (!empty($id)) {
                mysqli_query($this->con, "DELETE FROM item_watchlist WHERE id=".$id);
                echo $id;
            } else {
                echo "ID is required";
            }
        }
        
        public function getAllItemDetails() {
            $data = json_decode(file_get_contents("https://api.guildwars2.com/v2/commerce/prices/"));
            foreach($data as $item){
                $detail = json_decode(file_get_contents("https://api.guildwars2.com/v2/items/". $item), true);
                $this->insertItemDetail($detail["id"], $detail["name"], $detail["type"], $detail["level"], $detail["details"]["type"]);
            }
        }
        
        public function getAllItemDetailsFromDB() {
            if ($result = mysqli_query($this->con, "SELECT * FROM item_detail", MYSQLI_USE_RESULT)) {
                $myArray = array();
                while($row = $result->fetch_array(MYSQL_ASSOC)) {
                    $myArray[] = $row;
                }
                echo json_encode($myArray);

                if (!mysqli_query($this->con, "SET @a:='this will not work'")) {
                    printf("Error: %s\n", mysqli_error($this->con));
                }
                mysqli_free_result($result);
            }
        }
        
        private function insertItemPrice($itemId, $maxOffer, $qtyOffer, $minSale, $qtySale) {
            mysqli_query($this->con, "INSERT INTO item_price (item_id, max_offer_unit_price, all_offer_quantity, min_sale_unit_price, all_sale_quantity) 
            VALUES($itemId,$maxOffer,$qtyOffer,$minSale,$qtySale)");
        }
        
        public function insertAllItemPrices() {
            if ($result = mysqli_query($this->con, "SELECT * FROM item_watchlist")) {
                while($row = $result->fetch_array(MYSQL_ASSOC)) {
                    $detail = json_decode(file_get_contents("https://api.guildwars2.com/v2/commerce/prices/". $row["id"]), true);
                    $this->insertItemPrice($detail["id"], $detail["buys"]["unit_price"], $detail["buys"]["quantity"], $detail["sells"]["unit_price"], $detail["sells"]["quantity"]);
                }

                if (!mysqli_query($this->con, "SET @a:='this will not work'")) {
                    printf("Error: %s\n", mysqli_error($this->con));
                } else {
                    printf("OK");
                }
            }
        }
        
        public function getItemHistory($id) {
            if ($result = mysqli_query($this->con, "SELECT * FROM item_price WHERE item_id=".$id, MYSQLI_USE_RESULT)) {
                $myArray = array();
                while($row = $result->fetch_array(MYSQL_ASSOC)) {
                    $myArray[] = $row;
                }
                echo json_encode($myArray);

                if (!mysqli_query($this->con, "SET @a:='this will not work'")) {
                    printf("Error: %s\n", mysqli_error($this->con));
                }
                mysqli_free_result($result);
            }
        }
    }
    
?>