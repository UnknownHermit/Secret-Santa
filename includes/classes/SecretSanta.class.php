<?php
class SecretSanta{
    private $participants = Array(); // An array of all santa participants
    private $santaMatches = Array(); // An array of matched pairs 
    private $database = Array( // DB connection info
        "server" => "localhost",
        "username" => "secret_santa",
        "password" => "ilikecoal",
        "database" => "secret_santa"
    );

    /**
     * Populates $articipants from a csv named santas.csv
     */
    public function populateFromCsv(){
        $csvPath = "uploads/santas.csv";
        if( ($handle = fopen($csvPath, "r")) !== FALSE){
            while( ($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                // CSV is in format: Forename(0), Surname(1), Email(2)
                $this->participants[] = Array(
                    "forename" => $data[0],
                    "surname" => $data[1],
                    "email" => $data[2]
                );
            }
            fclose($handle);
        }
        else{

        }
    }

    /**
     * Allocates santas to $santaMatches based on the $participants array
     */
    public function allocateSantas(){
        // Make sure we actually have participants
        if(empty($this->participants) || sizeof($this->participants) == 1){
            return false;
        }
        // Make 2 duplicate arrays. We loop through the givers and assign them someone from the reciever array, then remove that reciever from the array
        $givers = $this->participants;
        $recievers = $this->participants;
        foreach($givers as $k => $giver){
            $isValid = false;
            $c = 0;
            while(!$isValid){
                $randomKey = array_rand($recievers,1);
                if($randomKey != $k){
                    $giver["gifting"] = $recievers[$randomKey];
                    $this->santaMatches[] = $giver;
                    unset($recievers[$randomKey]);
                    $isValid = true;
                }
                else{
                    // Everyone else has been paired up, so swap with someone else randomly from the list
                    if(sizeof($recievers) == 1){
                        $randomKey = array_rand($this->santaMatches,1);
                        $giver["gifting"] = $this->santaMatches[$randomKey]["gifting"];
                        $this->santaMatches[] = $giver;
                        $this->santaMatches[$randomKey]["gifting"] = $givers[$k];
                        $isValid = true;
                    }
                }
                $c++;
            }
        }
        return true;
    }

    public function allocateSantasNotRelated(){
        // Make sure we actually have participants
        if(empty($this->participants) || sizeof($this->participants) == 1){
            return false;
        }
         // Make 2 duplicate arrays. We loop through the givers and assign them someone from the reciever array, then remove that reciever from the array
        $givers = $this->participants;
        $recievers = $this->participants;
        foreach($givers as $k => $giver){
            $isValid = false;
            // Build a temporary array of valid recipients with a different forename as this version doesn't allow for same family secret santas
            $tmpArray = Array();
            foreach($recievers as $rk => $reciever){
                if($giver["surname"] != $reciever["surname"]){
                    $tmpArray[$rk] = $reciever;
                }
            }
            while(!$isValid){
                if(!empty($tmpArray)){
                    $randomKey = array_rand($tmpArray,1);
                    if($randomKey != $k){
                        $giver["gifting"] = $tmpArray[$randomKey];
                        $this->santaMatches[] = $giver;
                        unset($recievers[$randomKey]);
                        $isValid = true;
                    }
                }
                else{
                    // There are no non-family members available, or everyone else is paired up so find a random non-family from the list and swap
                    if(empty($tmpArray) || sizeof($recievers) == 1){
                        $differentSurname = false;
                        $c = 0;
                        while(!$differentSurname){
                            $randomKey = array_rand($this->santaMatches,1);
                            if($this->santaMatches[$randomKey]["surname"] != $giver["surname"]){
                                $giver["gifting"] = $this->santaMatches[$randomKey]["gifting"];
                                $this->santaMatches[] = $giver;
                                $this->santaMatches[$randomKey]["gifting"] = $givers[$k];
                                $differentSurname = true;
                                $isValid = true;
                            }
                            $c++;
                        }  
                    }
                }
                
            }
        }
        return true;
    }

    /**
     * Create a simple table based on the $santaMatches data
     */
    public function getAllocationsTable(){
        $html = "<table>";
        $html .= "<tr>";
        $html .= "<th>Sender<th><th>Recipient</th>";
        $html .= "</tr>";
        foreach($this->santaMatches as $match){
            $html .= "<tr>";
            $html .= "<td>";
            $html .= $match["forename"]." ".$match["surname"]."<br/>".$match["email"];
            $html .= "</td>";
            $html .= "<td>";
            $html .= $match["gifting"]["forename"]." ".$match["gifting"]["surname"]."<br/>".$match["gifting"]["email"];
            $html .= "</td>";
            $html .= "</tr>";
        }
        $html .= "</table>";
        return $html;
    }

    /**
     * Check to see if a string is empty
     */
    private function stringIsEmpty($string){
        if(strlen($string) > 0 && strlen(trim($string)) == 0){
            return true;
        }
        return false;
    }

    /**
     * Check to see if an email is valid
     */
    private function isValidEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }

    /**
     * Connect to our DB
     */
    private function connectToDB(){
        $con = new mysqli(
            $this->database["server"],
            $this->database["username"],
            $this->database["password"],
            $this->database["database"]
        );
        if($con->connect_errno){
            print "Error: something went wrong connecting to the database";
            die();
        }
        else{
            return $con;
        }
    }

    /**Insert a user into the database 
     * 
    */
    private function apiCreateUser($forename, $surname, $email){
        $errors = Array();
        $status = Array();
        // We must have all 3 fields
        print $forename;
        if($this->stringIsEmpty($forename)){
            $errors[] = "Forename is empty";
        }
        if($this->stringIsEmpty($surname)){
            $errors[] = "Surname is empty";
        }
        if(!$this->isValidEmail($email)){
            $errors[] = "Email is invalid";
        }
        if(empty($errors)){
            // Create our connection and escape the data
            $db = $this->connectToDB();
            $forename = $db->real_escape_string($forename);
            $surname = $db->real_escape_string($surname);
            $email = $db->real_escape_string($email);
            // Insert the data
            $sqlIns = "INSERT INTO secret_santa (forename, surname, email)
                        VALUES ('$forename','$surname','$email');";
            if($db->query($sqlIns)){
                $status["status"] = "OK";
            }
            else{
                $errors[] = "Insert query failed";
            }
            $db->close();
        }
        
        $status["errors"] = $errors;
        return $status;
        
    }

    /**
     * Return a users data based on a provided ID
     */
    private function apiReadUser($id){
        $errors = Array();
        $status = Array();
        // We must have an ID field
        if($this->stringIsEmpty($id)){
            $errors[] = "ID is empty";
        }
        if(empty($errors)){
            // Create our connection and escape the data
            $db = $this->connectToDB();
            $id = $db->real_escape_string($id);
            $sqlSel = "SELECT * FROM secret_santa WHERE id='$id'";
            $sqlRes = $db->query($sqlSel);
            // Fetch the data
            if($sqlRes->num_rows > 0){
                $userData = Array();
                while($data = $sqlRes->fetch_assoc()){
                    $userData["id"] = $data["id"];
                    $userData["forename"] = $data["forename"];
                    $userData["surname"] = $data["surname"];
                    $userData["email"] = $data["email"];
                }
                $status["status"] = "OK";
                $status["userData"] = $userData;
            }
            else{
                $errors[] = "Select query failed";
            }
            $db->close();
        }
        
        $status["errors"] = $errors;
        return $status;
    }

    /**
     * Updates a users data based on a provided ID
     */
    private function apiUpdateUser($id, $forename, $surname, $email){
        $errors = Array();
        $status = Array();
        // We must have all 4 fields
        if($this->stringIsEmpty($id)){
            $errors[] = "ID is empty";
        }
        if($this->stringIsEmpty($forename)){
            $errors[] = "Forename is empty";
        }
        if($this->stringIsEmpty($surname)){
            $errors[] = "Surname is empty";
        }
        if(!$this->isValidEmail($email)){
            $errors[] = "Email is invalid";
        }
        if(empty($errors)){
            // Create our connection and escape the data
            $db = $this->connectToDB();
            $id = $db->real_escape_string($id);
            $forename = $db->real_escape_string($forename);
            $surname = $db->real_escape_string($surname);
            $email = $db->real_escape_string($email);
            // Update the data
            $sqlUpd = "UPDATE secret_santa SET
                       forename = '$forename',
                       surname = '$surname',
                       email = '$email'
                       WHERE id = '$id'";
            if($db->query($sqlUpd)){
                $status["status"] = "OK";
            }
            else{
                $errors[] = "Update query failed";
            }
            $db->close();
        }
        
        $status["errors"] = $errors;
        return $status;
    }
    
    /**
     * Delete a user based on a provided ID
     */
    private function apiDeleteUser($id){
        $errors = Array();
        $status = Array();
        // We must have an ID
        if($this->stringIsEmpty($id)){
            $errors[] = "ID is empty";
        }
        if(empty($errors)){
            // Create our connection and escape the data
            $db = $this->connectToDB();
            $id = $db->real_escape_string($id);
            // Delete the data
            $sqlDel = "DELETE FROM secret_santa WHERE id='$id'";
            if($db->query($sqlDel)){
                $status["status"] = "OK";
            }
            else{
                $errors[] = "Select query failed";
            }
            $db->close();
        }
        
        $status["errors"] = $errors;
        return $status;
    }

    /**
     * Uses the users in the database to create a secret santa allocation list
     */
    private function apiGetSantas(){
        $errors = Array();
        $db = $this->connectToDB();
        $sqlSel = "SELECT * FROM secret_santa";
        $sqlRes = $db->query($sqlSel);
        if($sqlRes->num_rows > 0){
            $userData = Array();
            while($data = $sqlRes->fetch_assoc()){
                $this->participants[] = Array(
                    "forename"=> $data["forename"],
                    "surname"=> $data["surname"],
                    "email" => $data["email"]
                );
            }
            if($this->allocateSantas()){
                $status["status"] = "OK";
                $status["santaMatches"] = $this->santaMatches;
            }
            else{
                $errors[] = "Unable to allocate secret santas";
            }
        }
        else{
            $errors[] = "Select query failed";
        }
        $db->close();
        $status["errors"] = $errors;
        return $status;
    }

    /**
     * Processes any API requests
     */
    public function processApiRequest($action){
        $errors = Array();
        if(empty($action)){
            $errors[] = "No action recieved";
        }
        if(empty($errors)){
            switch($action){
                case "create":
                    $forename = $_POST["forename"];
                    $surname = $_POST["surname"];
                    $email = $_POST["email"];
                    $status = $this->apiCreateUser($forename,$surname,$email);
                    break;
                case "read":
                    $id = $_POST["ID"];
                    $status = $this->apiReadUser($id);
                    break;
                case "update":
                    $id = $_POST["ID"];
                    $forename = $_POST["forename"];
                    $surname = $_POST["surname"];
                    $email = $_POST["email"];
                    $status = $this->apiUpdateUser($id,$forename,$surname,$email);
                    break;
                case "delete":
                    $id = $_POST["ID"];
                    $status = $this->apiDeleteuser($id);
                    break;
                case "getSantas":
                    $status = $this->apiGetSantas();
                    break;
                default:
                    $errors[] = "No valid action recieved";
                break;
            }
        }
        if(!empty($errors)){
            $status["errors"] = $errors;
        }
        return $status;
    }
}


?>